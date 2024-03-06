<?php

namespace common\states\in;

use common\document\Document;
use common\helpers\Uuid;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\states\BaseDocumentStep;
use Yii;

class DocumentRegisterStep extends BaseDocumentStep
{
    public $name = 'register';

    public function run()
    {
        $cyxDoc = CyberXmlDocument::read($this->state->storedFileId);

        if (empty($cyxDoc)) {
            $this->log('Failed to create CyberXmlDocument from stored file ' . $this->state->storedFileId);

            return false;
        }

        $this->state->cyxDoc = $cyxDoc;

        $document = new Document([
            'status' => Document::STATUS_REGISTERED,
            'type' => $cyxDoc->docType,
            'origin' => Document::ORIGIN_WEB,
            'direction' => Document::DIRECTION_IN,
            'uuid' => Uuid::generate(),
            'uuidRemote' => $cyxDoc->docId,
            'sender' => $cyxDoc->senderId,
            'receiver' => $cyxDoc->receiverId,
            'terminalId' => Terminal::getIdByAddress($cyxDoc->receiverId),
            'encryptedStoredFileId' => $this->state->storedFileId,
        ]);

        $this->state->module = Yii::$app->registry->getTypeModule($document->type);

        if ($document->isServiceType()) {
            $document->typeGroup = Document::TYPE_SERVICE_GROUP;
            $document->uuidReference = $cyxDoc->getContent()->getTypeModel()->refDocId;
            $document->actualStoredFileId = $document->encryptedStoredFileId;
        } else {
            $document->typeGroup = $this->state->module
                                    ? $this->state->module->getServiceId()
                                    : Document::TYPE_UNKNOWN;
        }

        if (!$document->save()) {
            $this->log('Cannot save document');

            return false;
        }

        $this->state->document = $document;
        DocumentTransportHelper::ack($cyxDoc, $document->uuidRemote);

        Yii::$app->monitoring->log(
            'document:documentRegistered',
            'document',
            $document->id,
            ['documentType' => $document->typeGroup, 'terminalId' => $document->terminalId]
        );

//        $this->log($document->type . ' ' . $document->id
//                . ' registered from stored file ' . $this->state->storedFileId);

        return true;
    }

    public function onFail()
    {
        $storedFile = Yii::$app->storage->get($this->state->storedFileId);
        $this->log('Failed to register document. Stored file id: ' . $storedFile->id);

        parent::onFail();
    }

}
