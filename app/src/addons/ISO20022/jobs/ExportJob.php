<?php
namespace addons\ISO20022\jobs;

use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\helpers\ISO20022Receipt;
use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\Auth018Type;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\Auth025Type;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\ISO20022DocumentExt;
use addons\ISO20022\models\Pain001Type;
use common\base\DocumentJob;
use common\components\Registry;
use common\document\Document;
use common\helpers\FileHelper;
use common\helpers\StringHelper;
use common\helpers\ZipHelper;
use common\modules\api\ApiModule;
use common\modules\transport\jobs\RegularExportCheck;
use common\settings\AppSettings;
use common\settings\ExportSettings;
use DateTime;
use Exception;
use Psr\Log\LogLevel;
use Yii;

class ExportJob extends DocumentJob
{
    private $_exportErrorInfo = 'Export error';
    private $_useMaxAttemptsCount = false;
    private $_exportResource;
    private $_senderDir;

    public function setUp()
    {
        $this->_logCategory = 'ISO20022';
        $this->_module = Yii::$app->getModule('ISO20022');
        $this->_settings = Yii::$app->settings->get('ISO20022:ISO20022');
        $this->_filename = '';

        parent::setUp();
    }

    public function perform()
    {
        $result = false;
        $exportDate = new DateTime();
        $sender = $this->_cyxDocument->senderId;

        try {
            $this->_exportResource = $this->getExportResource(ISO20022Module::SERVICE_ID);
            if (empty($this->_exportResource)) {
                throw new Exception('failed to get export resource');
            }

            $this->_senderDir = $this->_exportResource->createDir($sender);
            if (!$this->_senderDir) {
                throw new Exception('failed to create dir ' . $this->_exportResource->getPath() . '/' . $sender);
            }

            if ($typeModel = $this->_cyxDocument->getContent()->getTypeModel()) {

                if ($typeModel->type !== Auth026Type::TYPE) {
                    $fileName = $typeModel->fileName;
                } else {
                    $fileName = reset($typeModel->fileNames);
                }

                $hasFinzipAttachment =
                    !empty($fileName)
                    && (
                        $typeModel->type === Auth024Type::TYPE
                        || ($typeModel->type === Auth026Type::TYPE && empty($typeModel->embeddedAttachments))
                    )
                    && !$typeModel->useZipContent;

                if ($hasFinzipAttachment) {
                    $this->_document->updateStatus(Document::STATUS_SERVICE_PROCESSING);

					return;
                }

                // Если включен экспорт XML, то документ был экспортирован в DocumentDuplicateExportStep
                if ($this->isCyberXmlExported()) {
                    $this->log("Document $this->docInfo was previously exported to XML");
                    $this->_document->updateStatus(Document::STATUS_EXPORTED, 'Export');

                    return;
                }
                $result = $this->processISO20022($typeModel);
            } else {
                $this->log($this->docInfo . ' failed to get type model', true);
            }
        } catch(Exception $ex) {
            $this->log($ex->getMessage(), true);
        }

        if ($result) {
            $savedPath = $this->replaceSftpAddress($result);
            $this->log($this->docInfo . ' exported to ' . $savedPath);

            ApiModule::addToExportQueueIfRequired($this->_document->uuidRemote, $savedPath, $this->_document->receiver);

            Yii::$app->monitoring->log(
                'document:DocumentExport',
                'document',
                $this->_documentId,
                ['path' => $savedPath]
            );

            if ($this->_settings->exportReceipts == 1) {
                $this->exportReceipt($exportDate);
            }
        } else {
            $this->_document->updateStatus(Document::STATUS_NOT_EXPORTED, $this->_exportErrorInfo);

            // если документ не экспортирован по причине некорректных данных,
            // повторные попытки экспорта не нужны
            if ($this->_useMaxAttemptsCount) {
                $this->_document->attemptsCount = RegularExportCheck::MAX_ATTEMPTS_COUNT;
                $this->_document->save();
            }

            // Если у модуля включен доступ по sftp
            // и документ не экспортирован, то меняем дополнительный статус документа
            if ($this->_settings->sftpEnable) {

                if ($this->_document->extModel) {
                    $this->_document->extModel->extStatus = ISO20022DocumentExt::STATUS_SFTP_ERROR;
                    $this->_document->extModel->save();
                }

                // Создание события
                Yii::$app->monitoring->log('transport:sftpOpenFailed', 'sftp', 0, [
                    'logLevel' => LogLevel::ERROR,
                    'serviceId' => ISO20022Module::SERVICE_ID,
                    'path' => 'export',
                    'terminalId' => $this->_document->terminalId
                ]);
            }
        }
    }

