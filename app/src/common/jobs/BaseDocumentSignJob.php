<?php

namespace common\jobs;

use common\base\Job;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\helpers\DocumentTransportHelper;
use Resque_Job_DontPerform;
use Yii;
use yii\base\Module;

abstract class BaseDocumentSignJob extends Job
{
    /**
     * @var integer $_documentId Document ID
     */
    protected $_documentId;

    /**
     * @var string $_signature Signature
     */
    protected $_signature;
    protected $_certBody;

    /**
     * @var Document $_document Document
     */
    protected $_document;

    /**
     * @var CyberXmlDocument $_cyxDoc CyberXml document
     */
    protected $_cyxDoc;

    /**
     * @var Module $_module Document module
     */
    protected $_module;

    protected $_signatureLevel;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        if (!$this->checkArgs()) {
            throw new Resque_Job_DontPerform('Bad arguments');
        }

        if ($this->_document->isEncrypted) {
            Yii::$app->terminals->setCurrentTerminalId($this->_document->sender);
            $data = Yii::$app->storage->decryptStoredFile($this->_document->actualStoredFileId);
            $cyxDoc = new CyberXmlDocument();
            $cyxDoc->loadXml($data);
        } else {
            $cyxDoc = CyberXmlDocument::read($this->_document->actualStoredFileId);
        }

        $this->_cyxDoc = $cyxDoc;

        if (empty($this->_cyxDoc)) {
            $msg = 'Get CyberXml document error';
            $this->log($msg);

            throw new Resque_Job_DontPerform($msg);
        }

        $this->_module = Yii::$app->registry->getTypeModule($this->_cyxDoc->docType);
        if (empty($this->_module)) {
            $msg = 'Module for docType ' . $this->_cyxDoc->docType . ' not found!';
            $this->log($msg);

            throw new Resque_Job_DontPerform($msg);
        }
    }

    /**
     * @inheritdoc
     */
    public function perform()
    {
        $xml = $this->_cyxDoc->saveXML();

        if ($this->injectSignature()) {

            $xml = $this->_cyxDoc->saveXML();

            if ($this->_document->isEncrypted) {
                Yii::$app->terminals->setCurrentTerminalId($this->_document->sender);
                $storedFile = $this->_module->storeDataOutEnc($xml, '', $this->_document->uuid);
            } else {
                $storedFile = $this->_module->storeDataOut($xml, '', $this->_document->uuid);
            }

            if (empty($storedFile)) {
                $this->log('CyberXmlDocument was signed but could not save storedFile');

                return;
            }

            $this->_document->signaturesCount++;

            // Здесь надо поставить дефолтный STATUS_FORSIGNING,
            // т.к. в джоб он попал в нестабильном статусе STATUS_SIGNING,
            // а это бы нарушило логику isSignable

            $this->_document->status = Document::STATUS_FORSIGNING;

            if (!$this->_document->isSignable()) {
                $this->_document->status = Document::STATUS_SIGNED;
            }

            $this->_document->actualStoredFileId = $storedFile->id;
            $this->_document->save(false, ['status', 'signaturesCount', 'actualStoredFileId']);

            if ($this->_document->status === Document::STATUS_SIGNED) {
                $this->_document->status = Document::STATUS_ACCEPTED;
            }

            // излишество для логирования событий изменения статуса
            $this->_document->updateStatus($this->_document->status);

            DocumentTransportHelper::processDocument($this->_document);
        } else {
            $this->log((static::class) . ' Unable to sign document ' . $this->_documentId);
        }
    }

    /**
     * Check input arguments
     *
     * @return boolean
     */
    private function checkArgs()
    {
        $this->_documentId = (isset($this->args['id'])) ? $this->args['id'] : null;
        if (empty($this->_documentId)) {
            $this->log('Document ID is empty');

            return false;
        }

        $this->_signature = (isset($this->args['signature'])) ? $this->args['signature'] : null;
        if (empty($this->_signature)) {
            $this->log('Signature is empty');

            return false;
        }

        $this->_signature = (isset($this->args['signature'])) ? $this->args['signature'] : null;
        if (empty($this->_signature)) {
            $this->log('Signature is empty');

            return false;
        }

        $this->_certBody = (isset($this->args['certBody'])) ? $this->args['certBody'] : null;
        if (empty($this->_certBody)) {
            $this->log('Cert body is empty');

            return false;
        }

        $this->_document = Document::findOne($this->_documentId);
        if (empty($this->_document)) {
            $this->log('Get document error');

            return false;
        }

        $this->_signatureLevel = $this->args['signatureLevel'] ?? null;

        return true;
    }

    protected abstract function injectSignature();
}