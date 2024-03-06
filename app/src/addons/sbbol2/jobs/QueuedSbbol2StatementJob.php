<?php
namespace addons\sbbol2\jobs;

use addons\edm\models\Sbbol2Statement\Sbbol2StatementType;
use addons\sbbol2\apiClient\api\StatementApi;
use addons\sbbol2\apiClient\ApiException;
use addons\sbbol2\models\Sbbol2ScheduledRequest;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\helpers\Lock;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Yii;

class QueuedSbbol2StatementJob extends BaseJob
{
    const MAX_REQUESTS_PER_JOB_RUN = 100;
    /** @var StatementApi $_api */
    private $_api;

    public function perform()
    {
        $this->_api = $this->module->apiFactory->create(StatementApi::class);

        $this->log('Checking for pending requests...');
        $requests = $this->findPendingRequests();

        foreach ($requests as $request) {
            try {
                if (empty($request->customerId)) {
                    continue;
                }
                if (!$this->module->apiAccessTokenProvider->customerHasActiveToken($request->customerId)){
                    continue;
                }
                $this->processRequest($request);
            } catch (Exception $exception) {
                $this->log("Failed to process request {$request->id}, caused by: $exception", true);
            }
        }

        $this->updateFailedOrLockedRequests();
    }

    private function updateFailedOrLockedRequests()
    {
        $request = Sbbol2ScheduledRequest::find()
            ->where(['!=', 'status', Sbbol2ScheduledRequest::STATUS_PENDING])
            ->andWhere(['type' => Sbbol2StatementType::TYPE])
            ->orderBy(['dateUpdate' => SORT_ASC, 'dateCreate' => SORT_ASC])
            ->limit(static::MAX_REQUESTS_PER_JOB_RUN)
            ->all();

        foreach ($request as $request) {
            if (
                $request->status == Sbbol2ScheduledRequest::STATUS_SENDING_ERROR
                || $request->status == Sbbol2ScheduledRequest::STATUS_PROCESSING_ERROR
            ) {
                if ($request->attempt < $request->maxAttempts) {
                    $request->attempt++;
                    $request->updateStatus(Sbbol2ScheduledRequest::STATUS_PENDING);
                } else {
                    // Удалить документ из БД
                    $request->delete();
                }
            } else if ($request->status == Sbbol2ScheduledRequest::STATUS_PROCESSING) {
                $dateUpdate = strtotime($request->dateUpdate);
                if ($dateUpdate + 600 < time()) {
                    $request->updateStatus(Sbbol2ScheduledRequest::STATUS_PENDING);
                }
            } else {
                $request->updateStatus(Sbbol2ScheduledRequest::STATUS_PENDING);
            }
        }
    }

    /**
     * @return Sbbol2ScheduledRequest[]
     */
    private function findPendingRequests()
    {
        return Sbbol2ScheduledRequest::find()
            ->where(['status' => Sbbol2ScheduledRequest::STATUS_PENDING])
            ->andWhere(['type' => Sbbol2StatementType::TYPE])
            ->orderBy(['dateUpdate' => SORT_ASC, 'dateCreate' => SORT_ASC])
            ->limit(static::MAX_REQUESTS_PER_JOB_RUN)
            ->all();
    }

    private function processRequest(Sbbol2ScheduledRequest $request)
    {
        if (!Lock::acquire('queuedSbbol2Statement', $request->id)) {
            $this->log('Scheduled request ' . $request->id . ' is locked by another job instance');

            return false;
        }

        $request->updateStatus(Sbbol2ScheduledRequest::STATUS_PROCESSING);

        $this->log("Processing scheduled request {$request->id}");

        $result = $this->receiveStatement($request);

        if ($result !== false) {
            // Удалить документ из БД
            $request->delete();
        } else {
            $request->updateStatus(Sbbol2ScheduledRequest::STATUS_PROCESSING_ERROR);
        }

        Lock::release('queuedSbbol2Statement', $request->id);
    }

    /**
     * @param Sbbol2ScheduledRequest $request
     * @return \addons\edm\models\Statement\StatementType
     */
    private function receiveStatement(Sbbol2ScheduledRequest $request)
    {
        $accessToken = $this->module->apiAccessTokenProvider->getForCustomer($request->customerId);

        $startDate = $request->getJsonData('startDate');

        try {
            $model = $this->receiveSbbol2Statement(
                $request->getJsonData('account'),
                $startDate,
                $this->_api,
                $accessToken
            );

            if ($model === false) {
                return false;
            }

            $model->statementPeriodStart = date('d.m.Y', strtotime($startDate));
            $model->statementPeriodEnd = $model->statementPeriodStart;

            $terminal = Yii::$app->exchange->defaultTerminal;

            // Создать контекст документа
            $context = DocumentHelper::createDocumentContext(
                $model,
                [
                    'type'               => $model->getType(),
                    'direction'          => Document::DIRECTION_OUT,
                    'origin'             => Document::ORIGIN_SERVICE,
                    'terminalId'         => $terminal->id,
                    'sender'             => $terminal->terminalId,
                    'receiver'           => $request->getJsonData('receiver'),
                    'signaturesRequired' => 0,
                ]
            );

            if (empty($context)) {
                $this->log('Failed to create Sbbol2Statement document');

                return false;
            }

            // Получить документ из контекста
            $document = $context['document'];
            // Отправить документ на обработку в транспортном уровне
            DocumentTransportHelper::processDocument($document, true);

            return true;

        } catch (ApiException $exception) {
            $this->log("Got error response from Sberbank client info API, status: {$exception->getCode()}, response: {$exception->getResponseBody()}");
        }

        return false;
    }

    private function receiveSbbol2Statement($accountNumber, $statementRequestDate, $api, $token)
    {
        $model = new Sbbol2StatementType();
        $model->statementAccountNumber = $accountNumber;
        $model->statementPeriodStart = $statementRequestDate;
        $model->statementPeriodEnd = $statementRequestDate;

        try {
            $result = $api->getSummaryUsingGET($token, $accountNumber, $statementRequestDate);
            if (empty($result)) {
                return false;
            }
            $model->loadFromDataObject($result);
            $result = $this->receiveSbbol2StatementTransactions($accountNumber, $statementRequestDate, $api, $token);
            $model->loadFromDataObject($result);
        } catch(Exception $ex) {
            \Yii::info('Got error response from Sberbank client API, '
                //. "status: {$ex->getResponseObject()}, "
                //. "response: {$ex->getResponseBody()}");
                . $ex->getMessage());
            throw $ex;
        }

        return $model;
    }

    private function receiveSbbol2StatementTransactions($accountNumber, $date, $api, $token)
    {
        $allTransactions = [];
        $page = 1;
        do {
            $result = $api->getTransactionsUsingGET($token, $accountNumber, $date, $page);
            $page = null;
            $allTransactions = array_merge($allTransactions, $result->getTransactions());
            $links = $result->getLinks();
            if ($links) {
                $links = $links[0];
                $href = $links->getHref();
                $rel = $links->getRel();

                if ($href && $rel == 'next') {
                    $array = [];
                    parse_str($href, $array);
                    if (isset($array['page'])) {
                        $page = (int) $array['page'];
                    }
                }
            }
        } while($page);

        $result->setTransactions($allTransactions);

        return $result;
    }

}
