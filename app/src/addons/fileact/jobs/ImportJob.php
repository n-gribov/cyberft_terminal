<?php
namespace addons\fileact\jobs;

use addons\fileact\FileActModule;
use addons\fileact\models\FileActType;
use common\base\RegularJob;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\helpers\FileHelper;
use Resque_Job_DontPerform;
use common\models\ImportError;
use Yii;

class ImportJob extends RegularJob
{
    const BIN_WAITING = 20; // Время ожидания bin-файла (минуты)

    /** @var FileActModule */
	private $_module;
    private $_resXml;
    private $_resBin;
    private $_resError;

	public function setUp()
	{
		parent::setUp();

		$this->_module = Yii::$app->getModule('fileact');

        if (!$this->_module) {
            throw new Resque_Job_DontPerform('FileAct module not found');
        }

		$config = $this->_module->config;
        $serviceId = $this->_module->serviceId;
        $this->_resXml = Yii::$app->registry->getImportResource($serviceId, $config->resourceXml);
		$this->_resBin = Yii::$app->registry->getImportResource($serviceId, $config->resourceBin);
        $this->_resError = Yii::$app->registry->getImportResource($serviceId, $config->resourceError);

        if (!$this->_resXml || !$this->_resBin || !$this->_resError) {
            throw new Resque_Job_DontPerform('Resource configuration error');
        }
	}

	public function perform()
	{
        $this->log('Importing FileAct data', false, 'regular-jobs');

		$dirBin = $this->_resBin->getPath();

		foreach($this->_resXml->contents as $file) {
            $model = $this->_module->saveFileAct($file, $dirBin);

            if (!$model || $model->hasErrors()) {
                // Проверка ожидания bin-файла, если он не был приложен для импорта
                if ($model && $model->hasErrors('binFileName')) {
                    if (Yii::$app->cache->exists($file)) {
                        $valueTime = Yii::$app->cache->get($file);
                        $currentTime = time();
                        $difference = ($currentTime - $valueTime) % 60;

                        if ($difference >= self::BIN_WAITING) {
                            $errorMessage = Yii::t('other', 'Processing FileAct failed: {xml}. Can not find bin file {bin}', [
                                'xml' => FileHelper::mb_basename($file),
                                'bin' => FileHelper::mb_basename($model->pduAttributes['file'])
                            ]);
                            Yii::$app->cache->delete($file);
                        } else {
                            Yii::info('Waiting for Bin file');
                            continue;
                        }
                    } else {
                        Yii::$app->cache->set($file, time());
                        Yii::info('Waiting for Bin file');
                        continue;
                    }
                } else if ($model && $model->hasErrors('senderReference')) {
                    $errorMessage = $model->getFirstError('senderReference');
                } else if ($model && $model->hasErrors('sender')) {
                    $errorMessage = $model->getFirstError('sender');
                } else if ($model && $model->hasErrors('recipient')) {
                    $errorMessage = $model->getFirstError('recipient');
                } else {
                    $errorMessage = 'Failed to import PDU file';
                }

                // Запись в журнал ошибок импорта
                $this->addImportMessageError($file, $model, $errorMessage);

                $this->log('Error: cannot import file ' . $file);

                $dirError = $this->_resError->getPath();
                $fileName = FileHelper::mb_basename($file);
                rename($file, $dirError . '/' . $fileName);

                if ($model) {
                    $binFileName = FileHelper::mb_basename($model->pduAttributes['file']);
                    $binFilePath = $dirBin . '/' . $binFileName;

                    if (file_exists($binFilePath)) {
                        rename($binFilePath, $dirError . '/' . $binFileName);
                    }
                }

                continue;
            }

            // Удаление кэша ожидания Bin-файла
            if (Yii::$app->cache->exists($file)) {
                Yii::$app->cache->delete($file);
            }

            $document = DocumentHelper::reserveDocument(
                    $model->getType(), Document::DIRECTION_OUT, Document::ORIGIN_FILE,
                    Yii::$app->terminals->defaultTerminal->id
            );

            if ($document) {
                // Сейчас созданы pdu, bin и zip
                // Дальше создаем CyberXml, так как он потребуется для шага подписания и отправки

                DocumentHelper::createCyberXml(
                    $document,
                    $model,
                    [
                        'uuid' => $model->uuid,
                        'zipStoredFileId' => $model->zipStoredFileId,
                        'binStoredFileId' => $model->binStoredFileId,
                        'pduStoredFileId' => $model->pduStoredFileId,
                        'binFileName'     => $model->binFileName,
                        'senderReference' => $model->senderReference
                    ]
                );

                // delete original file
                unlink($file);
                $binFileName = FileHelper::mb_basename($model->pduAttributes['file']);
                unlink($dirBin . '/' . $binFileName);
            } else {
                $errorMessage = 'Cannot reserve document in DB';
                $this->log('Error: ' . $errorMessage);

                // Запись в журнал ошибок импорта
                $this->addImportMessageError($file, false, $errorMessage);

                unlink($file);
            }
		}
	}

    /**
     * @param string $file
     * @param FileActType|false $model
     * @param string $message
     */
    private function addImportMessageError($file, $model, $message)
    {
        ImportError::createError([
            'type'                  => ImportError::TYPE_FILEACT,
            'filename'              => FileHelper::mb_basename($file),
            'identity'              => $model ? $model->senderReference : null,
            'errorDescriptionData'  => [
                'text' => $message
            ],
            'documentType'          => FileActType::TYPE,
            'senderTerminalAddress' => $model ? $model->sender : null,
        ]);
    }


}