<?php
namespace common\jobs;

use addons\fileact\models\FileActDocumentExt;
use common\base\Job;
use common\document\Document;
use common\helpers\CryptoProHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\helpers\DocumentTransportHelper;
use Resque_Job_DontPerform;
use Yii;

class CryptoProSignJob extends Job
{
    private $_module;
    private $_document;
    private $_documentExt;
    private $_documentId;
    private $_sender;
    private $_receiver;

    public function setUp()
    {
        parent::setUp();

        if (isset($this->args['id'])) {
            $this->_documentId = $this->args['id'];
        } else {
            $this->log('Document id must be set');
            throw new Resque_Job_DontPerform();
        }

        if (isset($this->args['sender'])) {
            $this->_sender = $this->args['sender'];
        }

        if (isset($this->args['receiver'])) {
            $this->_receiver = $this->args['receiver'];
        }

        $document = Document::findOne($this->_documentId);
        if (!$document) {
            $this->log('Error: Document id '. $this->_documentId . ' not found');
        }

        $documentExt = $document->extModel;

        if (!$documentExt || $documentExt->extStatus != 'forCryptoProSigning') {
            $this->log('Error: Extmodel for Document id '. $this->_documentId . ' with status "' . FileActDocumentExt::STATUS_FOR_CRYPTOPRO_SIGNING . '" not found');
            throw new Resque_Job_DontPerform();
        }

        $this->_module = Yii::$app->registry->getTypeModule($document->type);

        if (!$this->_module->isCryptoProSignEnabled($this->_sender)) {
            $this->log('Error: CryptoPro signing is disabled');
            throw new Resque_Job_DontPerform();
        }

        $this->_document = $document;
        $this->_documentExt = $documentExt;
    }

	public function perform()
	{
        $model = CyberXmlDocument::read($this->_document->actualStoredFileId);
        $signedModel = CryptoProHelper::sign($this->_module->getServiceId(), $model, true);

        if ($signedModel !== false) {
            $this->_documentExt->extStatus = FileActDocumentExt::STATUS_CRYPTOPRO_SIGNED;
            $this->log($this->_document->type . ' ' . $this->_documentId . ' signed with cryptopro keys');
            $storedFile = Yii::$app->storage->get($this->_document->actualStoredFileId);
            $storedFile->updateData($model->saveXML());
            $this->_module->processDocument($this->_document);
            DocumentTransportHelper::processDocument($this->_document, true);
        } else {
            $this->_documentExt->extStatus = FileActDocumentExt::STATUS_CRYPTOPRO_SIGNING_ERROR;
            $this->log($this->_document->type . ' ' . $this->_documentId . ' failed to sign with cryptopro keys');

            $this->_document->updateStatus(Document::STATUS_PROCESSING_ERROR);

            Yii::$app->monitoring->log('document:CryptoProSigningError', 'document', $this->_document->id, [
                'terminalId' => $this->_document->terminalId
            ]);
        }

        $this->_documentExt->save();
	}

}