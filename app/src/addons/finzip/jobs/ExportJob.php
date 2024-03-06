<?php
namespace addons\finzip\jobs;

use common\base\DocumentJob;
use common\document\Document;
use common\helpers\FileHelper;
use common\modules\api\ApiModule;
use common\settings\AppSettings;
use Yii;
use ZipArchive;

class ExportJob extends DocumentJob
{
    public function setUp()
    {
        $this->_logCategory = 'FinZip';
        $this->_module = Yii::$app->getModule('finzip');
        parent::setUp();
    }

	public function perform()
	{
	    $resourceExport = $this->getExportResource();

		if (!$resourceExport) {
			$this->log('Export resource allocation error', true);
			return false;
		}

		$storedFile = Yii::$app->storage->get($this->_document->extModel->zipStoredFileId);
		$zipPath = $storedFile->getRealPath();

        $result = true;

		$zip = new ZipArchive();

        $fileInfo = null;

        if ($zip->open($zipPath)) {
            for ($fileIndex = 0; $fileIndex < $zip->numFiles; $fileIndex++) {
                /**
                 * ZipArchive отдает имя в Unicode. Мы его туда клали в cp866, но зип об этом не знает.
                 * Поэтому он перекодирует имя из cp437 (как он думает) в UTF-8.
                 * Чтобы получить корректное имя для сохранения файла,
                 * мы должны перекодировать обратно из UTF-8 в cp437,
                 * а затем уже правильно перекодировать из cp866 в UTF-8
                 */
                $readFileIconv = iconv('cp866', 'UTF-8', iconv('UTF-8', 'cp437', $zip->getNameIndex($fileIndex)));
                $fileName = FileHelper::mb_pathinfo($readFileIconv)['filename'];

                /**
                 * Get file info
                 */
                $readFile = $zip->getNameIndex($fileIndex);
                /**
                 * php pathinfo() некорректно работает с пробелами в русских именах файлов
                 */
                $fileParts = FileHelper::mb_pathinfo($readFile);
                $writeFile = $fileName . '-' . $this->_document->sender . '-' . $this->_document->uuid;
                if (isset($fileParts['extension'])) {
                    $writeFile .= '.' . $fileParts['extension'];
                }

                $fileInfo = $resourceExport->putStream($zip->getStream($readFile), $writeFile);
                if (!is_file($fileInfo['path'])) {
                    $this->log("Document ID {$this->_documentId}: cannot save file {$writeFile}", true);
                    $result = false;

                    continue;
                }

                $this->log("Saved file from Document ID {$this->_documentId}: " . $fileInfo['path'] . "\n");
            }
        } else {
            $this->log("Document ID {$this->_documentId}: cannot open zip {$zipPath}", true);
			$result = false;
		}

        if (!$result) {
            $this->log("Error exporting document ID {$this->_documentId}", true);
            $this->_document->updateStatus(Document::STATUS_NOT_EXPORTED, 'Export');
        } else {
            if ($fileInfo) {
                ApiModule::addToExportQueueIfRequired($this->_document->uuidRemote, $fileInfo['path'], $this->_document->receiver);
                $this->log("Document ID {$this->_documentId} exported");
            } else {
                $this->log("Document ID {$this->_documentId} not exported (no files in zip)");
            }
            $this->_document->updateStatus(Document::STATUS_EXPORTED, 'Export');
        }

		return $result;
	}

    private function getExportResource()
    {
        return $this->shouldUseGlobalExportSettings()
            ? Yii::$app->registry->getExportResource($this->_module->serviceId)
            : Yii::$app->registry->getTerminalExportResource($this->_module->serviceId, $this->getTerminalAddress());
    }

    private function shouldUseGlobalExportSettings(): bool
    {
        /** @var AppSettings $terminalSettings */
        $terminalSettings = Yii::$app->settings->get('app', $this->getTerminalAddress());
        return (bool)$terminalSettings->useGlobalExportSettings;
    }

    private function getTerminalAddress()
    {
        return $this->_document->receiver;
    }
}
