<?php

namespace addons\ISO20022\states\out;

use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\Auth025Type;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\ISO20022Type;
use addons\ISO20022\models\Pain001Type;
use common\components\storage\FileCollectionFile;
use common\components\storage\FileCollectionZip;
use common\document\Document;
use common\helpers\ArchiveFileZip;
use common\helpers\FileHelper;
use common\helpers\Lock;
use common\helpers\StringHelper;
use common\helpers\Uuid;
use common\models\cyberxml\CyberXmlDocument;
use common\models\ImportError;
use common\models\Terminal;
use common\states\BaseDocumentStep;
use Yii;

class ISO20022PrepareStep extends BaseDocumentStep
{
    public $name = 'prepare';
    private $_attachableTypes = [Auth024Type::TYPE, Auth025Type::TYPE, Auth026Type::TYPE];
    private $_zip;

    public function run()
    {
        if (!$this->state->module) {
            $this->state->module = Yii::$app->getModule('ISO20022');
        }

        $filePath = $this->state->filePath;

        $this->log('Registering file ' . $filePath);

        $typeModel = $this->getTypeModel($filePath);

        if (empty($typeModel)) {
            $this->log('Failed to get type model from ' . $filePath);
            // Зарегистрировать событие ошибки документа в модуле мониторинга
            Yii::$app->monitoring->log('ISO20022:InvalidDocument', null, null, [
                'docPath' => $filePath
            ]);

            return false;
        }

        if (!$typeModel->validate()) {
            $this->log("Source document validation failed\n" . print_r($typeModel->errors, true));
            // Зарегистрировать событие ошибки документа в модуле мониторинга
            Yii::$app->monitoring->log('ISO20022:InvalidDocument', null, null, [
                'docPath' => $filePath
            ]);

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_ISO20022,
                'identity'              => $typeModel->msgId,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => [
                    'text' => 'Source document validation failed'
                ],
                'documentType'          => $typeModel->getType(),
                'senderTerminalAddress' => $this->state->sender,
            ]);

