<?php

namespace common\commands\DocumentEdit;

use common\commands\BaseHandler;
use common\document\Document;
use common\helpers\Address;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Yii;
use yii\base\ErrorException;
use yii\base\Module;

class DocumentEditHandler extends BaseHandler
{
    /**
     * @var Document $_document Document
     */
    private $_document;

    /**
     * @var Module $_module Module
     */
    private $_module;

    public function perform($command)
    {
        try {
            if (!($command instanceof DocumentEditCommand)) {
                throw new Exception("Command is not an instance of DocumentEditCommand.");
            }

            if ($this->findDocument($command->getEntityId()) === false) {
                throw new Exception("Empty document for document ID[{$command->getEntityId()}].");
            }

            if ($this->getModule() === false) {
                throw new Exception("Module for document type[{$this->_document->type}] not found.");
            }

            $result = $this->saveDocument($command->typeModel);
            if ($result === false) {
                throw new Exception("Edit (save CyberXml and update model) document ID[{$this->_document->id}] error.");
            }

            return $result;
        } catch (Exception $ex) {
            throw new ErrorException($ex->getMessage());
        }
    }

    /**
     * Save CyberXml document
     *
     * @param string $typeModel serialize type model
     * @return boolean
     */
    protected function saveDocument($typeModel)
    {
        $resultData = [
            'previousStatus' => $this->_document->status,
        ];

        $typeObj = unserialize($typeModel);
        $cyx = CyberXmlDocument::loadTypeModel($typeObj)->saveXML();
        $storedFile = $this->_module->storeDataOut($cyx);

        if (empty($storedFile) ){
            \Yii::warning("Save CyberXml document error.");

            return false;
        }

        $result = $this->updateDocument($storedFile->id, $typeObj);
        if ($result === false) {
            \Yii::warning("Update document ID[{$this->_document->id}] error.");

            return false;
        }

        $resultData['status'] = $this->_document->status;

        return $resultData;
    }

    /**
     * Update document model
     *
     * @param integer $storageId File storage ID
     * @return boolean
     */
    protected function updateDocument($storageId, $model)
    {
        $this->_document->actualStoredFileId = $storageId;
        $this->_document->receiver = $model->recipient;
        $this->_document->receiverParticipantId = Address::truncateAddress($model->recipient);

        if (!$this->_document->save(
                false,
                ['actualStoredFileId', 'receiver', 'receiverParticipantId']
        )) {
            \Yii::warning("Set storage ID[{$storageId}] to document ID[{$this->_document->id}] error!");

            return false;
        }

        $this->_module->processDocument($this->_document);

        if ($this->_document->status == Document::STATUS_ACCEPTED) {
            DocumentTransportHelper::createSendingState($this->_document);
        }

        return true;
    }

    protected function findDocument($documentId)
    {
        $this->_document = Document::findOne($documentId);

        return !empty($this->_document);
    }

    protected function getModule()
    {
        $this->_module = Yii::$app->registry->getTypeModule($this->_document->type);

        return !empty($this->_module);
    }

}