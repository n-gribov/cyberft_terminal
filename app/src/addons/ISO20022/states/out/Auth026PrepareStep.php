<?php

namespace addons\ISO20022\states\out;

use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\helpers\RosbankHelper;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\helpers\FileHelper;
use common\helpers\Uuid;
use common\models\cyberxml\CyberXmlDocument;
use common\models\ImportError;
use common\models\Terminal;
use common\states\BaseDocumentStep;
use Yii;

class Auth026PrepareStep extends BaseDocumentStep
{
    public $name = 'prepare';
    private $_errorData = [];

    public function run()
    {
        $this->state->module = Yii::$app->getModule('ISO20022');
        $filePath = $this->state->filePath;
        $this->log('Registering file ' . $filePath);

// Тип документа, отправитель, получатель, тема, описание, код типа документа и идентификатор сообщения (опционально)
// определяются из имени файла по следующему принципу:
// тип документа_код отправителя_код получателя_тема_описание_код[_id сообщения].расширение
// Пример: auth.026_EVRZRUMMAXXX_IMBKRUMMAXXX_СВО-25112017_СВО_CCTC.pdf
//         auth.026_EVRZRUMMAXXX_IMBKRUMMAXXX_СВО-25112017_СВО_CCTC_1256564de4c311e7805ad89d671910af1.pdf

        $fileParts = FileHelper::mb_pathinfo($filePath);

        $basename = FileHelper::mb_basename($filePath);

        $list = explode('_', $fileParts['filename']);
        $cnt = count($list);
        if ($cnt < 6 || $cnt > 7) {
            $this->logError('Invalid filename format: ' . $basename);
            $this->_errorData[] = ['text' => Yii::t('app/iso20022', 'Invalid filename format') . ': ' . $basename];

            return false;
        }

        $type = $list[0];
        $sender = $list[1];
        $receiver = $list[2];
        $subject = $list[3];
        $descr = $list[4];
        $code = $list[5];
        $messageId = $cnt > 6 ? $list[6] : null;

        if ($type != Auth026Type::TYPE) {
            $this->logError('Type is not supported: ' . $type);
            $this->_errorData[] = ['text' => Yii::t('app/iso20022', 'Type is not supported') . ': ' . $type];

            return false;
        }

        if (empty($sender) || empty($receiver)) {
            $this->log('Auth026Prepare: cannot find sender/receiver in ' . $filePath);
            $this->_errorData[] = ['text' => Yii::t('app/iso20022', 'Cannot find sender/receiver')];

            return false;
        }

        $this->state->terminalId = Terminal::getIdByAddress($sender);

        // Не останавливаемся после первой ошибки, собираем все

        if (strlen($sender) != 12 || empty($this->state->terminalId)) {
            $this->log('Invalid sender: ' . $sender);
            $this->_errorData[] = ['text' => Yii::t('app/iso20022', 'Invalid sender') . ': ' . $sender];
        }

        if (strlen($receiver) != 12) { //  || !Cert::findByRole($receiver, Cert::ROLE_SIGNER_BOT)
            $this->log('Invalid receiver: ' . $receiver);
            $this->_errorData[] = ['text' => Yii::t('app/iso20022', 'Invalid receiver') . ': ' . $receiver];
        }

        if (!$subject) {
            $this->log('Missing subject');
            $this->_errorData[] = ['text' => Yii::t('app/iso20022', 'Missing subject')];
        }

        if (!$descr) {
            $this->log('Missing description');
            $this->_errorData[] = ['text' => Yii::t('app/iso20022', 'Missing description')];
        }

        if (!$code) {
            $this->log('Missing code');
            $this->_errorData[] = ['text' => Yii::t('app/iso20022', 'Missing code')];
        }

        if ($messageId !== null) {
            if (strlen($messageId) < 1 || strlen($messageId) > 35) {
                $this->log('Invalid message id');
                $this->_errorData[] = ['text' => Yii::t('app/iso20022', 'Invalid message id')];
            } else {
                $documentWithSameMessageIdExists = ISO20022DocumentExt::find()
                    ->where(['msgId' => $messageId])
                    ->exists();
                if ($documentWithSameMessageIdExists) {
                    $this->log("Document with message id {$messageId} already exists");

                    $errorMessage = Yii::t(
                        'app/iso20022',
                        'Document with message id {messageId} already exists',
                        ['messageId' => $messageId]
                    );
                    $this->_errorData[] = ['text' => $errorMessage];
                }
            }
        }

        $settings = $this->state->module->settings;
        if (!array_key_exists($code, $settings->typeCodes)) {
            $this->log('Invalid code: ' . $code);
            $this->_errorData[] = ['text' => Yii::t('app/iso20022', 'Invalid code') . ': ' . $code];
        }

        if (!empty($this->_errorData)) {
            return false;
        }

        $this->state->sender = $sender;
        $this->state->receiver = $receiver;

        $auth026Model = new Auth026Type([
            'typeCode' => $code,
            'dateCreated' => time(),
            'sender' => $sender,
            'receiver' => $receiver,
            'subject' => $subject,
            'descr' => $descr,
            'numberOfItems' => 1,
            'msgId' => $messageId,
            'useZipContent' => true,
        ]);

        $fileName = $fileParts['filename'];

        $fileNameEnding = '';

        if (Yii::$app->settings->get('ISO20022:ISO20022')->useUniqueAttachmentName) {
            $fileNameEnding = '_' . $sender . date('YmdHis');
        }

        if (isset($fileParts['extension'])) {
            $fileNameEnding .= '.' . $fileParts['extension'];
        }

        if (RosbankHelper::isGatewayTerminal($receiver)) {
            $auth026Model->addEmbeddedAttachment($fileName, $filePath);
        } else {
            $attachFileName = "attach_{$subject}_{$descr}$fileNameEnding";
            ISO20022Helper::attachZipContent($auth026Model, $attachFileName, $filePath);
        }

        if ($this->shouldValidateXml()) {
           if (!$auth026Model->validateXSD()) {
               $this->log("Source document validation against XSD failed\n" . print_r($auth026Model->errors, true));
               Yii::$app->monitoring->log('ISO20022:FailedXsdValidation', null, null, ['docPath' => $filePath]);
               $this->_errorData[] = ['text' => 'Source document validation against XSD failed'];

               return false;
           }
        }

        $cyxDoc = CyberXmlDocument::loadTypeModel($auth026Model);
        $cyxDoc->docDate = Yii::$app->formatter->asDatetime(time(), 'php:c');
        $cyxDoc->docId = Uuid::generate();
        $cyxDoc->senderId = $this->state->sender;
        $cyxDoc->receiverId = $this->state->receiver;
        $cyxDoc->filename = $basename;

        $this->state->cyxDoc = $cyxDoc;
        $this->state->typeModel = $auth026Model;

        return true;
    }

    public function cleanup()
    {
        if (is_file($this->state->filePath)) {
            unlink($this->state->filePath);
        }
    }

    public function onFail()
    {
        $filePath = $this->state->filePath;

        if (is_file($filePath)) {
            $errorResource = Yii::$app->registry->getImportResource(
                    $this->state->module->getServiceId(), 'error'
            );

            if (!$errorResource) {
                $this->log('Error resource not configured, file not moved');

                return;
            }
            // В данном случае файл не копируется, а переносится, т.к. cleanup его не удаляет
            $errorResource->moveFile($filePath);
        }

        $terminal = $this->state->terminalId
            ? Terminal::findOne($this->state->terminalId)
            : null;

        foreach($this->_errorData as $error) {
            ImportError::createError([
                'type'                  => ImportError::TYPE_ISO20022,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => $error,
                'documentType'          => Auth026Type::TYPE,
                'senderTerminalAddress' => $terminal ? $terminal->terminalId : null,
            ]);
        }
        
    }

    private function shouldValidateXml(): bool
    {
        return (bool)Yii::$app->settings->get('app')->validateXmlOnImport;
    }
}