<?php

namespace common\modules\transport\jobs;

use common\base\Job;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\api\ApiModule;
use Resque_Job_DontPerform;
use Yii;

class ExportJob extends Job
{
    const RESOURCE_ID = 'export';

    private $_documentId;
    private $_document;
    private $_cyxDocument;

    public function setUp()
    {
        parent::setUp();

        if (!$this->checkArgs()) {
            throw new Resque_Job_DontPerform('Invalid job params');
        }
    }

    public function perform()
    {
        $fileName = $this->_cyxDocument->docType . '_' . $this->_cyxDocument->docId . '.xml';

        $path = Yii::getAlias('@docExport/');

        $appSettings = Yii::$app->settings->get('app');

        if (isset($appSettings->exportXmlPath)) {
            $path .= $appSettings->exportXmlPath;
        }

        if (!is_dir($path)) {
            mkdir($path);
        }

        $path .= "/" . $fileName;

        if (file_put_contents($path, $this->_cyxDocument->saveXML()) === false) {
            $this->log("Error exporting document ID {$this->_documentId}", true);
            $this->_document->updateStatus(Document::STATUS_NOT_EXPORTED, 'Export');
        } else {
            $this->log("Document ID {$this->_documentId} exported to {$path}");
            $this->_document->updateStatus(Document::STATUS_EXPORTED, 'Export');
            
            ApiModule::addToExportQueueIfRequired($this->_document->uuidRemote, $path, $this->_document->receiver);
        }
    }

    private function checkArgs()
    {
        $this->_documentId = (isset($this->args['id'])) ? $this->args['id'] : null;
        if (is_null($this->_documentId)) {
            $this->log("Document ID must be set");

            return false;
        }

        $this->_document = Document::findOne($this->_documentId);
        if (empty($this->_document)) {
            $this->log("Document ID {$this->_documentId} not found!", true);

            return false;
        }

        $this->_cyxDocument = CyberXmlDocument::read($this->_document->actualStoredFileId);
        if (empty($this->_cyxDocument)) {
            $this->log("CyberXml document with storage ID {$this->_document->actualStoredFileId} cannot be found for document ID {$this->_documentId}", true);

            return false;
        }

        return true;
    }
}