            return false;
        }

        if ($typeModel instanceof Pain001Type) {
            ISO20022Helper::addMissingHeadersToPain001($typeModel);
        }

        // Валидация по XSD, если включена настройка
        if ($this->shouldValidateXml()) {
           if (!$typeModel->validateXSD()) {
               $this->log("Source document validation against XSD failed\n" . print_r($typeModel->errors, true));
               // Зарегистрировать событие ошибки XSD валидации в модуле мониторинга
               Yii::$app->monitoring->log('ISO20022:FailedXsdValidation', null, null, [
                    'docPath' => $filePath
               ]);

               // Запись в журнал ошибок импорта
               ImportError::createError([
                   'type'                  => ImportError::TYPE_ISO20022,
                   'identity'              => $typeModel->msgId,
                   'filename'              => FileHelper::mb_basename($filePath),
                   'errorDescriptionData'  => [
                       'text' => 'Source document validation against XSD failed'
                   ],
                   'documentType'          => $typeModel->getType(),
                   'senderTerminalAddress' => $this->state->sender,
               ]);

               return false;
           }
        }

        if (empty($this->state->sender) || empty($this->state->receiver)) {
            $this->state->sender = ISO20022Helper::findSenderTerminalAddress($typeModel);
            $this->state->receiver = ISO20022Helper::findReceiverTerminalAddress($typeModel);
        }

        if (empty($this->state->sender) || empty($this->state->receiver)) {
            $this->log('ISO20022Prepare: cannot find sender/receiver in ' . $this->state->filePath);

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_ISO20022,
                'identity'              => $typeModel->msgId,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => [
                    'text' => 'Cannot find sender/receiver'
                ],
                'documentType'          => $typeModel->getType(),
                'senderTerminalAddress' => $this->state->sender,
            ]);

            return false;
        }

        $typeModel->sender = $this->state->sender;
        $typeModel->receiver = $this->state->receiver;
        $typeModel->originalFilename = $this->state->originalFilename ?: basename($this->state->filePath);

        $this->state->terminalId = Terminal::getIdByAddress($typeModel->sender);

        if (empty($this->state->terminalId)) {
            $this->log('Invalid or unknown sender: ' . $typeModel->sender);

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_ISO20022,
                'identity'              => $typeModel->msgId,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => [
                    'text'   => 'Invalid sender: {sender}',
                    'params' => ['sender' => $typeModel->sender]
                ],
                'documentType'          => $typeModel->getType(),
                'senderTerminalAddress' => $this->state->sender,
            ]);

            return false;
        }

        $cyxDoc = CyberXmlDocument::loadTypeModel($typeModel);
        $cyxDoc->docDate = Yii::$app->formatter->asDatetime(time(), 'php:c');
        $cyxDoc->docId = Uuid::generate();
        $cyxDoc->senderId = $typeModel->sender;
        $cyxDoc->receiverId = $typeModel->receiver;
        $cyxDoc->filename = FileHelper::mb_basename($this->state->filePath);

        $this->state->cyxDoc = $cyxDoc;
        $this->state->typeModel = $typeModel;

        $this->state->lockName = 'MsgDupCheck_' . $typeModel->msgId;
        $this->state->lockValue = 1;

        if (
            /**
             * Если не можем залочиться по msgId, значит такой msgId уже кто-то залочил,
             * значит дубликат заведомо есть
             */
            !Lock::acquire($this->state->lockName, $this->state->lockValue, 5000)
            /**
             * Если удалось залочиться, то проверяем дубликат
             */
            || $this->duplicateExists($typeModel)
        ) {
            // Зарегистрировать событие дубликата документа в модуле мониторинга
            Yii::$app->monitoring->log('ISO20022:DuplicateDocument', null, null, [
                'docPath' => $filePath,
                'msgId' => $typeModel->msgId
            ]);

            $this->log('Duplicate message id: ' . $typeModel->msgId);

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_ISO20022,
                'identity'              => $typeModel->msgId,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => [
                    'text' => 'Document already exists'
                ],
                'documentType'          => $typeModel->getType(),
                'senderTerminalAddress' => $this->state->sender,
            ]);

            return false;
        }

        /**
         * @todo DEBUG DATA, remove after debug
         */
        $this->state->logData['xmlContent'] = $this->state->packString($this->state->xmlContent);
        $this->state->logData['typeModel'] = $this->state->packString((string) $typeModel);
        $this->state->logData['cyxDoc'] = $this->state->packString($cyxDoc->saveXML());

        return true;
    }

    public function cleanup()
    {
        if ($this->_zip) {
            $this->_zip->purge();
        }

        if (is_file($this->state->filePath)) {
            unlink($this->state->filePath);
        }
    }

    public function onFail()
    {
        // Lock освобождается только при fail.
        // В случае успеха он должен освободиться только в ExtModelCreateStep
        if ($this->state->lockValue) {
            Lock::release($this->state->lockName, $this->state->lockValue);
        }

        $filePath = $this->state->filePath;
        if (is_file($filePath)) {
            $errorResource = Yii::$app->registry->getImportResource(
                    $this->state->module->getServiceId(), 'error'
            );
            if (!$errorResource) {
                $this->log('Error resource not configured, file not moved');

                return;
            }

            $errorResource->copyFile($filePath, empty($this->state->receiver) ? null : $this->state->receiver);
        }
    }

    private function duplicateExists($typeModel)
    {
        $doc = Document::find()
        ->innerJoin(
            'documentExtISO20022 isoext',
            'isoext.documentId = document.id'
        )
        ->where([
            'document.direction' => Document::DIRECTION_OUT,
            'document.type' => $typeModel->type,
            'isoext.msgId' => $typeModel->msgId
        ])
        ->one();

        return !empty($doc);
    }

    private function getTypeModel($filePath)
    {
        $xmlContent = false;
        $xmlFilename = '';
        $fileInfo = FileHelper::mb_pathinfo($filePath);
        $attachList = [];

        if (strtolower($fileInfo['extension']) == 'zip') {
            $this->_zip = ArchiveFileZip::openArchive($filePath);
            $files = $this->_zip->getFileList();
            $errorMessage = null;

            foreach ($files as $index => $file) {
                if (mb_substr($file, 0, 7) != 'attach_' && mb_substr($file, -4) == '.xml') {
                    if ($xmlContent === false) {
                        $xmlContent = $this->_zip->getFromIndex($index);
                        $xmlFilename = $file;
                    } else {
                        $errorMessage = 'The archive contains more than one xml file';

                        break;
                    }
                } else {
                    $attachList[$index] = $file;
                }
            }

            $this->_zip->close();

            if ($xmlContent === false) {
                $errorMessage = 'The archive does not contain any xml files';
            }

            if ($errorMessage) {
                $this->logError($errorMessage);

                ImportError::createError([
                    'type'                  => ImportError::TYPE_ISO20022,
                    'filename'              => FileHelper::mb_basename($filePath),
                    'errorDescriptionData'  => [
                        'text' => $errorMessage
                    ],
                    'senderTerminalAddress' => $this->state->sender,
                ]);

                return null;
            }
        } else if ($this->state->xmlContent) {
            $xmlContent = $this->state->xmlContent;
        } else {
            $xmlContent = file_get_contents($filePath);
            $xmlFilename = $filePath;
        }

        // предохраняемся от эксепшена, который бросает xml при неправильной кодировке файла
        $xmlContent = StringHelper::utf8($xmlContent, 'cp1251');

        if (!$this->state->xmlContent) {
            // Если контент не задан на предыдущих шагах, мы имеем дело с родным форматом,
            // который надо прочитать из файла
            $this->state->xmlContent = $xmlContent;
        }

        $typeModel = ISO20022Type::getModelFromString($xmlContent);

        if (is_null($typeModel)) {
            $errorMessage = 'Failed to get ISO20022 document type';

            $this->logError($errorMessage);

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_ISO20022,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => [
                    'text' => $errorMessage
                ],
                'senderTerminalAddress' => $this->state->sender,
            ]);

            return null;
        }

        if (!in_array($typeModel->type, $this->_attachableTypes)) {
            $this->state->filePath = $xmlFilename;
            
            if ($typeModel->type !== Auth026Type::TYPE){
                $fileName = $typeModel->fileName;
            } else {
                $fileName = reset($typeModel->fileNames);
            }

            // Если документ не поддерживает передачу вложений, но при этом имеет вложение
            if ($fileName) {
                $baseErrorMessage = 'Attachments are not allowed for this document type:';
                $logMessage = "{$baseErrorMessage}: {$typeModel->type}";
                $errorMessage = Yii::t('other', "{$baseErrorMessage} {type}", ['type' => $typeModel->type]);

                $this->logError($logMessage);

                // Запись в журнал ошибок импорта
                ImportError::createError([
                    'type'                  => ImportError::TYPE_ISO20022,
                    'identity'              => $typeModel->msgId,
                    'filename'              => FileHelper::mb_basename($filePath),
                    'errorDescriptionData'  => [
                        'text' => $errorMessage
                    ],
                    'documentType'          => $typeModel->getType(),
                    'senderTerminalAddress' => $this->state->sender,
                ]);

                return null;
            }
        }

        // Если у документа есть вложения, то в данной версии они могут попасть только через зип
        if ($this->_zip) {
            // Если зип без вложений, то attachList будет пустой.
            // В этом случае передаем просто пустой список файлов для валидации.
            // При пустом списке валидацию проводить все равно надо, т.к. в модели
            // может быть ошибочно указано количество вложений > 0
            if (!empty($attachList)) {
                $this->_zip->open(); // Переоткрываем архив
                $files = new FileCollectionZip($attachList, $this->_zip);
            } else {
                $files = new FileCollectionFile([]);
            }

            $typeModel->validateAttachment($files);

            $this->_zip->close();

            if ($typeModel->errors) {
                $logMessage = 'Attachments validation failed:';
                foreach($typeModel->originalErrorMessages as $message) {
                    $logMessage .= " {$message};";
                }

                $this->logError(rtrim($logMessage, '; '));

                $errorMessage = [];
                foreach($typeModel->translatedErrorMessages as $message) {
                    $errorMessage[] = $message;
                }

                // Запись в журнал ошибок импорта
                ImportError::createError([
                    'type'                  => ImportError::TYPE_ISO20022,
                    'identity'              => $typeModel->msgId,
                    'filename'              => FileHelper::mb_basename($filePath),
                    'errorDescriptionData'  => [
                        'text' => implode('; ', $errorMessage)
                    ],
                    'documentType'          => $typeModel->getType(),
                    'senderTerminalAddress' => $this->state->sender,
                ]);

                return null;
            }

            // Использовать сжатие в zip
            $typeModel->useZipContent = true;
            $typeModel->zipFilename = basename($filePath);
            $typeModel->zipContent = $this->_zip->asString();
        }

        return $typeModel;
    }

    private function shouldValidateXml(): bool
    {
        return (bool)Yii::$app->settings->get('app')->validateXmlOnImport;
    }
}