    private function processISO20022($typeModel)
    {
        $sender = $this->_cyxDocument->senderId;

        try {
            // Проверяем настройку ISO для способа получения имени файла и тип документа
            $cyxFilename = $this->_settings->keepOriginalFilename ? $this->_cyxDocument->filename : null;
            $hasRosbankEnvelope = property_exists($typeModel, 'rosbankEnvelope') && $typeModel->rosbankEnvelope !== null;
            $fileName = $cyxFilename ?: $this->makeFilename($sender, $typeModel, $this->_cyxDocument->docId, 'xml');

            if ($hasRosbankEnvelope) {
                $content = $typeModel->rosbankEnvelope->toXml();
                $this->_fileName = $fileName;
                $savedPath = $this->atomicWrite($content, $this->_senderDir . '/' . $this->_fileName);
            } else if (
                // если включена опция экспорта в формат ibank и это pain.001
                $typeModel->type == Pain001Type::TYPE
                && $this->_settings->exportIBankFormat
                && !$typeModel->getPainDocumentType()
            ) {
                $savedPath = $this->exportIbank($typeModel, $sender, $cyxFilename);
            } else if (
                // типы Auth с аттачментами в зипе
                in_array(
                    $typeModel->type,
                    [Auth018Type::TYPE, Auth024Type::TYPE, Auth025Type::TYPE, Auth026Type::TYPE]
                )
            ) {
                $savedPath = $this->exportAuth($typeModel, $sender, $cyxFilename);
            } else {
                $content = StringHelper::fixXmlHeader($typeModel->getModelDataAsString(true));
                $this->_fileName = $fileName;
                $savedPath = $this->atomicWrite($content, $this->_senderDir . '/' . $this->_fileName);
            }

            $this->_document->updateStatus(Document::STATUS_EXPORTED, 'Export');

            return $savedPath;
        } catch(Exception $ex) {
            $this->log($this->docInfo . ' ' . $ex->getMessage(), true);

            return false;
        }
    }

    private function exportIbank($typeModel, $sender, $cyxFilename)
    {
        // Создание директории для экспорта IBank
        $senderDir = $this->_exportResource->createDir('IBANK/' . $sender);

        if (!$senderDir) {
            throw new Exception('failed to create dir ' . $this->_exportResource->getPath()
                    . '/IBANK/' . $sender);
        }

        try {
            $content = ISO20022Helper::createIBankFromPain001($typeModel);
        } catch (Exception $ex) {
            $errorText = 'Creating iBank file format from pain.001 failed: ' . $ex->getMessage();
            $this->_exportErrorInfo = $errorText;

            throw new Exception($errorText);
        }

        // Если в конверте CYX есть имя оригинального файла, то используем его для экспорта
        if (!empty($cyxFilename)) {
            $fileInfoCyx = FileHelper::mb_pathinfo($cyxFilename);
            $fileName = $fileInfoCyx['filename'];
        } else {
            $fileName = $sender . '_ibank_' . $this->_cyxDocument->docId;
        }

        $this->_fileName = $fileName;

        return $this->atomicWrite($content, $senderDir . '/' . $fileName . '.txt');
    }

    private function exportAuth($typeModel, $sender, $cyxFilename)
    {
        $fileName = $this->makeFilename($sender, $typeModel, $this->_cyxDocument->docId);
        $zipFileName = $cyxFilename ?: $fileName . '.zip';
        if (substr($zipFileName, -4) !== '.zip') {
            $zipFileName .= '.zip';
        }

		$targetZip = null;
		$zipArchive = null;
		/** @var common\settings\AppSettings $settings */
		$settings = Yii::$app->settings->get('app', $this->getTerminalAddress());
		$encoding = $settings->useUtf8ZipFilenameEncoding ? null : 'cp866';

        if ($typeModel->useZipContent) {
            $zipArchive = ZipHelper::createArchiveFileZipFromString($typeModel->zipContent);
			if (!$encoding) {
				$targetZip = ZipHelper::createTempArchiveFileZip();
			}
        } else {
            $extModel = $this->_document->extModel;
            if ($extModel && $extModel->storedFileId) {
                $storedFile = Yii::$app->storage->get($extModel->storedFileId);
               // $zipArchive = ZipHelper::copyArchiveFileZipFromFile($storedFile->getRealPath());
				$zipArchive = \common\helpers\ArchiveFileZip::open($storedFile->getRealPath());
				if (!$encoding) {
					$targetZip = ZipHelper::createTempArchiveFileZip();
				}
			} else {
                // Если нет аттачмента в сторед файле (старая версия аттачментов), то тайп модель
                // нужно все равно экспортировать в архиве, поэтому создаем архив
                $zipArchive = ZipHelper::createArchiveFileZipFromString('');
                $zipArchive->addFromString(
                    StringHelper::fixXmlHeader($typeModel->getModelDataAsString(true)),
                    $fileName . '.xml',
                    $encoding
                );
            }
        }

		if ($targetZip) {
			$fileList = $zipArchive->getFileList('cp866');
			foreach ($fileList as $pos => $name) {
				$content = $zipArchive->getFromIndex($pos);
				$targetZip->addFromString($content, $name);
			}
			$targetZip->close();
		} else {
			$targetZip = $zipArchive;
		}

        $zipArchive->close();
        $savedPath = $this->atomicWrite($targetZip->getPath(), $this->_senderDir . '/' . $zipFileName, true);
        $this->_fileName = $zipFileName;

        return $savedPath;
    }

