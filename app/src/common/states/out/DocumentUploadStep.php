<?php
namespace common\states\out;

use common\document\Document;
use common\states\BaseDocumentStep;
use Psr\Log\LogLevel;
use Yii;

class DocumentUploadStep extends BaseDocumentStep
{
    public $name = 'upload';

    public function run()
    {
        $document = $this->state->document;

        $module = Yii::$app->getModule('transport');
        $stompSettings = $module->getStompSettings($document->sender);
		$login		 = $stompSettings['login'];
		$password	 = $stompSettings['password'];

        /**
         * Возможно, документ был загружен ранее, но не удался этап "Send Request"
         * в случае, если это повторная попытка, не грузим документ второй раз.
         */
        if ($document->status != Document::STATUS_UPLOADED) {
            $storedFile = Yii::$app->storage->get($document->encryptedStoredFileId);
            $cftcp = $module->cftcp;

            $cftcp->send(
                $login,
                $password,
                $document->uuid,
                $storedFile->getRealPath(),
                $document->status === Document::STATUS_UPLOAD_FAIL
            );

            if ($cftcp->hasErrors()) {
                // Зарегистрировать событие ошибки CFTCP в модуле мониторинга
                Yii::$app->monitoring->log('transport:cftcpFailed', '', 0, ['logLevel'  => LogLevel::ERROR, 'terminalId' => $document->terminalId]);

                return false;
            }

            $document->updateStatus(Document::STATUS_UPLOADED);
        }

		// Send request
        $stomp = $module->stomp;
        $stomp->send(
            '', $login, $password, 'INPUT',
            [
                'receipt' => uniqid('Document', true),
                'doc_id' => $document->uuid,
                'sender_id' => $document->sender,
                'doc_type' => $document->type,
                'file_id' => $document->uuid,
            ], true
        );

        if ($stomp->hasErrors()) {
            // Зарегистрировать событие ошибки STOMP в модуле мониторинга
            Yii::$app->monitoring->log('transport:stompFailed', '', 0, ['logLevel'  => LogLevel::ERROR, 'terminalId' => $document->terminalId]);

            return false;
        }

        $document->updateStatus(Document::STATUS_SENT);

        return true;
    }
 
    public function onRetry($retryParams = null)
    {
        $this->log('Failed to upload (attempt ' . $this->state->attempt . ')');
        $document = $this->state->document;
        $this->state->terminalId = $document->sender;

        if ($this->state->document->status == Document::STATUS_UPLOADED) {
            $this->state->document->updateStatus(Document::STATUS_REQUEST_FAIL);
        } else {
            $this->state->document->updateStatus(Document::STATUS_UPLOAD_FAIL);
        }

        // Сохранить модель в БД и вернуть результат сохранения
        return $this->state->save();
    }

    public function onFail()
    {
        $this->log('Not uploaded (attempt ' . $this->state->attempt . ')');
        if ($this->state->document->status == Document::STATUS_UPLOADED) {
            $this->state->document->updateStatus(Document::STATUS_NOT_SENT);
        } else {
            $this->state->document->updateStatus(Document::STATUS_NOT_UPLOADED);
        }
    }

}