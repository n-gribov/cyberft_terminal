<?php

namespace addons\fileact;

use addons\fileact\models\FileAct;
use addons\fileact\models\FileActDocumentExt;
use addons\fileact\models\FileActSearch;
use addons\fileact\models\FileActType;
use common\base\BaseBlock;
use common\components\storage\StoredFile;
use common\document\Document;
use common\helpers\CryptoProHelper;
use common\helpers\FileHelper;
use common\helpers\Uuid;
use common\models\CryptoproKey;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use common\modules\certManager\models\Cert;
use Psr\Log\LogLevel;
use Yii;
use ZipArchive;

class FileActModule extends BaseBlock
{
    const SERVICE_ID           = 'fileact';
    const EXT_USER_MODEL_CLASS = '\addons\fileact\models\FileActUserExt';
    const SETTINGS_CODE = 'fileact:FileAct';

    /**
     * Регистрирует сообщение в локальной таблице. Экстрактит из него три файла: zip, pdu, bin
     * @param CyberXmlDocument $doc        CyberXml document
     * @param integer          $documentId Document ID
     * @return boolean
     */
    public function registerMessage(CyberXmlDocument $doc, $documentId)
    {
        $extModel = FileActDocumentExt::findOne(['documentId' => $documentId]);

        if (!$extModel) {
            $this->log('FileAct extmodel not found');

            return false;
        }
        $storedZip = Yii::$app->storage->putData($doc->content->rawData, static::SERVICE_ID, $this->_config->resourceIn);
        $zipPath   = $storedZip->getRealPath();

        $zip = new ZipArchive();

        if (!$zip->open($zipPath)) {
            $this->log('cannot open zip: ' . $zipPath);

            return false;
        }

        $storedPdu = false;
        $storedBin = false;

        for ($fileIndex = 0; $fileIndex < $zip->numFiles; $fileIndex++) {
            $readFile  = $zip->getNameIndex($fileIndex);
            $writeFile = FileHelper::mb_basename($readFile);

            $storedFile = Yii::$app->storage->putStream(
                $zip->getStream($readFile), static::SERVICE_ID,
                $this->_config->resourceOut, $writeFile
            );

            if ($doc->content->binFileName == $writeFile) {
                $storedBin = $storedFile;
            } else {
                $storedPdu = $storedFile;
            }

            //$this->log("saved: " . $storedFile->getRealPath() . "\n");
        }

        if ($storedZip !== false) {
            $extModel->zipStoredFileId = $storedZip->id;
        }

        if ($storedPdu !== false) {
            $extModel->pduStoredFileId = $storedPdu->id;
            $typeModel = new FileActType();
            $typeModel->loadHeader($storedPdu->getRealPath());
            $extModel->binFileName = $typeModel->binFileName;
            $extModel->senderReference = $typeModel->senderReference;
        }

        if ($storedBin !== false) {
            $extModel->binStoredFileId = $storedBin->id;
        }

        if (!$extModel->save(
                false,
                ['zipStoredFileId', 'pduStoredFileId', 'binStoredFileId', 'binFileName', 'senderReference']
            )
        ) {
            Yii::info('Register FileAct document error! ' . print_r($extModel->errors, true), 'system');

            return false;
        }

        if ($this->verifyCryptoPro($doc, $extModel)) {
            Yii::$app->resque->enqueue('\addons\fileact\jobs\ExportJob', ['documentId' => $documentId]);
        } else {
            $this->log('Document id ' . $documentId . ' failed CryptoPro verification');
            $previousStatus = $extModel->extStatus;

            $extModel->extStatus = FileActDocumentExt::STATUS_CRYPTOPRO_VERIFICATION_FAILED;
            $extModel->save(false, ['extStatus']);

            $params = [
                'logLevel' => LogLevel::ERROR,
                'previousStatus' => $previousStatus,
                'status' => $extModel->extStatus
            ];

            $document = Document::findOne($documentId);

            if ($document) {
                $params['terminalId'] = $document->terminalId;
            }

            Yii::$app->monitoring->log(
                // Зарегистрировать событие ошибки обработки документа в модуле мониторинга
               'document:documentProcessError', 'document', $documentId,
                [
                    'logLevel' => LogLevel::ERROR,
                    'previousStatus' => $previousStatus,
                    'status' => $extModel->extStatus
                ]
            );

            return false;
        }

        return true;
    }

