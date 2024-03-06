<?php

namespace common\states\out;

use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\Terminal;
use common\states\BaseDocumentStep;
use Exception;
use Yii;

class DocumentCreateStep extends BaseDocumentStep
{
    public $name = 'create';

    public function run()
    {
        $cyxDoc = $this->state->cyxDoc;

        if (!$cyxDoc->validateXSD()) {
            $this->log('Error validating CyberXml document: ' . print_r($cyxDoc->getErrors(), true));
            if (!empty($this->state->logData)) {
                $this->log(var_export($this->state->logData, true));
            }
            $this->log("CyberXML in current state:\n" . $this->state->packString($cyxDoc->saveXML()));

            return false;
        }

        if (!$this->state->module) {
            $this->state->module = Yii::$app->registry->getTypeModule($cyxDoc->docType);
        }

        // Сохраняем cyxDoc в Storage
        $storedFile = $this->state->module->storeDataOut($cyxDoc->saveXML());

        if (empty($storedFile)) {
            throw new Exception('Error saving stored file');
        }

		// Связываем Storage и cyxDoc
		$cyxDoc->setStorageId($storedFile->id);

        $terminalId = Terminal::getIdByAddress($cyxDoc->senderId);
        if (!$terminalId) {
            throw new Exception('No terminal found for sender ' . $cyxDoc->senderId);
        }
        
        if ($this->state->apiUuid) {
            if ($this->state->apiUuid !== $cyxDoc->docId) {
                throw new Exception('Uuid from header and document body do not match ' . $cyxDoc->senderId);                
            }
        }

        $document = new Document([
            'uuid' => $cyxDoc->docId,
            'sender' => $cyxDoc->senderId,
            'receiver' => $cyxDoc->receiverId,
            'status' => Document::STATUS_CREATING,
            'direction' => Document::DIRECTION_OUT,
            'type' => $cyxDoc->docType,
            'typeGroup' => DocumentHelper::getTypeGroup($cyxDoc->docType),
            'origin' => $this->state->origin,
            'terminalId' => $terminalId,
            'actualStoredFileId' => $storedFile->id,
            'uuidReference' => !empty($this->state->refDocId) ? $this->state->refDocId : null
        ]);

        if (!$document->save()) {
            throw new Exception('Could not save document');
        }

        $this->state->document = $document;

        return true;
    }

}