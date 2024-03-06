<?php
namespace common\modules\transport\jobs;

use common\base\RegularJob;
use common\components\TerminalId;
use common\document\Document;
use common\helpers\FileHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\ImportError;
use common\models\Terminal;
use ErrorException;
use Exception;
use Resque_Job_DontPerform;
use Yii;

class ImportJob extends RegularJob
{
    private $_module;

    private $_errorResource;
    private $_importResource;

    private $_formatDetectorList = [
        '\common\modules\transport\helpers\FormatDetectorCyberXml',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->_module = Yii::$app->getModule('transport');

        if (empty($this->_module)) {
            $this->log('Transport module not found', true);
            throw new Resque_Job_DontPerform('Transport module not found');
        }

        $this->_importResource = Yii::$app->registry->getImportResource($this->_module->serviceId);
        $this->_errorResource = Yii::$app->registry->getImportResource($this->_module->serviceId, 'error');

        if (empty($this->_importResource) || empty($this->_errorResource)) {
            $this->log('Resource configuration error', true);
            throw new Resque_Job_DontPerform('Resource configuration error');
        }
    }

    public function perform()
    {
        $this->log('Importing Transport document types', false, 'regular-jobs');

        $files = $this->_importResource->getContents();
        foreach($files as $file) {
            if (!is_dir($file)) {
                $this->processFile($file);
            }
        }
    }

    private function processFile($filePath)
    {
        foreach ($this->_formatDetectorList as $formatDetector) {

            $result = false;

            try {
                $model = $formatDetector::detect($filePath);

                if ($model instanceof CyberXmlDocument) {
                    //$this->_errorResource->putFile($filePath, $filePath);

                    $result = $this->processCyberXmlDocument($model, Document::ORIGIN_FILE, $filePath);

                    break;
                }
            } catch (Exception $ex) {
                $this->log('Exception while or after using ' . $formatDetector . ' on file '
                        . $filePath . ': ' . $ex->getMessage(), true);
            }
        }

        // all format detectors checked and no result? lol file is invalid
        if ($result == false) {
            // move to error directory
            $this->log('File ' . $filePath . ' was not recognized as valid.');
            $this->_errorResource->putFile($filePath, $filePath);
        }

        unlink($filePath);
    }

    private function processCyberXmlDocument(CyberXmlDocument $cyxDoc, $origin, $filePath)
    {
        if (!$cyxDoc->validateXSD()) {
            $this->log(
                'Error validating CyberXml document. Info: ' . print_r($cyxDoc->getErrors(), true),
                true
            );

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_TRANSPORT,
                'identity'              => $cyxDoc->docId,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => [
                    'text' => "Error validating document"
                ],
                'documentType'          => $cyxDoc->docType,
                'senderTerminalAddress' => $cyxDoc->senderId,
            ]);

            return false;
        }

        // Проверяем, не был ли этот документ уже отправлен
        $document = Document::findOne(['uuid' => $cyxDoc->docId, 'direction' => Document::DIRECTION_OUT]);

        if ($document) {
            // Если такой документ существует, то логируем ошибку и завершаем процесс
            $this->log('Document already exists. Uuid - ' . $cyxDoc->docId, true);

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_TRANSPORT,
                'identity'              => $cyxDoc->docId,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => [
                    'text' => 'Document already exists'
                ],
                'documentType'          => $cyxDoc->docType,
                'senderTerminalAddress' => $cyxDoc->senderId,
            ]);

            return false;
        }

        if (!$this->checkSender($cyxDoc->senderId)) {
            $this->log('invalid sender in CyberXmlDocument: ' . $cyxDoc->senderId, true);

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_TRANSPORT,
                'identity'              => $cyxDoc->docId,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => [
                    'text'   => 'Invalid sender: {sender}',
                    'params' => [
                        'sender' => $cyxDoc->senderId
                    ]
                ],
                'documentType'          => $cyxDoc->docType,
                'senderTerminalAddress' => $cyxDoc->senderId,
            ]);

            return false;
        }

        $storedFile = $this->_module->storeDataOut($cyxDoc->saveXML());

        if (empty($storedFile)) {
            $this->log('Could not create stored file for CyberXMLDocument ' . $cyxDoc->docType);

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_TRANSPORT,
                'identity'              => $cyxDoc->docId,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => [
                    'text'   => 'Could not create stored file: {type}',
                    'params' => [
                        'type' => $cyxDoc->docType
                    ]
                ],
                'documentType'          => $cyxDoc->docType,
                'senderTerminalAddress' => $cyxDoc->senderId,
            ]);

            return false;
        }

        $document = new Document([
            'origin' => $origin,
            'direction' => Document::DIRECTION_OUT,
            'type' => $cyxDoc->docType,
            'typeGroup'=> $this->_module->getServiceId(),
            'actualStoredFileId' => $storedFile->id,
            'sender' => $cyxDoc->senderId,
            'receiver' => $cyxDoc->receiverId,
            'uuid' => $cyxDoc->docId,
            'status' => Document::STATUS_FOR_MAIN_AUTOSIGNING,
            'terminalId' => Terminal::getIdByAddress($cyxDoc->senderId)
        ]);

        if ($document->save()) {
            // StatusReport должен проходить через другую очередь
//            if ($cyxDoc->docType == StatusReportType::TYPE) {
//
//                Yii::$app->resque->enqueue(
//                    'common\jobs\StateJob',
//                    [
//                        'stateClass' => 'common\states\out\ServiceOutState',
//                        'params' => serialize([
//                            'status' => 'sign',
//                            'documentId' => $document->id
//                        ])
//                    ],
//                    true,
//                    \common\components\Resque::OUTGOING_QUEUE
//                );
//            } else {
                Yii::$app->resque->enqueue(
                    'common\jobs\StateJob',
                    [
                        'stateClass' => 'common\states\out\SendingState',
                        'params' => serialize([
                            'status' => 'sign',
                            'documentId' => $document->id
                        ])
                    ],
                    true,
                    \common\components\Resque::OUTGOING_QUEUE
                );
//            }

            return true;
        }

        return false;
    }

    protected function checkSender($sender)
    {
    	try {
			// Выясняем, какому участнику принадлежит терминал, от лица которого отсылается документ
			if (($extracted = TerminalId::extract($sender))) {
				$participant = $extracted->participantId;
			} else {
				throw new ErrorException("No participant exists for sender {$sender}");
			}

			// В цикле ищем соответствие участника терминалам, указанным в конфигурации терминала
			foreach (Yii::$app->terminals->addresses as $terminalId) {
				if (($extracted = TerminalId::extract($terminalId))) {
					$configuredParticipant = $extracted->participantId;
				} else {
					throw new ErrorException("No participant exists for Terminal configuration {$terminalId}");
				}
				// Соответствие есть.
				if ($participant == $configuredParticipant) {
					return true;
				}
			}
			// Сюда попадаем, если нет соответствий в конфиге, и мы не можем отсылать
			// "не свои" документы
			throw new ErrorException("Illegal document sender {$sender}");
		} catch(ErrorException $ex) {
            $this->log('Error while checking sender: ' . $ex->getMessage());
        }

        return false;
    }

}