    /**
     * Формирует и cохраняет модель в свою таблицу,
     * регистрируя в хранилище три файла: pdu, bin и zip
     * @param string $pduFilePath путь к файлу PDU, который служит основой модели
     * @param string $binPath путь для поиска файла Bin или сам файл Bin
     * @return FileActType|boolean
     */
    public function saveFileAct($pduFilePath, $binPath)
    {
        $model = new FileActType();

        if (!$model->loadHeader($pduFilePath)) {
            return false;
        }

        $senderReference = $model->senderReference;
        $extModelCount = FileActDocumentExt::find()->where(['senderReference' => $senderReference])->count();

        if ($extModelCount) {
            $this->log('FileAct duplicate SenderReference: ' . $senderReference);
            $model->addError('senderReference', 'Document already exists');

            return $model;
        }

        // Получаем корректных отправителя/получателя при сохранении документа
        $sender = $model->pduAttributes['sender'];
        $model->sender = Terminal::getParticipantAddress($sender);
        if (!$model->sender) {
            // Сендер ищется по терминалам, значит нет терминала
            $this->log("Can't find terminal for $sender");
            $model->addError('sender', Yii::t('other', 'Unknown sender {sender}',
                    ['sender' => $sender]));

            return $model;
        }

        $receiver = $model->pduAttributes['receiver'];
        $model->recipient = Cert::getParticipantAddress($receiver);
        if (!$model->recipient) {
            // Recipient ищется по серту, значит нет серта
            $this->log("Can't find certificate for $receiver");
            $model->addError('recipient', Yii::t('other', 'Unknown recipient {recipient}',
                    ['recipient' => $receiver]));

            return $model;
        }

        // Имя бин-файла всегда будет такое, как в документе, независимо от того,
        // что могли передать в binPath
        $binFileName = FileHelper::mb_basename($model->pduAttributes['file']);

        // Если задан конкретный файл, то берем его, иначе используем путь для поиска
        if (is_file($binPath)) {
            $binFilePath = $binPath;
        } else {
            $binFilePath = $binPath . '/' . $binFileName;
        }

        if (!file_exists($binFilePath) || !is_readable($binFilePath)) {
            $this->log('Bin file error: not found');
            $model->addError('binFileName', 'Bin file not found');

            return $model;
        }

        $model->uuid = Uuid::generate();

        $zipFileName = $model->uuid . $this->_config->containerExt;
        //$resOut      = Yii::$app->registry->getStorageResource(static::SERVICE_ID, $this->_config->resourceOut);
        $zipFilePath = $this->createZip(
            $zipFileName,
            [
                $pduFilePath => FileHelper::mb_basename($pduFilePath),
                $binFilePath => $binFileName
            ]
        );

        $storedZip = $this->storeFileOut($zipFilePath, $zipFileName);
        $storedPdu = $this->storeFileOut($pduFilePath, FileHelper::mb_basename($pduFilePath));
        $storedBin = $this->storeFileOut($binFilePath, $binFileName);

        if (empty($storedPdu) || empty($storedBin) || empty($storedZip)) {
            return false;
        }

        $model->zipStoredFileId = $storedZip->id;
        $model->pduStoredFileId = $storedPdu->id;
        $model->binStoredFileId = $storedBin->id;

        return $model;
    }

    /**
     * Save file into storage. Using out folder
     *
     * @param string $path Data for save in storage
     * @param string $filename File name
     * @return StoredFile|null
     */
    public function storeFileOut($path, $filename = '')
    {
        return $this->storeFile($path, $this->_config->resourceOut, $filename);
    }

    /**
     * Save data into storage. Using out folder
     *
     * @param string $data Data to save
     * @param string $filename File name
     * @return StoredFile|null
     */
    public function storeDataOut($data, $filename = '')
    {
        return $this->storeData($data, $this->_config->resourceOut, $filename);
    }

    /**
     * Get FileAct document instance
     *
     * @param integer $id FileAct document ID
     * @return FileAct|null
     */
    public function getDocument($id)
    {
        return FileActSearch::findOne($id);
    }

    /**
     * Создает зип-архив в темп-каталоге.
     * @param string $filename Имя зип-файла
     * @param array $fileList Список путей к файлам для зипования
     * @return boolean | string Путь к готовому архиву или false
     */
    protected function createZip($filename, $fileList)
    {
        $zipPath = Yii::getAlias('@temp/') . $filename;

        $zipFile = new ZipArchive();
        if ($zipFile->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        foreach ($fileList as $filePath => $fileName) {
            if ($zipFile->addFile($filePath, $fileName) === false) {
                return false;
            }
        }

        if (!$zipFile->close()) {
            return false;
        }

        return $zipPath;
    }

    public function isCryptoProSignEnabled($terminalId)
    {
        if ($this->settings->enableCryptoProSign) {
            if (CryptoproKey::findByTerminalId(Terminal::getIdByAddress($terminalId))) {
                return true;
            }
        }

        return false;
    }

    public function processDocument(Document $document, $sender = null, $receiver = null)
    {
        if ($this->processDocumentExtStatus($document, $sender, $receiver)) {
            return $document->updateStatus(Document::STATUS_SERVICE_PROCESSING);
        }

        return parent::processDocument($document);
    }

    public function processDocumentExtStatus($document, $sender = null, $receiver = null)
    {
        if ($this->isCryptoProSignEnabled($sender) &&
            !in_array($document->extModel->extStatus,
                [
                    FileActDocumentExt::STATUS_FOR_CRYPTOPRO_SIGNING,
                    FileActDocumentExt::STATUS_CRYPTOPRO_SIGNING_ERROR,
                    FileActDocumentExt::STATUS_CRYPTOPRO_SIGNED
            ])) {

            $document->extModel->extStatus = FileActDocumentExt::STATUS_FOR_CRYPTOPRO_SIGNING;

            // Если модель успешно сохранена в БД
            if ($document->extModel->save()) {
                Yii::$app->resque->enqueue('common\jobs\CryptoProSignJob',
                [
                    'id' => $document->id,
                    'sender' => $sender,
                    'receiver' => $receiver
                ]);

                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param CyberXmlDocument $cyx
     * @param FileActDocumentExt $extModel
     * @return boolean
     */
    private function verifyCryptoPro(CyberXmlDocument $cyx, FileActDocumentExt $extModel)
    {
        if (false === $cyx->content->isSigned) {
            return true;
        }

        $result = CryptoProHelper::verify(static::SERVICE_ID, $cyx->storedFile->getRealPath(), $cyx->senderId, $cyx->receiverId);
        if ($result) {
            $extModel->extStatus = FileActDocumentExt::STATUS_CRYPTOPRO_VERIFIED;
        } else {
            $extModel->extStatus = FileActDocumentExt::STATUS_CRYPTOPRO_VERIFICATION_FAILED;
        }

        // Сохранить модель в БД
        $extModel->save();

        return $result;
    }

    public function getName(): string
    {
        return Yii::t('app', 'FileAct');
    }
}