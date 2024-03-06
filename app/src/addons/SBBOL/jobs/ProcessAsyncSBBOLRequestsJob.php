<?php

namespace addons\SBBOL\jobs;

use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\models\soap\request\GetRequestStatusSRPRequest;
use addons\SBBOL\states\in\response\ProcessAsyncResponseState;
use common\components\Resque;
use common\jobs\StateJob;
use Resque_Job_DontPerform;
use Yii;
use yii\db\Expression;

class ProcessAsyncSBBOLRequestsJob extends BaseJob
{
    const MAX_REQUESTS_PER_JOB_RUN = 100;

    public function setUp()
    {
        parent::setUp();

        $this->module = Yii::$app->getModule('SBBOL');
        if (empty($this->module)) {
            throw new Resque_Job_DontPerform('SBBOL module not found');
        }
    }

    public function perform()
    {
        $this->log('Checking for pending requests...', false);

        try {
            $requests = $this->findPendingRequests();
            if (empty($requests)) {
                $this->log('Nothing to do', false);

                return;
            }

            $requestsByCustomerId = static::partitionRequestsByCustomerId($requests);
            foreach ($requestsByCustomerId as $customerId => $customerRequests) {
                $this->processRequests($customerId, $customerRequests);
            }
        } catch (\Exception $ex) {
            $this->log("Requests processing failed, caused by: $ex");
        }
    }

    private function processRequests($customerId, $requests)
    {
        $responsesBodies = $this->fetchRequestsResults($customerId, $requests);
        foreach ($responsesBodies as $index => $responseBody) {
            $this->processResponse($requests[$index], $responseBody, $customerId);
        }
    }

    /**
     * @param string $customerId
     * @param SBBOLRequest[] $requests
     * @return string[]
     */
    private function fetchRequestsResults($customerId, $requests)
    {
        $this->log('Sending status request...', false);

        $requestsIds = array_map(
            function (SBBOLRequest $request) {
                return $request->status === SBBOLRequest::STATUS_SENT ? $request->receiverRequestId : $request->documentStatusRequestId;
            },
            $requests
        );

        $transport = $this->module->transport;
        $requestMessage = new GetRequestStatusSRPRequest([
            'sessionId' => $this->module->sessionManager->findOrCreateSession($requests[0]->holdingHeadCustomerId),
            'orgId' => $customerId,
            'requests' => $requestsIds
        ]);
        $responseMessage = $transport->send($requestMessage);

        $responsesBodies = $responseMessage->return;

        if ($responsesBodies === null) {
            return [];
        } else if (is_array($responsesBodies)) {
            return $responsesBodies;
        } else {
            return [$responsesBodies];
        }
    }

    private function processResponse(SBBOLRequest $request, $responseBody, $customerId)
    {
        $this->log("Processing response for {$request->documentType}, {$request->id}...", false);
        $request->responseCheckDate = date('Y-m-d H:i:s');
        // Сохранить модель в БД
        $request->save();

        list($hasResponse, $canRetry) = $this->checkResponse($request, $responseBody);

        if (!$hasResponse) {
            if (!$canRetry) {
                $request->status = SBBOLRequest::STATUS_REJECTED;
                // Сохранить модель в БД
                $request->save();
            }
            return;
        }

        Yii::$app->resque->enqueue(
            StateJob::class,
            [
                'stateClass' => ProcessAsyncResponseState::class,
                'params' => serialize([
                    'requestId'    => $request->id,
                    'responseBody' => $responseBody
                ])
            ],
            true,
            Resque::INCOMING_QUEUE
        );
    }

    private function findPendingRequests()
    {
        $threeSecondsAgo = new Expression('current_timestamp() - interval 3 second');

        $requests = SBBOLRequest::find()
            ->where(['status' => [SBBOLRequest::STATUS_SENT, SBBOLRequest::STATUS_DELIVERED]])
            ->andWhere(['<', 'dateCreate', $threeSecondsAgo])
            ->andWhere(
                [
                    'or',
                    ['responseCheckDate' => null],
                    ['<', 'responseCheckDate', $threeSecondsAgo]
                ]
            )
            ->orderBy(['dateCreate' => SORT_ASC])
            ->limit(static::MAX_REQUESTS_PER_JOB_RUN)
            ->all();

        $processableRequests = array_reduce(
            $requests,
            function ($carry, SBBOLRequest $request) {
                if ($request->lock(60000)) {
                    $carry[] = $request;
                } else {
                    Yii::info("Cannot acquire lock for request {$request->id}");
                }
                return $carry;
            },
            []
        );

        return $processableRequests;
    }

    private function checkResponse(SBBOLRequest $request, $responseBody)
    {
        $isRequestProcessed = !preg_match('/^\s*<!--\s*([A-Z _]+)\s*-->\s*$/', $responseBody, $matches);
        if (!$isRequestProcessed) {
            $responseCode = $matches[1];
            $this->log("Request {$request->id} is not processed, response code: $responseCode", false);

            return [
                false,
                $responseCode === 'NOT PROCESSED YET'
            ];
        }

        return [true, false];
    }

    /**
     * @param SBBOLRequest[] $requests
     * @return array
     */
    private static function partitionRequestsByCustomerId(array $requests)
    {
        return array_reduce(
            $requests,
            function ($carry, SBBOLRequest $request) {
                $key = $request->customerId;
                $carry[$key][] = $request;
                return $carry;
            },
            []
        );
    }
}
