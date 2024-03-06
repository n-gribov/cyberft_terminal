<?php

namespace addons\sbbol2\jobs;

use addons\sbbol2\apiClient\api\PaymentsApi;
use addons\sbbol2\helpers\Sbbol2ModuleHelper;
use addons\sbbol2\jobs\CheckSbbol2DocumentsStatusJob\StatusRequestResult;
use addons\sbbol2\models\Sbbol2DocumentImportRequest;
use addons\sbbol2\models\Sbbol2DocumentStatus;
use common\document\Document;
use Exception;
use Yii;

class CheckSbbol2DocumentsStatusJob extends BaseJob
{
    const MAX_REQUESTS_PER_JOB_RUN = 100;

    public function perform()
    {
        $module = Yii::$app->getModule('sbbol2');

        $this->log('Checking for pending requests...');

        $requests = $this->findPendingRequests();
        if (empty($requests)) {
            $this->log('Nothing to do');
            return;
        }

        foreach ($requests as $request) {
            try {
                if (!$request->customerId){
                    continue;
                }
                if (!$module->apiAccessTokenProvider->customerHasActiveToken($request->customerId)){
                    continue;
                }
                $this->processRequest($request);
            } catch (Exception $exception) {
                $this->log("Failed to process request {$request->id}, caused by: $exception", true);
            }
        }
    }

    /**
     * @return Sbbol2DocumentImportRequest[]
     */
    private function findPendingRequests()
    {
        return Sbbol2DocumentImportRequest::find()
            ->where(['status' => Sbbol2DocumentImportRequest::STATUS_SENT])
            ->orderBy(['statusCheckDate' => SORT_ASC, 'createDate' => SORT_ASC])
            ->limit(static::MAX_REQUESTS_PER_JOB_RUN)
            ->all();
    }

    private function processRequest(Sbbol2DocumentImportRequest $request)
    {
        $this->log("Processing import request {$request->id}, document: {$request->documentId}");

        $result = $this->getBankDocumentStatus($request);
        if (!$result->hasError()) {
            $request->updateStatus(Sbbol2DocumentImportRequest::STATUS_PROCESSED);
        } else if (!$result->canRetry()) {
            $request->updateStatus(Sbbol2DocumentImportRequest::STATUS_PROCESSING_ERROR);
        }
    }

    private function getBankDocumentStatus(Sbbol2DocumentImportRequest $importRequest)
    {
        $module = Yii::$app->getModule('sbbol2');
        $paymentsApi = $module->apiFactory->create(PaymentsApi::class);

        $importRequest->touchStatusCheckDate();

        $accessToken = $module->apiAccessTokenProvider->getForCustomer($importRequest->customerId);

        try {
            $response = $paymentsApi->getStatusUsingGET9($accessToken, $importRequest->externalDocumentId);
        } catch (Exception $ex) {
            $this->log($ex);
            return new StatusRequestResult(true, false, null);
        }

        $bankStatus = $response->getBankStatus();
        $bankComment = $response->getBankComment();

        $importRequest->bankDocumentStatus = $bankStatus;
        $importRequest->bankComment = $bankComment;
        // Сохранить модель в БД
        $importRequest->save();

        $sbbol2DocumentStatus = new Sbbol2DocumentStatus($bankStatus);

        Sbbol2ModuleHelper::sendPaymentStatusReport(
            Document::findOne($importRequest->documentId),
            $bankComment,
            $sbbol2DocumentStatus
        );

        return new StatusRequestResult(false, !$sbbol2DocumentStatus->isFinal());

    }


}
