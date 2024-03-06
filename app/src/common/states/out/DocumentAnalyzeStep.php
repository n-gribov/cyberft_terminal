<?php
namespace common\states\out;

use common\document\Document;
use common\states\BaseDocumentStep;
use Yii;

class DocumentAnalyzeStep extends BaseDocumentStep
{
    public $maxFileSize = 1048576;

    const STATE_STOMP = Document::STATUS_FORSENDING;
    const STATE_CFTCP = Document::STATUS_FORUPLOADING;
	const PROCESSING_STATE = Document::STATUS_ANALYZING;

    public $name = 'analyze';

    public function run()
    {
        $decidedState = self::STATE_STOMP;

        $storageId = $this->state->document->encryptedStoredFileId
                        ?: $this->state->document->actualStoredFileId;

        $storedFile = Yii::$app->storage->get($storageId);

        $info = null;

		if (!Yii::$app->settings->get('app')->processing['safeMode']) {
            if ($storedFile->size > $this->maxFileSize) {
				$decidedState = self::STATE_CFTCP;
			}
		} else {
			if ($storedFile->size > Yii::$app->settings->get('app')->processing['safeModeMaxFileSize']) {
				$decidedState = Document::STATUS_NOT_SENT;
				$info = 'Размер сообщения (' . round($storedFile->size / 1024, 2) . ' Кб) превышает допустимый в безопасном режиме ('.round(Yii::$app->settings->get('app')->processing['safeModeMaxFileSize']/1024, 2).' Кб)';
			}
		}

        $this->state->document->updateStatus($decidedState, $info);

        return true;
    }

}