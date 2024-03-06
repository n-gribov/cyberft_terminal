<?php
namespace addons\ISO20022\jobs;

use addons\finzip\models\FinZipDocumentExt;
use addons\finzip\models\FinZipType;
use addons\ISO20022\helpers\ISO20022Receipt;
use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\base\RegularJob;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\helpers\FileHelper;
use common\helpers\StringHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\settings\AppSettings;
use Exception;
use Resque_Job_DontPerform;
use Yii;
use yii\base\InvalidConfigException;
use ZipArchive;

class AttachmentJob extends RegularJob
{
    private $_finZipModule;
    private $_module;
    private $_tempResource;

    const ATTACHMENT_TIMEOUT = 600; // seconds

    public function setUp()
    {
        try {
            $this->_logCategory = 'ISO20022';
            parent::setUp();

            $this->_finZipModule = Yii::$app->getModule('finzip');
            if (empty($this->_finZipModule)) {
                $this->log('FinZip module not found', true);

                throw new Resque_Job_DontPerform('FinZip module not found');
            }

            $this->_module = Yii::$app->getModule(ISO20022Module::SERVICE_ID);
            $this->_tempResource = Yii::$app->registry->getTempResource(ISO20022Module::SERVICE_ID);
        } catch(Exception $ex) {
            $this->log($ex->getMessage(), true);

            throw new Resque_Job_DontPerform('Unable to continue');
        }

        if (empty($this->_module)) {
            $this->log('ISO20022 module not found', true);

            throw new Resque_Job_DontPerform('ISO20022 module not found');
        } else if (empty($this->_tempResource)) {
            $this->log('ISO20022 resource configuration error', true);

            throw new Resque_Job_DontPerform('ISO20022 resource configuration error');
        }
    }

    public function perform()
    {
        $this->log('Auth026/Auth024 Checking for FinZip attachments', false, 'regular-jobs');
        $this->checkOutgoingAttachments();
        $this->checkIncomingAttachments();
    }

    private function checkOutgoingAttachments()
    {
        $docList = Document::find()
            ->from(Document::tableName() . ' doc')
            ->innerJoin(
                ISO20022DocumentExt::tableName() . ' ext',
                [
                    'and',
                    'ext.documentId = doc.id',
                    ['ext.extStatus' => ISO20022DocumentExt::STATUS_AWAITING_ATTACHMENT],
                    ['doc.direction' => Document::DIRECTION_OUT],
                    ['doc.status' => Document::STATUS_SERVICE_PROCESSING],
                    ['doc.type' => [Auth024Type::TYPE]]
                ]
            )
            ->all();

        $updateList = [];

        foreach ($docList as $doc) {
            $updateList[$doc->uuid] = $doc->id;
        }

        $docList = Document::find()
            ->from(Document::tableName() . ' doc')
            ->innerJoin(FinZipDocumentExt::tableName() . ' ext',
                [
                    'and',
                    'ext.documentId = doc.id',
                    ['doc.type' => FinZipType::TYPE],
                    ['doc.direction' => Document::DIRECTION_OUT],
                    ['ext.attachmentUUID' => array_keys($updateList)]
                ]
            )->all();

        foreach($docList as $doc) {
            if (in_array(
                $doc->status,
                [
                    Document::STATUS_CREATING_ERROR,
                    Document::STATUS_PROCESSING_ERROR,
                    Document::STATUS_SIGNING_ERROR,
                    Document::STATUS_AUTOSIGNING_ERROR,
                    Document::STATUS_MAIN_AUTOSIGNING_ERROR,
                    Document::STATUS_ENCRYPTING_ERROR,
                    Document::STATUS_NOT_UPLOADED,
                    Document::STATUS_NOT_SENT,
                    Document::STATUS_REJECTED,
                    Document::STATUS_UNDELIVERED,
                    Document::STATUS_DELIVERED
                ])
            ) {
                $docId = $updateList[$doc->extModel->attachmentUUID];
                DocumentHelper::updateDocumentStatusbyId($docId, $doc->status);
            }
        }
    }

