<?php

namespace addons\VTB\jobs\CheckVTBDocumentsStatusJob\StatusUpdateHandler;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\VTB\models\VTBDocumentStatus;
use addons\VTB\VTBModule;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\models\vtbxml\documents\DocInfo;
use Yii;

abstract class BaseStatusUpdateHandler
{
    /** @var Document */
    protected $document;

    /** @var VTBDocumentStatus */
    protected $status;

    /** @var VTBDocumentStatus */
    protected $previousStatus;

    /** @var DocInfo|null */
    protected $documentInfo;

    /** @var callable */
    protected $logCallback;

    /** @var VTBModule */
    protected $module;

    public function __construct(Document $document, VTBDocumentStatus $status, VTBDocumentStatus $previousStatus, DocInfo $documentInfo = null, $logCallback)
    {
        $this->document = $document;
        $this->status = $status;
        $this->previousStatus = $previousStatus;
        $this->documentInfo = $documentInfo;
        $this->logCallback = $logCallback;
        $this->module = Yii::$app->addon->getModule('VTB');
    }

    public function run()
    {
        $this->sendStatusReport();
        $this->processDocument();
    }

    protected abstract function sendStatusReport();

    protected function processDocument()
    {
    }

    protected function log($message, $isWarning = false)
    {
        $callback = $this->logCallback;
        $callback($message, $isWarning);
    }

    /**
     * @return BaseVTBDocumentType
     */
    protected function getDocumentTypeModel()
    {
        $cyxDocument = CyberXmlDocument::read($this->document->actualStoredFileId);
        return $cyxDocument->getContent()->getTypeModel();
    }
}
