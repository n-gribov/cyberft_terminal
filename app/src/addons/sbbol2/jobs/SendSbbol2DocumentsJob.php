<?php


namespace addons\sbbol2\jobs;

use addons\edm\models\Sbbol2PayDocRu\Sbbol2PayDocRuType;
use addons\sbbol2\apiClient\api\PaymentsApi;
use addons\sbbol2\apiClient\ApiException;
use addons\sbbol2\jobs\SendSbbol2DocumentsJob\ImportRequestResult;
use addons\sbbol2\models\Sbbol2DocumentImportRequest;
use common\models\cyberxml\CyberXmlDocument;
use Exception;
use Yii;



class SendSbbol2DocumentsJob extends BaseJob
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
            ->where(['status' => Sbbol2DocumentImportRequest::STATUS_PENDING])
            ->orderBy(['importAttemptDate' => SORT_ASC, 'createDate' => SORT_ASC])
            ->limit(static::MAX_REQUESTS_PER_JOB_RUN)
            ->all();
    }

    private function processRequest(Sbbol2DocumentImportRequest $request)
    {
        $this->log("Processing import request {$request->id}, document: {$request->documentId}");

        $result = $this->sendDocumentImportRequest($request);
        if (!$result->hasError()) {
            $request->updateStatus(Sbbol2DocumentImportRequest::STATUS_SENT);
            $request->updateExternalDocumentId($result->getExternalId());
        } elseif (!$result->canRetry()) {
            $request->updateStatus(Sbbol2DocumentImportRequest::STATUS_SENDING_ERROR);
        }
    }

    /**
     * @param Sbbol2DocumentImportRequest $importRequest
     * @return ImportRequestResult
     */
    private function sendDocumentImportRequest(Sbbol2DocumentImportRequest $importRequest)
    {
        $module = Yii::$app->getModule('sbbol2');

        /** @var PaymentsApi $paymentsApi */
        $paymentsApi = $module->apiFactory->create(PaymentsApi::class);

        $importRequest->touchImportAttemptDate();

        $document = $importRequest->document;
        $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);

        // TODO replace with general type
        /** @var Sbbol2PayDocRuType $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();
        $sbbol2Document = $typeModel->document;

        if (empty($sbbol2Document)) {
            $this->log('Sbbol2 document is empty');
            return new ImportRequestResult(true, false, null);
        }

        $accessToken = $module->apiAccessTokenProvider->getForCustomer($importRequest->customerId);

        Yii::info("access token $accessToken");

        try {
            $response = $paymentsApi->createUsingPOST7($sbbol2Document, $accessToken);
        } catch (ApiException $exception) {
            $this->log("Got error response from Sberbank client info API, status: {$exception->getCode()}, response: {$exception->getResponseBody()}");
            return new ImportRequestResult(true, false, null);
        }

        if ($response[1] === 500) {
            return new ImportRequestResult(true, true, null);
        }

        $externalId = $sbbol2Document->getExternalId();

        return new ImportRequestResult(false, false, $externalId);
    }
}
