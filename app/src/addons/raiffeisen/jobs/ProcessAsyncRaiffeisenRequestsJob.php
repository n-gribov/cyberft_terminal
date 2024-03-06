<?php

namespace addons\raiffeisen\jobs;

use addons\raiffeisen\models\RaiffeisenRequest;
use addons\raiffeisen\models\soap\request\GetRequestStatusRequest;
use addons\raiffeisen\states\in\response\ProcessAsyncResponseState;
use common\components\Resque;
use common\jobs\StateJob;
use Yii;
use yii\db\Expression;

class ProcessAsyncRaiffeisenRequestsJob extends BaseJob
{
    const MAX_REQUESTS_PER_JOB_RUN = 100;

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
                $this->processRequests($customerRequests);
            }
        } catch (\Exception $ex) {
            $this->log("Requests processing failed, caused by: $ex");
        }
    }

    private function processRequests($requests)
    {
        $responsesBodies = $this->fetchRequestsResults($requests);
        foreach ($responsesBodies as $index => $responseBody) {
            try {
                /** @var RaiffeisenRequest $request */
                $request = $requests[$index];
                $this->processResponse($request, $responseBody);
            } catch (\Exception $exception) {
                $this->log("Requests processing failed, caused by: $exception");
                $request->releaseLock();
            }
        }
    }

    /**
     * @param RaiffeisenRequest[] $requests
     * @return string[]
     */
    private function fetchRequestsResults($requests)
    {
        $this->log('Sending status request...', false);

        $requestsIds = array_map(
            function (RaiffeisenRequest $request) {
                $return = $request->status === RaiffeisenRequest::STATUS_SENT ? $request->receiverRequestId : $request->documentStatusRequestId;
                return $return;
            },
            $requests
        );

        return $this->module->transport->fetchRequestStatuses($requestsIds, $requests[0]->holdingHeadCustomerId);
    }

    private function processResponse(RaiffeisenRequest $request, $responseBody)
    {
        $this->log("Processing response for {$request->documentType}, {$request->id}...", false);
        $request->responseCheckDate = date('Y-m-d H:i:s');
        // Сохранить модель в БД
        $request->save();

        list($hasResponse, $canRetry) = $this->checkResponse($request, $responseBody);

        if (!$hasResponse) {
            if (!$canRetry) {
                $request->status = RaiffeisenRequest::STATUS_REJECTED;
                // Сохранить модель в БД
                $request->save();
            }
            $request->releaseLock();
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

        $requests = RaiffeisenRequest::find()
            ->where(['status' => [RaiffeisenRequest::STATUS_SENT, RaiffeisenRequest::STATUS_DELIVERED]])
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
            function ($carry, RaiffeisenRequest $request) {
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

    private function checkResponse(RaiffeisenRequest $request, $responseBody)
    {
        $isRequestProcessed = !preg_match('/^\s*<!--\s*([A-Z _]+)\s*-->\s*$/', $responseBody, $matches);
        if (!$isRequestProcessed) {
            $responseCode = $matches[1];
            $this->log("Request {$request->id} is not processed, response code: $responseCode", false);

            return [
                false,
                $responseCode === 'NOT PROCESSED YET' || $responseCode === 'NONEXISTENT SESSION'
            ];
        }

        return [true, false];
    }

    /**
     * @param RaiffeisenRequest[] $requests
     * @return array
     */
    private static function partitionRequestsByCustomerId(array $requests)
    {
        return array_reduce(
            $requests,
            function ($carry, RaiffeisenRequest $request) {
                $key = $request->customerId;
                $carry[$key][] = $request;
                return $carry;
            },
            []
        );
    }
}
