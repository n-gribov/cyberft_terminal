<?php

namespace addons\edm\states\out;

use addons\edm\EdmModule;
use common\helpers\FileHelper;
use common\helpers\Uuid;
use common\models\ImportError;
use common\states\BaseDocumentStep;
use Yii;

/** @property EdmOutState $state */
class ProcessFileStep extends BaseDocumentStep
{
    public $name = 'processFile';

    public function run()
    {
        $filePath = $this->state->filePath;
        $this->log("Processing file $filePath");

        $isZipFile = preg_match('/\.zip$/i', $filePath);
        if ($isZipFile) {
            $success = $this->processZipFile();
            if (!$success) {
                $this->moveFileToErrors();
            }
            $this->state->isImportingZipArchive = true;

            return $success;
        }

        $this->state->documentFilePath = $this->state->filePath;

        return true;
    }

    private function processZipFile()
    {
        $zipArchive = new \ZipArchive();
        if ($zipArchive->open($this->state->filePath) !== true) {
            $this->processImportError('Failed to open zip archive');

            return false;
        }

        $filesNames = $this->getFilenamesFromZipArchive($zipArchive);

        if ($this->getDocumentCount($filesNames) !== 1) {
            $this->processImportError('Zip archive has invalid content, it must contain exactly one XML document');
            $zipArchive->close();

            return false;
        }

        $tempDirPath = Yii::$app->registry->getTempResource(EdmModule::SERVICE_ID)->createDir(Uuid::generate());
        foreach ($filesNames as $fileName) {
            $this->processFileFromZipArchive($zipArchive, $fileName, $tempDirPath);
        }
        $zipArchive->close();

        return true;
    }

    private function getDocumentCount($filenames)
    {
        $count = 0;
        foreach($filenames as $filename) {
            if (mb_substr($filename, 0, 7) != 'attach_' && mb_substr($filename, -4) == '.xml') {
                $count++;
            }
        }

        return $count;
    }

    private function getFilenamesFromZipArchive(\ZipArchive $zipArchive)
    {
        $out = [];
        for ($i = 0; $i < $zipArchive->numFiles; $i++) {
            $out[] = $zipArchive->getNameIndex($i);
        }

        return $out;
    }

    private function processFileFromZipArchive(\ZipArchive $zipArchive, $fileName, $tempDirPath)
    {
        $stream = $zipArchive->getStream($fileName);
        try {
            $targetFileName = iconv('cp866', 'UTF-8', iconv('UTF-8', 'cp437', $fileName));
        } catch (\Exception $ex) {
            $targetFileName = $fileName;
        }

        $isAttachment = strpos($fileName, 'attach_') === 0;
        if ($isAttachment) {
            $targetFileName = preg_replace('/^attach_/', '', $targetFileName);
        }

        $targetFilePath = "$tempDirPath/$targetFileName";
        file_put_contents($targetFilePath, $stream);
        if ($isAttachment) {
            $this->state->attachmentsPaths[] = $targetFilePath;
        } else {
            $this->state->documentFilePath = $targetFilePath;
        }
    }

    private function moveFileToErrors()
    {
        $errorResource = Yii::$app->registry->getImportResource(EdmModule::SERVICE_ID, 'error');
        if (empty($errorResource)) {
            $this->logError('Import error resource is not configured, invalid file will not be moved.');
        } else {
            $errorResource->putFile($this->state->filePath, basename($this->state->filePath));
        }
        unlink($this->state->filePath);
    }

    private function processImportError($message) {
        $this->logError($message);
        ImportError::createError([
            'type'                 => ImportError::TYPE_EDM,
            'filename'             => FileHelper::mb_basename($this->state->filePath),
            'errorDescriptionData' => ['text' => Yii::t('edm', $message)],
            'identity'             => $this->state->apiUuid,
        ]);
        $this->state->addApiImportError($message);
    }
}
