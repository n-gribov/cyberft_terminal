<?php

namespace addons\swiftfin\states\out;

use addons\swiftfin\models\SwiftFinType;
use common\document\Document;
use common\helpers\Uuid;
use common\models\cyberxml\CyberXmlDocument;
use common\states\BaseDocumentStep;
use common\models\ImportError;
use common\helpers\FileHelper;
use Exception;

class SwiftPrepareStep extends BaseDocumentStep
{
    public $name = 'prepare';

    public function run()
    {
        $filePath = $this->state->filePath;

        $this->log('Registering file ' . $filePath);

        $typeModel = false;

        if ($this->state->origin == Document::ORIGIN_XMLFILE) {
            $cyxDoc = new CyberXmlDocument();
            if ($cyxDoc->loadXml(file_get_contents($filePath))) {
                $docId = $cyxDoc->docId;
                // Контроль повторной отправки
                $document = Document::findOne(['uuid' => $docId, 'direction' => Document::DIRECTION_OUT]);
                if ($document) {

                    // Запись в журнал ошибок импорта
                    ImportError::createError([
                        'type'                  => ImportError::TYPE_SWIFTFIN,
                        'filename'              => FileHelper::mb_basename($filePath),
                        'identity'              => $docId,
                        'errorDescriptionData'  => [
                            'text' => 'Document already exists'
                        ],
                        'documentType'          => $cyxDoc->docType,
                        'senderTerminalAddress' => $cyxDoc->senderId,
                    ]);

                    throw new Exception('Duplicate uuid: ' . $docId);
                }

                $typeModel = $cyxDoc->getContent()->getTypeModel();
            }
        } else {
            $typeModel = SwiftFinType::createFromFile($filePath);
            $cyxDoc = CyberXmlDocument::loadTypeModel($typeModel);
            $cyxDoc->docDate = date('c');
            $cyxDoc->docId = Uuid::generate();
        }

        if ($typeModel === false) {

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_SWIFTFIN,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => [
                    'text' => 'Failed to get document type'
                ],
                'documentType'          => $cyxDoc->docType,
                'senderTerminalAddress' => $cyxDoc->senderId,
            ]);

            throw new Exception('Failed to get typemodel');
        }

        if (!$typeModel->validateSender(true)) {

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_SWIFTFIN,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => [
                    'text'   => 'Invalid sender: {sender}',
                    'params' => [
                        'sender' => $typeModel->sender
                    ]
                ],
                'documentType'          => $typeModel->getType(),
                'documentNumber'        => $typeModel->operationReference,
                'documentCurrency'      => $typeModel->currency,
                'senderTerminalAddress' => $typeModel->sender,
            ]);

            throw new Exception('Incorrect sender');
        }

        if (!$typeModel->validateRecipient()) {

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_SWIFTFIN,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => [
                    'text'   => 'Invalid recipient: {recipient}',
                    'params' => [
                        'recipient' => $typeModel->recipient
                    ]
                ],
                'documentType'          => $typeModel->getType(),
                'documentNumber'        => $typeModel->operationReference,
                'documentCurrency'      => $typeModel->currency,
                'senderTerminalAddress' => $typeModel->sender,
            ]);

            throw new Exception('Incorrect recipient');
        }

        if (!$cyxDoc->validateXSD()) {

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_SWIFTFIN,
                'filename'              => FileHelper::mb_basename($filePath),
                'identity'              => $cyxDoc->docId,
                'errorDescriptionData'  => [
                    'text' => 'Source document validation against XSD failed'
                ],
                'documentType'          => $typeModel->getType(),
                'documentNumber'        => $typeModel->operationReference,
                'documentCurrency'      => $typeModel->currency,
                'senderTerminalAddress' => $typeModel->sender,
            ]);

            throw new Exception(
               'Error validating CyberXml document: ' . print_r($cyxDoc->getErrors(), true)
            );
        }

        $this->state->cyxDoc = $cyxDoc;
        $this->state->typeModel = $typeModel;

        return true;
    }

    public function cleanup()
    {
        if (is_file($this->state->filePath)) {
            unlink($this->state->filePath);
        }
    }

}