<?php


namespace addons\VTB\jobs;


use addons\edm\models\VTBPayDocRu\VTBPayDocRuType;
use addons\VTB\jobs\SendVTBDocumentsJob\ImportRequestResult;
use addons\VTB\models\soap\messages\WSImportSignedDocument\ImportSignedDocumentRequest;
use addons\VTB\models\soap\services\WSImportSignedDocument;
use addons\VTB\models\VTBDocumentImportRequest;
use common\models\cyberxml\CyberXmlDocument;

class SendVTBDocumentsJob extends BaseJob
{
    const MAX_REQUESTS_PER_JOB_RUN = 100;

    public function perform()
    {
        $this->log('Checking for pending requests...');

        $requests = $this->findPendingRequests();
        if (empty($requests)) {
            $this->log('Nothing to do');
            return;
        }

        foreach ($requests as $request) {
            try {
                $this->processRequest($request);
            } catch (\Exception $exception) {
                $this->log("Failed to process request {$request->id}, caused by: $exception", true);
            }
        }
    }

    /**
     * @return VTBDocumentImportRequest[]
     */
    private function findPendingRequests()
    {
        return VTBDocumentImportRequest::find()
            ->where(['status' => VTBDocumentImportRequest::STATUS_PENDING])
            ->orderBy(['importAttemptDate' => SORT_ASC, 'dateCreate' => SORT_ASC])
            ->limit(static::MAX_REQUESTS_PER_JOB_RUN)
            ->all();
    }

    private function processRequest(VTBDocumentImportRequest $request)
    {
        $this->log("Processing import request {$request->id}, document: {$request->documentId}");

        $result = $this->sendDocumentImportRequest($request);
        if (!$result->hasError()) {
            $request->updateStatus(VTBDocumentImportRequest::STATUS_SENT);
        } else if (!$result->canRetry()) {
            $request->updateStatus(VTBDocumentImportRequest::STATUS_SENDING_ERROR);
        }
    }

    /**
     * @param VTBDocumentImportRequest $importRequest
     * @return ImportRequestResult
     */
    private function sendDocumentImportRequest(VTBDocumentImportRequest $importRequest)
    {
        $importRequest->touchImportAttemptDate();

        $document = $importRequest->document;
        $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);

        // TODO replace with general type
        /** @var VTBPayDocRuType $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();
        $vtbDocument = $typeModel->document;
        $signatureInfo = $typeModel->signatureInfo;

        if (empty($vtbDocument)) {
            $this->log('VTB document is empty');
            return new ImportRequestResult(true, false);
        }
        if (empty($vtbDocument)) {
            $this->log('VTB document signature data is empty');
            return new ImportRequestResult(true, false);
        }

        $wsResponse = null;
        try {
            $service = new WSImportSignedDocument();
            $wsRequest = (new ImportSignedDocumentRequest())
                ->setCustID($typeModel->customerId)
                ->setDocScheme($vtbDocument::TYPE)
                ->setDocVersion($typeModel->documentVersion)
                ->setDocData($vtbDocument->toXml($typeModel->documentVersion))
                ->setSignData($signatureInfo->toXml());
            $wsResponse = $service->importSignedDocument($wsRequest);
        } catch (\Exception $exception) {
            $this->log("Document import request has failed, caused by: $exception");
            return new ImportRequestResult(true, true);
        }

        if (empty($wsResponse->getBSErrorCode())) {
            $importRequest->externalRequestId = $wsResponse->getRecordID();
            // Сохранить модель в БД
            $importRequest->save();
        } else {
            $this->log(
                'Got error response for import request'
                . ', error code: ' . $wsResponse->getBSErrorCode()
                . ', error: ' . $wsResponse->getBSError()
            );

            return new ImportRequestResult(true, false);
        }

        return new ImportRequestResult(false, false);
    }
}
