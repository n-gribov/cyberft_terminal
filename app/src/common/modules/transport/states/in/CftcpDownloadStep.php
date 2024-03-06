<?php
namespace common\modules\transport\states\in;

use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\modules\transport\TransportModule;
use common\states\BaseDocumentStep;
use Yii;

/**
 * Download file from server via CftcpTransport module job class
 */
class CftcpDownloadStep extends BaseDocumentStep
{
    const INITIAL_INCOMING_STATE = Document::STATUS_FORDOWNLOADING;
    const RETRYING_STATE = Document::STATUS_DOWNLOAD_FAIL;
    const PROCESSING_STATE = Document::STATUS_DOWNLOADING;
    const SUCCESSFUL_PROCESSED_STATE = Document::STATUS_DOWNLOADED;
    const ERRONEOUS_PROCESSED_STATE = Document::STATUS_NOT_DOWNLOADED;

    public $name = 'download';

    public function run()
    {
        $document = $this->state->document;
        $storedFile = Yii::$app->storage->get($document->encryptedStoredFileId);

        $stompSettings = TransportModule::getInstance()->getStompSettings($document->receiver);
        $login         = $stompSettings['login'];
        $password      = $stompSettings['password'];
        $uuid          = $document->fileId;
        $file          = $storedFile->getRealPath();
        $resume        = ($document->status === self::RETRYING_STATE);

        /**
         * @todo
         *
         * Полученный из сторед файла путь может отсутствовать перед receive ввиду того, что файл параллельным процессом
         * будет перенесен в тар. В целях безопасности нужно делать локальный прием файла в темп-каталоге и затем делать
         * storedFile->update()
         *
         */
        $myCftcp = Yii::$app->getModule('transport')->cftcp;
        $myCftcp->receive($login, $password, $uuid, $file, $resume);

        if ($myCftcp->hasErrors()) {
            $this->log('failed to download');

            return false;
        }

        $document->updateStatus(self::SUCCESSFUL_PROCESSED_STATE);

        $cyxDoc = CyberXmlDocument::read($document->encryptedStoredFileId);
        $this->state->cyxDoc = $cyxDoc;

        $this->state->module = Yii::$app->registry->getTypeModule($cyxDoc->docType);

        $document->type = $cyxDoc->docType;
        $document->sender = $cyxDoc->senderId;
        $document->typeGroup = $this->state->module->getServiceId();

        $document->save(false, ['type', 'sender', 'typeGroup']);

        DocumentTransportHelper::ack($cyxDoc, $document->uuidRemote);

        return true;
    }

    public function onRetry($retryParams = null)
    {
        $this->log('Failed to download (attempt ' . $this->state->attempt . ')');
        $document = $this->state->document;
        $this->state->terminalId = $document->receiver;

        $document->updatestatus(Document::STATUS_DOWNLOAD_FAIL);

        return $this->state->save();
    }

    public function onFail()
    {
        $this->log('Not downloaded (attempt ' . $this->state->attempt . ')');
        $this->state->document->updateStatus(Document::STATUS_NOT_DOWNLOADED);        
    }

}