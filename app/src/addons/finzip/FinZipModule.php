<?php

namespace addons\finzip;

use addons\finzip\models\FinZipDocumentExt;
use addons\finzip\models\FinZipSearch;
use addons\finzip\models\FinZipType;
use common\base\BaseBlock;
use common\components\storage\StoredFile;
use common\document\Document;
use common\helpers\FileHelper;
use common\models\cyberxml\CyberXmlDocument;
use Yii;
use ZipArchive;

class FinZipModule extends BaseBlock
{
    const EXT_USER_MODEL_CLASS = '\addons\finzip\models\FinZipUserExt';
    const SERVICE_ID	 = 'finzip';
    const RESOURCE_IN = 'in';
    const RESOURCE_IN_ENC = 'inEnc';
    const RESOURCE_OUT = 'out';
    const RESOURCE_OUT_ENC = 'outEnc';
    const SETTINGS_CODE = 'finzip:FinZip';

    public function registerMessage(CyberXmlDocument $cyxDoc, $docId)
    {
	$extModel = FinZipDocumentExt::findOne(['documentId' => $docId]);

        if ($extModel) {
            $cyxContent = $cyxDoc->getContent();

            $typeModel = $cyxContent->getTypeModel();
            Yii::$app->terminals->setCurrentTerminalId($cyxDoc->receiverId);

            $typeModel->subject = Yii::$app->xmlsec->encryptData($typeModel->subject, true);
            $typeModel->descr = Yii::$app->xmlsec->encryptData($typeModel->descr, true);

            //Yii::$app->storage->remove($storedFile->id);
            $storedFile = Yii::$app->storage->putData($cyxContent->rawData, static::SERVICE_ID, static::RESOURCE_IN_ENC);
            $typeModel->zipStoredFileId = $storedFile->id;

            $document = Document::findOne($docId);
            Yii::$app->storage->remove($document->actualStoredFileId);

            $document->actualStoredFileId = $document->encryptedStoredFileId;
            $document->isEncrypted = true;
            $document->save(false);

            $extModel->loadContentModel($typeModel);
            $extModel->attachmentUUID = $cyxDoc->attachmentUUID;

            if (!$extModel->save(false, ['zipStoredFileId', 'fileCount', 'subject', 'descr', 'attachmentUUID'])) {
                Yii::info('Register FINZIP message error: ' . print_r($extModel->errors, true), 'system');

                return false;
            }

            // Если это аттачмент для Auth026/Auth024, то не экспортируем.
            if (empty($extModel->attachmentUUID)) {
                Yii::$app->resque->enqueue('\addons\finzip\jobs\ExportJob', ['documentId' => $docId]);
            }
        } else {
            Yii::info('Register FINZIP document error: Ext document not found', 'system');

            return false;
        }

        return true;
    }

    /**
     * Помещает переданные файлы в зип-архив, сохраняет его в StoredFile и присваивает id этого StoredFile
     * полю $model->zipStoredFileId
     *
     * @param FinZipType $model
     * @param type $fileList
     * @return boolean
     */
    public function saveFinZip(FinZipType $model, $fileList)
    {
        $zipPath = Yii::getAlias('@temp/') . FileHelper::uniqueName() . '.zip';
        $zipArchive = new ZipArchive();

        if ($zipArchive->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        $result = $zipArchive->addFromString(FinZipType::FINZIP_MESSAGE_FILE, $model->getMessage());

        foreach ($fileList as $file) {
            if (!$result) {
                break;
            }

            $result = $result && $zipArchive->addFile(
                $file['path'],
                iconv('UTF-8', 'cp866', $file['fileName'])
            );
        }

        $model->fileCount = $zipArchive->numFiles;

        $result = $result && $zipArchive->close();

        if (!$result) {
            return false;
        }

        Yii::$app->terminals->setCurrentTerminalId($model->sender);
        $storedFile = $this->storeFileOutEnc($zipPath);

        if (empty($storedFile)) {
            return false;
        }

        unlink($zipPath);
        $model->zipStoredFileId = $storedFile->id;

        return $result;
    }

    /**
     * Save file into storage. Using out folder
     *
     * @param string $path Data for save in storage
     * @param string $filename File name
     * @return StoredFile | NULL
     */
    public function storeFileOut($path, $filename = '')
    {
        return $this->storeFile($path, self::RESOURCE_OUT, $filename);
    }

    public function storeFileOutEnc($path, $filename = '')
    {
        return $this->storeFile($path, self::RESOURCE_OUT_ENC, $filename);
    }

    /**
     * Save data into storage. Using out folder
     *
     * @param string $data Data to save
     * @param string $filename File name
     * @return StoredFile | NULL
     */
    public function storeDataOut($data, $filename = '')
    {
        return $this->storeData($data, self::RESOURCE_OUT, $filename);
    }

    public function storeDataOutEnc($data, $filename = '')
    {
        return $this->storeData($data, self::RESOURCE_OUT_ENC, $filename);
    }

    /**
     * Get FinZip document instance
     *
     * @param integer $id Document ID
     * @return Document | NULL
     */
    public function getDocument($id)
    {
        return FinZipSearch::findOne($id);
    }

    public function getName(): string
    {
        return Yii::t('app/menu', 'Free Format');
    }

}
