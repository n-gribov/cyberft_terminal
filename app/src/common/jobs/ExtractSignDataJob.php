<?php

namespace common\jobs;

use common\base\Job;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use Exception;
use Resque_Job_DontPerform;
use Yii;

class ExtractSignDataJob extends Job
{
    /**
     * @var integer $_documentId Document ID
     */
    private $_documentId;

    /**
     * @var Document $_document Document
     */
    private $_document;

    /**
     * @var CyberXmlDocument $_cyxDoc CyberXml document
     */
    private $_cyxDoc;

    /**
     * @var Module $_module Document module
     */
    private $_module;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        if (!$this->checkArgs()) {
            throw new Resque_Job_DontPerform('Bad arguments');
        }

        try {
            $this->_module = Yii::$app->registry->getTypeModule($this->_cyxDoc->docType);
            if (empty($this->_module)) {
                $moduleErrorMessage = 'Module for docType ' . $this->_cyxDoc->docType . ' not found!';
                $this->log($moduleErrorMessage);

                throw new Exception($moduleErrorMessage);
            }
        } catch (Exception $ex) {
            throw new Resque_Job_DontPerform($ex->getMessage());
        }
    }

    /**
     * @inheritdoc
     */
    public function perform()
    {
        try {
            $this->_document->setSignData($this->_cyxDoc->extractSignData());

            if ($this->_document->status == Document::STATUS_PENDING) {
                // Если документ попал в задание в статусе PENDING, то значит это был
                // документ, требующий подписания
                $this->_document->status = Document::STATUS_FORSIGNING;
            }
            if ($this->_document->save(false, ['status'])) {
                $this->log($this->_document->type . ' ' . $this->_documentId . ' signature data extracted');
            } else {
                $this->_document->updateStatus(
                    Document::STATUS_CREATING_ERROR,
                    $this->_document->type . ' ' . $this->_documentId . ' failed to extract signature data'
                );
            }
        } catch (Exception $ex) {
            \Yii::info($ex->getMessage());

            throw new Resque_Job_DontPerform($ex->getMessage());
        }
    }

    /**
     * Check input arguments
     *
     * @return boolean
     */
    protected function checkArgs()
    {
        $this->_documentId = (isset($this->args['id'])) ? $this->args['id'] : null;
        if (empty($this->_documentId)) {
            $this->log('Document ID is empty');

            return false;
        }

        $this->_document = Document::findOne($this->_documentId);
        if (empty($this->_document)) {
            $this->log("Document with ID[{$this->_documentId}] was not found");

            return false;
        }

        $this->_cyxDoc = CyberXmlDocument::read($this->_document->actualStoredFileId);
        if (empty($this->_cyxDoc)) {
            $this->log($this->_document->type . ' ' . $this->_documentId . ' failed to create CyberXml from stored file ' . $this->_document->actualStoredFileId);

            return false;
        }

        return true;
    }
}