    private function checkIncomingAttachments()
    {
        // Найти все входящие Auth026, которые находятся в статусе processing или undelivered
        $docList = Document::find()->where([
            'type' => [Auth026Type::TYPE, Auth024Type::TYPE],
            'status' => [Document::STATUS_SERVICE_PROCESSING, Document::STATUS_ATTACHMENT_UNDELIVERED],
            'direction' => Document::DIRECTION_IN,
        ])->all();

        $updateList = [];

        $now = date_create();
        $nowTimestamp = date_timestamp_get($now) - static::ATTACHMENT_TIMEOUT;

        foreach ($docList as $isoDoc) {

            $updated = date_create_from_format('Y-m-d H:i:s', $isoDoc->dateUpdate);
            $updatedTimestamp = date_timestamp_get($updated);

            // Документы, которые слишком долго находятся в статусе processing,
            // получают статус attachment_undelivered

            if ($isoDoc->status == Document::STATUS_SERVICE_PROCESSING && $updatedTimestamp < $nowTimestamp) {
                $isoDoc->updateStatus(Document::STATUS_ATTACHMENT_UNDELIVERED);
            }

            $updateList[$isoDoc->uuidRemote] = $isoDoc;
        }

        // Найти все входящие, но не экспортированные финзипы, которые соответствуют ранее найденным
        // и сохраненным в список auth026/auth024

        $finzipList = FinZipDocumentExt::find()
            ->from(FinZipDocumentExt::tableName() . ' AS fz')
            ->innerJoin(
                Document::tableName() . ' AS doc',
                [
                    'and',
                    'doc.id = fz.documentId',
                    ['doc.status' => Document::STATUS_PROCESSED],
                    ['doc.direction' => Document::DIRECTION_IN],
                    ['fz.attachmentUUID' => array_keys($updateList)]
                ]
            )
            ->all();

        foreach($finzipList as $finzip) {
            $isoDoc = $updateList[$finzip->attachmentUUID];

            $cyxDoc = $isoDoc->getCyberXml();
            list($isExported, $storedFile, $exportedZipFilePath) = $this->export($finzip, $cyxDoc, $isoDoc);
            $exportDate = new \DateTime();

            if ($isExported) {
                // Если финзип экспортирован удачно

                $isoExt = ISO20022DocumentExt::findOne(['documentId' => $isoDoc->id]);

                $isoExt->storedFileId = $storedFile->id;
                $isoExt->extStatus = null;
                $isoExt->save(false, ['storedFileId', 'extStatus']);

                // если статус был undelivered, то отправляем репорт об успешной доставке
                if ($isoDoc->status == Document::STATUS_ATTACHMENT_UNDELIVERED) {
                    // Отправить Status Report
                    DocumentTransportHelper::statusReport($isoDoc, [
                        'statusCode' => 'ACDC',
                        'errorCode' => '0',
                        'errorDescription' => ''
                    ]);
                }

                DocumentHelper::updateDocumentStatus($isoDoc, Document::STATUS_EXPORTED);

                if ($this->_module->settings->exportReceipts == 1) {
                    $this->exportReceipt($isoDoc, $cyxDoc, basename($exportedZipFilePath), $exportDate);
                }
            } else {
                if ($isoDoc->status !== Document::STATUS_ATTACHMENT_UNDELIVERED) {
                    // Отправить Status Report
                    DocumentTransportHelper::statusReport($isoDoc, [
                        'statusCode' => 'ATDE',
                        'errorCode' => '555',
                        'errorDescription' => 'Attachment not exported'
                    ]);
                }

                $isoDoc->updateStatus(Document::STATUS_NOT_EXPORTED);
            }
        }
    }