    private function atomicWrite($content, $dstPath, $isFile = false)
    {
        $sftpEnable = $this->_settings->sftpEnable == 1;

        // Если передан путь к файлу, но экспорт на SFTP, то нужно сначала прочитать контент из файла
        // и поместить в tmp папку на sftp.
        if ($isFile && $sftpEnable) {
            $content = file_get_contents($content);
            $isFile = false;
        }

        if (!$isFile) {
            $tempDir = $this->_exportResource->createDir('temp');
            if (!$tempDir) {
                throw new Exception('failed to create temp dir in ' . $this->_exportResource->getPath());
            }
            $savedTempPath = $tempDir . '/' . FileHelper::uniqueName();
            if (false === file_put_contents($savedTempPath, $content)) {
                throw new Exception('failed to write temp export file ' . $this->replaceSftpAddress($savedTempPath));
            }
        } else {
            $savedTempPath = $content;
        }

        // Если экспорт на sftp, сначала проверяем наличие файла со сходным именем
        // и модифицируем название, если такой файл уже существует
        if ($sftpEnable && file_exists($dstPath)) {
            $fileInfo = pathinfo($dstPath);
            $timeTag = date('Y-m-d-H-i-s', time());
            $dstPath = "{$fileInfo['dirname']}/{$fileInfo['filename']}-{$timeTag}.{$fileInfo['extension']}";
        }

        if (false === rename($savedTempPath, $dstPath)) {
            throw new Exception('failed to move temp export file ' . $this->replaceSftpAddress($dstPath));
        }

        if (false === $this->_exportResource->chmod($dstPath, 0664)) {
            $this->log($this->docInfo . ' failed to set permissions on '
                    . $this->replaceSftpAddress($dstPath), true);
        }

        return $dstPath;
    }

    /**
     * Преобразование строки с адресом sftp-сервера
     * @param $path
     */
    private function replaceSftpAddress($path)
    {
        if ($this->_settings->sftpEnable == 1) {
            $sftpPath = $this->_settings->sftpHost;
            $regExp = '#(?<=ssh2\.sftp://)[0-9]{3}(?=/)#';

            return preg_replace($regExp, $sftpPath, $path);
        }

        return $path;
    }

    private function exportReceipt(DateTime $exportDate)
    {
        try {
            $exportResource = $this->getExportResource(ISO20022Module::SERVICE_ID);
            $receiptsDirPath = $exportResource->createDir('receipt');
            $receipt = new ISO20022Receipt($this->_cyxDocument, $this->_filename, $exportDate);
            $receiptFilePath = $receipt->export($receiptsDirPath);
            $this->log("Receipt for {$this->docInfo} is exported to $receiptFilePath");
        } catch (Exception $exception) {
            $this->log("Failed to export receipt for {$this->docInfo}, caused by: $exception", true);
        }
    }

    private function makeFilename($sender, $typeModel, $docId, $ext = null)
    {
        $type = $typeModel->getFullType();
        if (substr($type, 0, 8) != 'auth.026') {
            $name = "{$sender}_{$type}_{$docId}";
        } else {
            $name = "{$sender}_{$type}_{$typeModel->msgId}";
        }

        if ($ext) {
            $name .= '.' . $ext;
        }

        return $name;
    }

    private function getExportResource($serviceId, $dirId = Registry::DEFAULT_RESOURCE_ID)
    {
        return $this->shouldUseGlobalExportSettings()
            ? Yii::$app->registry->getExportResource($serviceId, $dirId)
            : Yii::$app->registry->getTerminalExportResource($serviceId, $this->getTerminalAddress(), $dirId);
    }

    private function shouldUseGlobalExportSettings(): bool
    {
        /** @var AppSettings $terminalSettings */
        $terminalSettings = Yii::$app->settings->get('app', $this->getTerminalAddress());
        return (bool)$terminalSettings->useGlobalExportSettings;
    }

    private function getTerminalAddress()
    {
        return $this->_cyxDocument->receiverId;
    }

    private function isCyberXmlExported()
    {
        if ($this->shouldUseGlobalExportSettings()) {
            $moduleSettings = $this->_module->settings;
            return $moduleSettings && property_exists($moduleSettings, 'exportXml') && $moduleSettings->exportXml;
        } else {
            /** @var ExportSettings $terminalExportSettings */
            $terminalExportSettings = Yii::$app->settings->get('export', $this->getTerminalAddress());
            return (bool)$terminalExportSettings->serviceNeedsExport($this->_module->getServiceId());
        }
    }
}
