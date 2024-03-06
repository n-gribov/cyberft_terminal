<?php

namespace addons\VTB\jobs\CheckVTBDocumentsStatusJob;

use addons\VTB\jobs\CheckVTBDocumentsStatusJob\StatusUpdateHandler\StatusUpdateHandlerFactory;
use addons\VTB\models\soap\messages\WSGetDocStatus\GetDocStatusRequest;
use addons\VTB\models\soap\services\WSGetDocStatus;
use addons\VTB\models\VTBDocumentImportRequest;
use addons\VTB\models\VTBDocumentStatus;
use addons\VTB\VTBModule;
use common\models\cyberxml\CyberXmlDocument;
use common\models\vtbxml\documents\DocInfo;
use Yii;

class StatusChecker
{
    /** @var VTBDocumentImportRequest */
    private $importRequest;

    /** @var callable */
    private $logCallback;

    /** @var VTBModule */
    private $module;

    public function __construct(VTBDocumentImportRequest $importRequest, $logCallback)
    {
        $this->importRequest = $importRequest;
        $this->logCallback = $logCallback;
        $this->module = Yii::$app->addon->getModule('VTB');
    }

    public function run()
    {
        $importRequest = $this->importRequest;
        $this->log("Processing import request {$importRequest->id}, document: {$importRequest->documentId}");

        $result = $this->sendStatusRequest();
        if ($result->hasError()) {
            if (!$result->canRetry()) {
                $importRequest->updateStatus(VTBDocumentImportRequest::STATUS_PROCESSING_ERROR);
            }
            return;
        }

        $previousStatusCode = $importRequest->externalDocumentStatus ? $importRequest->externalDocumentStatus : '0';
        $previousStatus = new VTBDocumentStatus($previousStatusCode);

        $documentStatus = $result->getDocumentStatus();
        $documentInfoXml = $result->getDocumentInfoXml();
        $this->updateImportRequest($documentStatus, $documentInfoXml);

        $documentInfo = empty($documentInfoXml) ? null : DocInfo::fromXml($documentInfoXml);
        $statusUpdateHandler = StatusUpdateHandlerFactory::create(
            $importRequest->document,
            $documentStatus,
            $previousStatus,
            $documentInfo,
            $this->logCallback
        );
        $statusUpdateHandler->run();
    }

    /**
     * @return StatusRequestResult
     * @throws \Exception
     */
    private function sendStatusRequest()
    {
        $importRequest = $this->importRequest;
        $importRequest->touchStatusCheckDate();

        $cyxDocument = CyberXmlDocument::read($importRequest->document->actualStoredFileId);
        $typeModel = $cyxDocument->getContent()->getTypeModel();
        $vtbDocument = $typeModel->document;

        $wsResponse = null;
        try {
            $service = new WSGetDocStatus();
            $wsRequest = (new GetDocStatusRequest())
                ->setCustID($typeModel->customerId)
                ->setDocScheme($vtbDocument::TYPE)
                ->setRecordID($importRequest->externalRequestId);
            $wsResponse = $service->getDocStatus($wsRequest);
        } catch (\Exception $exception) {
            $this->log("Document status request has failed, caused by: $exception");
            return new StatusRequestResult(true, true);
        }

        if (!empty($wsResponse->getBSErrorCode())) {
            $this->log(
                'Got error response for status request'
                . ', error code: ' . $wsResponse->getBSErrorCode()
                . ', error: ' . $wsResponse->getBSError()
            );

            return new StatusRequestResult(true, false);
        }

        return new StatusRequestResult(
            false,
            false,
            new VTBDocumentStatus($wsResponse->getDocStatus()),
            $wsResponse->getDocInfo()
        );
    }

    private function updateImportRequest(VTBDocumentStatus $documentStatus, $documentInfoXml)
    {
        $importRequest = $this->importRequest;
        if ($documentStatus->isFinal()) {
            $importRequest->status = VTBDocumentImportRequest::STATUS_PROCESSED;
        }
        $importRequest->externalDocumentStatus = $documentStatus->getCode();
        $importRequest->externalDocumentInfo = $documentInfoXml;
        $importRequest->save();
    }

    private function log($message, $isWarning = false)
    {
        $callback = $this->logCallback;
        $callback($message, $isWarning);
    }

}