    private function export($finzip, CyberXmlDocument $cyxDoc, Document $isoDoc)
    {
        $typeModel = $cyxDoc->getContent()->getTypeModel();
        $sender = $cyxDoc->senderId;
        $exportResource = $this->getExportResource($cyxDoc->receiverId);

        try {
            // создаем каталог отправителя в ресурсе экспорта
            $senderDir = $exportResource->createDir($sender);

            /**
             * InvalidConfigExceptionException бросается для операций, связанных с sftp-ресурсами
             */
            if (!$senderDir) {
                throw new InvalidConfigException('Cannot create dir ' . $sender . ' in resource ' . $exportResource->getPath());
            }

            $tempDir = $exportResource->createDir('temp');
            if (!$tempDir) {
                throw new InvalidConfigException('Cannot create temp dir in resource ' . $exportResource->getPath());
            }

            $zipFileName = $sender . '_' . $typeModel->getFullType() . '_' . $typeModel->msgId . '.zip';

            $storedFile = Yii::$app->storage->get($finzip->zipStoredFileId);

            if (!$storedFile) {
                throw new InvalidConfigException('Cannot get stored file for zip storage id ' . $finzip->zipStoredFileId);
            }

            // в экспорте нужно создать зип, внутри которого будет xml и вложение
            // копируем уже существующий зип с вложением в наш темп-ресурс
            // избегаем копировать сразу в экспорт-ресурс, т.к. операция должна быть атомарна

            if ($storedFile->isEncrypted) {
                $document = $finzip->document;
                Yii::$app->exchange->setCurrentTerminalId($document->originTerminalId);
                $data = Yii::$app->storage->decryptStoredFile($storedFile->id);
                $fileInfo = $this->_tempResource->putData($data, $zipFileName);
            } else {
                $fileInfo = $this->_tempResource->putFile($storedFile->getRealPath(), $zipFileName);
            }

            if (!$fileInfo) {
                throw new InvalidConfigException(
                    "Cannot copy {$storedFile->getRealPath()} to {$this->_tempResource->path}/$zipFileName"
                );
            }

            $tempZipPath = $fileInfo['path'];

            // теперь открываем скопированный зип
            $zipArchive = new ZipArchive();
            $zipArchive->open($tempZipPath);

            for ($fileIndex = 0; $fileIndex < $zipArchive->numFiles; $fileIndex++) {
                /**
                 * Get file name. If file name is cyrillic, we need to convert it
                 * and use NOT converted after that!
                 */
                $fileName = $zipArchive->getNameIndex($fileIndex);
                // удалить артефакт FINZIP
                if (FileHelper::mb_basename(iconv('cp866', 'UTF-8', $fileName)) == FinZipType::FINZIP_MESSAGE_FILE) {
                    $zipArchive->deleteName($fileName);

                    break;
                }
            }

            // теперь добавляем в этот зип xml-файл
            $body = StringHelper::fixXmlHeader($typeModel->getModelDataAsString(true));
            $xmlFileName = $sender
                . '_' . $typeModel->getFullType()
                . '_' . $typeModel->msgId . '.xml';

            $zipArchive->addFromString(iconv('UTF-8', 'cp866', $xmlFileName), $body);

            if (!$zipArchive->close()) {
                throw new Exception('Unable to write zip archive ' . $tempZipPath);
            }

            // копируем полученный зип во временный каталог экспорта
            $tempExportedZipPath = "$tempDir/$zipFileName";

            if (!copy($tempZipPath, $tempExportedZipPath)) {
                throw new Exception("Cannot copy $tempZipPath to $tempExportedZipPath");
            }

            $exportedZipPath = "$senderDir/$zipFileName";

            if (false === rename($tempExportedZipPath, $exportedZipPath)) {
                throw new InvalidConfigException('Unable to move temp export file ' . $tempExportedZipPath . ' to ' . $exportedZipPath);
            }

            if (false === $exportResource->chmod($exportedZipPath, 0664)) {
                $this->log("Unable to set file permissions for file $zipFileName in export resource", true);
            }

            $this->log("Exported file from FinZipExt ID {$finzip->id}: $exportedZipPath");

            // Зарегистрировать событие экспорта документа в модуле мониторинга
            Yii::$app->monitoring->log(
                'document:DocumentExport',
                'document',
                $isoDoc->id,
                [
                    'path' => $exportedZipPath,
                    'terminalId' => $isoDoc->terminalId
                ]
            );

            return [true, $storedFile, $exportedZipPath];
        } catch(InvalidConfigException $ex) {
            $this->log($ex->getMessage(), true);

            if ($this->_module->settings->sftpEnable) {
                $isoDoc->extModel->extStatus = ISO20022DocumentExt::STATUS_SFTP_ERROR;
                // Сохранить модель в БД
                $isoDoc->extModel->save();
            }
        } catch(Exception $ex) {
            $this->log($ex->getMessage(), true);

            return [false, null, null];
        } finally {
            if (!empty($tempZipPath) && is_readable($tempZipPath)) {
                unlink($tempZipPath);
            }
        }
    }

    private function exportReceipt(Document $isoDoc, CyberXmlDocument $cyxDocument, $exportedFileName, \DateTime $exportDate)
    {
        try {
            $exportResource = $this->getExportResource($cyxDocument->receiverId);
            $receiptsDirPath = $exportResource->createDir('receipt');
            $receipt = new ISO20022Receipt($cyxDocument, $exportedFileName, $exportDate);
            $receiptFilePath = $receipt->export($receiptsDirPath);
            $this->log("Receipt for {$isoDoc->type} {$isoDoc->id} is exported to $receiptFilePath");
        } catch (Exception $exception) {
            $this->log("Failed to export receipt for {$isoDoc->type} {$isoDoc->id}, caused by: $exception", true);
        }
    }

    private function getExportResource($terminalAddress)
    {
        return $this->shouldUseGlobalExportSettings($terminalAddress)
            ? Yii::$app->registry->getExportResource(ISO20022Module::SERVICE_ID)
            : Yii::$app->registry->getTerminalExportResource(ISO20022Module::SERVICE_ID, $terminalAddress);
    }

    private function shouldUseGlobalExportSettings($terminalAddress): bool
    {
        /** @var AppSettings $terminalSettings */
        $terminalSettings = Yii::$app->settings->get('app', $terminalAddress);
        return (bool)$terminalSettings->useGlobalExportSettings;
    }
}