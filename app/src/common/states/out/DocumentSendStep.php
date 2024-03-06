<?php
namespace common\states\out;

use common\document\Document;
use common\states\BaseDocumentStep;
use Psr\Log\LogLevel;
use Yii;

class DocumentSendStep extends BaseDocumentStep
{
    const RETRYING_STATE             = Document::STATUS_SENDING_FAIL;
    const PROCESSING_STATE           = Document::STATUS_SENDING;
    const SUCCESSFUL_PROCESSED_STATE = Document::STATUS_SENT;
    const ERRONEOUS_PROCESSED_STATE  = Document::STATUS_NOT_SENT;

    public $name = 'send';

    public function run()
    {
        $module = Yii::$app->getModule('transport');

        $document = $this->state->document;

        $storageId = $document->encryptedStoredFileId
                     ? $document->encryptedStoredFileId
                     : $document->actualStoredFileId;

        $storedFile = Yii::$app->storage->get($storageId);

        // Настраиваем параметры отправки
        $data     = $storedFile->data;
        $sender   = $document->sender;
        $stomp    = $module->stomp;
        $login    = $module->getStompSettings($sender)['login'];
        $password = $module->getStompSettings($sender)['password'];
        $result   = false;

        if (empty($login) || empty($password)) {
            $stomp->addError('Invalid STOMP settings: missing login or password');
        } else {
            // Пытаемся отправить сообщение
            $result = $stomp->send(
                $data,
                $login,
                $password,
                'INPUT',
                [
                    'receipt' => uniqid('Document', true),
                    'doc_id' => $document->uuid,
                    'sender_id' => $document->sender,
                    'doc_type' => $document->type,
                ],
                true
            );
        }

        if (!$result) {
            $params = [
                'logLevel'  => LogLevel::ERROR,
                'errorCode' => 'badConnection',
                'terminalId' => $document->terminalId
            ];

            if ($stomp->hasErrors()) {
                $params['stompErrors'] = implode("\n", $stomp->getErrors());
            }

            // Зарегистрировать событие ошибки STOMP в модуле мониторинга
            Yii::$app->monitoring->log(
                'transport:stompFailed',
                '',
                0,
                $params
            );

            return false;
        }

        $document->updateStatus(Document::STATUS_SENT);

        return true;
    }

    public function onRetry($retryParams = null)
    {
        $this->log('Failed to be sent (attempt ' . $this->state->attempt . ')');
        $document = $this->state->document;

        if ($document) {
            $document->updateStatus(self::RETRYING_STATE);
            $this->state->terminalId = $document->sender;

            return $this->state->save($retryParams['interval']);
        } else {
            $this->log('ERROR: no document');

            return false;
        }
    }

    public function onFail()
    {
        $this->log('Not sent (attempt ' . $this->state->attempt . ')');
        if ($this->state->document) {
            $this->state->document->updateStatus(self::ERRONEOUS_PROCESSED_STATE);
        } else {
            $this->log('ERROR: no document');
        }
    }
}
