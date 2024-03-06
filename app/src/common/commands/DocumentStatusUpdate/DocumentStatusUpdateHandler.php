<?php

namespace common\commands\DocumentStatusUpdate;

use common\commands\BaseHandler;
use common\document\Document;
use common\helpers\DocumentHelper;
use Exception;
use yii\base\ErrorException;

/**
 * Update document status handler class
 */
class DocumentStatusUpdateHandler extends BaseHandler
{
    /**
     * @var Document $_document Document
     */
    private $_document;

    /**
     * @inheritdoc
     */
    public function perform($command)
    {
        if (!($command instanceof DocumentStatusUpdateCommand)){
            throw new ErrorException('Command is not an instance of DocumentStatusUpdateCommand');
        }

        if ($this->findDocument($command->getEntityId()) === false) {
            throw new ErrorException("Empty document for document ID[{$command->getEntityId()}]");
        }

        $result = [
            'previousStatus' => $this->_document->status,
            'status'         => $command->status,
            'info'           => $command->info,
        ];

        if (!DocumentHelper::updateDocumentStatus($this->_document, $command->status)){
            throw new ErrorException('Update document error');
        }

        return $result;
    }

    /**
     * Get document by ID
     *
     * @param integer $documentId Document ID
     * @return boolean
     */
    protected function findDocument($documentId)
    {
        $this->_document = Document::findOne($documentId);

        return !empty($this->_document);
    }

}