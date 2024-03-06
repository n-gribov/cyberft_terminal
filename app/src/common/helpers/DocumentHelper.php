<?php
namespace common\helpers;

use addons\finzip\models\FinZipType;
use addons\swiftfin\models\SwiftFinDocumentExt;
use common\base\BaseType;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\models\ImportError;
use common\models\Terminal;
use common\modules\monitor\models\MonitorLogAR;
use common\modules\transport\helpers\DocumentTransportHelper;
use DateTime;
use Exception;
use Psr\Log\LogLevel;
use Yii;
use yii\base\ErrorException;

/**
 * Document helper class
 *
 * @package core
 * @subpackage helpers
 */
class DocumentHelper
{
    /**
     * Update document from file
     * @deprecated
     * @param Document $document          Document
     * @param integer $actualStoredFileId Actual storage file ID
     * @param array   $extModelParams     Ext model parrams
     * @return boolean
     */
    public static function updateDocumentFromFile(Document $document, $actualStoredFileId, $extModelParams = [])
    {
        //$document = Document::findOne(['id' => $documentId]);
        $cyxDoc = CyberXmlDocument::read($actualStoredFileId);

        if (!$cyxDoc->validateXSD()) {
            Yii::info(
                Yii::t('app', 'Error on validate CyberXml document {id}. Info: {info}', [
                    'id' => $actualStoredFileId,
                    'info' => print_r($cyxDoc->getErrors(), true),
                ]),
                'system');
            return false;
        }

        $document->actualStoredFileId = $actualStoredFileId;
        $document->uuid = (is_null($document->uuid) ? $cyxDoc->docId : $document->uuid);
        $document->sender = (!is_null($cyxDoc->senderId) ? $cyxDoc->senderId : '');
        $document->receiver = (!is_null($cyxDoc->receiverId) ? $cyxDoc->receiverId : '');

        if ($document->direction == Document::DIRECTION_OUT) {
            $document->terminalId = Terminal::getIdByAddress($document->sender);
        } else if ($document->direction == Document::DIRECTION_IN) {
            $document->terminalId = Terminal::getIdByAddress($document->receiver);
        }

        if (!$document->save()) {
            Yii::info(
                Yii::t('app', '{actionName}: Document #{documentId} not saved with data from file #{fileId}', [
                    'actionName' =>  Yii::t('app', 'Update document from file'),
                    'documentId' => $document->id,
                    'fileId' => $actualStoredFileId
                ]),
                'system');

            return false;
        }

        if (Yii::$app->registry->getTypeExtModelClass($document->type)) {
            $typeModel = $cyxDoc->getContent()->getTypeModel($extModelParams);
            $document->extModel->loadContentModel($typeModel);
            if (!$document->extModel->save()) {
                Yii::info(
                    Yii::t('app', '{actionName}: Extending model of document #{documentId} not saved with data from file #{fileId}', [
                        'actionName' =>  Yii::t('app', 'Update document from file'),
                        'documentId' => $document->id,
                        'fileId' => $actualStoredFileId
                    ]),
                    'system'
                );

                return false;
            }
        }

        return true;
    }

    /**
     * Метод создает CyberXmlDocument, сохраняет его в StoredFile, затем создает Document и ExtModel.
     * Метод призван заменить три старых метода: ReserveDocument, CreateCyberXmlJob и UpdateDocumentFromFile
     * @param BaseType $typeModel тайп-модель, которая заворачивается в CyberXml
     * @param array $docAttributes атрибуты документа
     * @param array $extAttributes атрибуты экст-модели
     * @param string $parentTerminal терминал, от лица которого формируется документ,
     *                                 в случае если сендер с ним не совпадает
     * @param array $cyxAttributes атрибуты CyberXmlDocument
     * @return array|bool [cyxdoc, document, extmodel, storedfile] или false
     * @throws Exception
     */
    public static function createDocumentContext($typeModel, $docAttributes = [], $extAttributes = null, $parentTerminal = null, $cyxAttributes = [])
    {
        $typeGroup = isset($docAttributes['typeGroup']) ? $docAttributes['typeGroup'] : null;
        $module = Yii::$app->registry->getTypeModule($typeModel->type, $typeGroup);

        if (empty($module)) {
            Yii::error('Module for type ' . $typeModel->type . ' not found');

            throw new Exception('Module for type ' . $typeModel->type . ' not found');
        }

        $settings = Yii::$app->settings->get('app', $docAttributes['sender']);

        $cyxDoc = CyberXmlDocument::loadTypeModel($typeModel);

        $cyxDoc->docDate = date('c');

        if (!isset($docAttributes['type'])) {
            $docAttributes['type'] = $typeModel->type;
        }

        if (!isset($docAttributes['uuid'])) {
            $docAttributes['uuid'] = Uuid::generate();
        }

        $cyxDoc->docId = $docAttributes['uuid'];

        if (isset($docAttributes['sender'])) {
            $cyxDoc->senderId = $docAttributes['sender'];
        }

        if (isset($docAttributes['receiver'])) {
            $cyxDoc->receiverId = $docAttributes['receiver'];
        }

        $cyxDoc->setAttributes($cyxAttributes, false);

        if (!$cyxDoc->validateXSD()) {
            Yii::error(
                'Validation failed for CyberXml document ' . $typeModel->type . ': ' . print_r($cyxDoc->getErrors(), true)
            );

            Yii::info($cyxDoc->saveXML());

            return false;
        }

        $storedFile = null;
        try {
            if ($typeModel->type == FinZipType::TYPE) {
                Yii::$app->terminals->setCurrentTerminalId($typeModel->sender);
                $storedFile = $module->storeDataOutEnc($cyxDoc->saveXML());
            } else {
                $storedFile = $module->storeDataOut($cyxDoc->saveXML());
            }
        } catch (ErrorException $ex) {
            Yii::error($ex->getMessage());
        }

		if (empty($storedFile)) {
			Yii::error('Cannot store file for CyberXml type ' . $typeModel->type);

            throw new Exception('Cannot store file for CyberXml type ' . $typeModel->type);
        }

        $docAttributes['actualStoredFileId'] = $storedFile->id;

        $document = static::createDocument($docAttributes, $extAttributes);

        if (!$document) {
            Yii::error('Could not save model for ' . $typeModel->type . ' with stored file id ' . $storedFile->id);

            return false;
		}

        if (empty($parentTerminal)) {
            $parentTerminal = $document->sender;
        }

        /*
         * Регистрируем событие, если документ создает аутентифицированный пользователь
         */
        if (Yii::$app->id !== 'app-console' && !Yii::$app->user->isGuest) {
            Yii::$app->monitoring->log('user:createDocument', 'document', $document->id, [
                'userId' => Yii::$app->user->id,
                'docType' => $typeModel->type,
                'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
            ]);
        }

        /*
         * Аддон сам решит что делать
         */
        $module->processDocument($document, $parentTerminal, $cyxDoc->receiverId);

		Yii::info('Saved ' . $typeModel->type . ' with uuid ' . $cyxDoc->docId);

		return [
            'cyxDoc' => $cyxDoc,
            'document' => $document,
        // чтобы возвращать экстмодель, надо чтобы метод createDocument
        // также возвращал массив с ней
        //    'extModel' => $extModel,
            'storedFile' => $storedFile,
        ];
    }

    /**
     * @deprecated
     *
     * @param type $document
     * @param type $typeModel
     * @param type $params
     * @return boolean
     * @throws Exception
     */
    public static function createCyberXml($document, $typeModel, $params = [])
    {
        $module = Yii::$app->registry->getTypeModule($document->type);

        if (empty($module)) {
            Yii::error('Module for type ' . $document->type . ' not found');

            throw new Exception('Module for type ' . $document->type . ' not found');
        }

        $cyx = CyberXmlDocument::loadTypeModel($typeModel);
        $cyx->docDate = date('c');

        if (isset($params['uuid'])) {
            $cyx->docId = $params['uuid'];
        } else {
            $cyx->docId = Uuid::generate();
        }

        if (isset($params['sender'])) {
            $cyx->senderId = $params['sender'];
        }

        if (isset($params['receiver'])) {
            $cyx->receiverId = $params['receiver'];
        }

        $storedFile = null;

        try {
            $storedFile = $module->storeDataOut($cyx->saveXML());
        } catch (ErrorException $ex) {
            Yii::error($ex->getMessage());
        }

		if (empty($storedFile)) {
			Yii::info('Cannot save output file');

            throw new Exception('Cannot save output file');
        }

        $result = static::updateDocumentFromFile(
            $document, $storedFile->id, $params
        );

        if (!$result) {
            $document->updateStatus(
                Document::STATUS_CREATING_ERROR,
                'Could not update model from CyberXML #' . $storedFile->id
            );

            Yii::error('Could not save model for ' . $typeModel->type . ' with stored file id ' . $storedFile->id);

            return false;
		}

        /*
         * Аддон сам решит что делать
         */
        $module->processDocument($document, $cyx->senderId, $cyx->receiverId);

        if ($document->status == Document::STATUS_ACCEPTED) {
            DocumentTransportHelper::createSendingState($document);
        } else if ($document->signaturesRequired > 0 && $document->direction == Document::DIRECTION_OUT) {
            /**
             * Если потребуется подписание, то выполним ExtractSignDataJob
             */
    		Yii::$app->resque->enqueue('common\jobs\ExtractSignDataJob', ['id' => $document->id]);
        }

		Yii::info('Saved ' . $typeModel->type . ' with uuid ' . $cyx->docId);

		return $cyx;
    }

    public static function createDocument($attributes = [], $extAttributes = [])
    {
        $attributes['scenario'] = Document::SCENARIO_RESERVE;
        $document = new Document($attributes);

        $document->status = $document->status ?: $document::STATUS_CREATING;
        $document->direction = $document->direction ?: Document::DIRECTION_OUT;

        if (in_array($document->type, ['CFTStatusReport', 'CFTAck'])) {
            $document->typeGroup = Document::TYPE_SERVICE_GROUP;
        } else {
            if (empty($document->typeGroup)) {
                if ($typeModule = Yii::$app->registry->getTypeModule($document->type)) {
                    $document->typeGroup = $typeModule::SERVICE_ID;
                } else {
                    $document->typeGroup = Document::TYPE_UNKNOWN;
                }
            }
        }

        if (!$document->save()) {
            Yii::info('Create document: could not save document');

            return false;
        }

        if (
            $document->typeGroup != Document::TYPE_SERVICE_GROUP
            && Yii::$app->registry->getTypeExtModelClass($document->type, $document->typeGroup)
        ) {
            if (is_array($extAttributes)) {
                $extAttributes['documentId'] = $document->id;
            } else {
                $extAttributes = ['documentId' => $document->id];
            }

            $extModel = $document->extModelCreateInstance($extAttributes);

            if (is_null($extModel)) {
                $document->delete();
                Yii::info('Create document: could not create document instance for type ' . $document->type);

                return false;
            } else {
                if (!$extModel->save()) {
                    $document->delete();

                    Yii::info('Create document: could not save extModel for type ' . $document->type . ', extModel errors: ' . var_export($extModel->getErrors(), true));

                    return false;
                }
            }
        }

        $document->scenario = Document::SCENARIO_DEFAULT;

        return $document;
    }

    /**
     * Reserve document
     * @deprecated
     * @param string $type      Document type
     * @param string $direction Document direction
     * @param string $origin    Document origin
     * @return boolean|Document
     */
    public static function reserveDocument($type, $direction, $origin, $terminalId = null, $extModelAttributes = null)
    {
        $document = new Document(['scenario' => Document::SCENARIO_RESERVE]);

        $document->status = $document::STATUS_CREATING;
        $document->direction = $direction;
        $document->type = $type;
        $document->origin = $origin;
        $document->terminalId = $terminalId;

        if (in_array($type, ['CFTStatusReport', 'CFTAck', 'StatusReport'])) {
            $document->typeGroup = Document::TYPE_SERVICE_GROUP;
        } else {
            if ($typeModule = Yii::$app->registry->getTypeModule($type)) {
                $document->typeGroup = $typeModule::SERVICE_ID;
            } else {
                $document->typeGroup = Document::TYPE_UNKNOWN;
            }
        }

        if ($document->save()) {
            if (
                $document->typeGroup != Document::TYPE_SERVICE_GROUP
                && Yii::$app->registry->getTypeExtModelClass($document->type)
            ) {
                if (is_null($extModelAttributes)) {
                    $extModelAttributes = ['documentId' => $document->id];
                } else {
                    $extModelAttributes['documentId'] = $document->id;
                }
                $extModel = $document->extModelCreateInstance($extModelAttributes);

                if (is_null($extModel)) {
                    $document->delete();

                    return false;
                } else {
                    if (!$extModel->save(false, ['documentId'])) {
                        $document->delete();
                        Yii::info('Reserve document: Document extModel not saved');

                        return false;
                    }
                }
            }

        } else {
            Yii::info('Reserve document: Document not saved');
        }

        $document->scenario = Document::SCENARIO_DEFAULT;

        return $document;
    }

    /**
     * Update document status
     *
     * @param integer $documentId Document ID
     * @param string  $status     New status
     * @param integer $attempt    Current attempt
     * @param string  $token      Job tokken
     * @param string  $info       Info
     * @return boolean
     */
    public static function updatedocumentStatusById($documentId, $status, $attempt = 1, $token = null, $info = null)
    {
        $document = Document::findOne(['id' => $documentId]);

        return static::updateDocumentStatus($document, $status, $attempt, $token, $info);
    }

    /**
     * Update document status
     *
     * @param Document $document  Document
     * @param string  $status     New status
     * @param integer $attempt    Current attempt
     * @param string  $token      Job token
     * @param string  $info       Info
     * @return boolean
     */
    public static function updateDocumentStatus($document, $status, $attempt = 1, $token = null, $info = null)
    {
        $params   = [
            'previousStatus' => $document->status,
            'status'         => $status,
            'attempt'        => $attempt,
            'job'            => $token,
            'info'           => $info,
            'terminalId'    => $document->terminalId
        ];

        $document->status = $status;
        $document->attemptsCount = (int) $attempt;
        $document->statusJob = $token;
        $document->statusDate = date('Y-m-d H:i:s');

        /**
         *  Run status change function for module
         *  For example: ISO20022 module will change STATUS_DELIVERED status of Auth026
         *  to STATUS_SERVICE_PROCESSING with extStatus STATUS_AWAITING_ATTACHMENT
         */
        $module = Yii::$app->registry->getTypeModule($document->type, $document->typeGroup);

        if (!empty($module) && !$document->isServiceType()) {
            $module->onDocumentStatuschange($document);
        }

        $result = $document->save(false);
        if ($result) {
            if (in_array($document->status, Document::getErrorStatus())) {
                Yii::$app->monitoring->log(
                    'document:documentProcessError', 'document', $document->id,
                    [
                        'logLevel' => LogLevel::ERROR,
                        'previousStatus' => $params['previousStatus'],
                        'status' => $status,
                        'terminalId' => $document->terminalId
                    ]
                );
            } else if (Document::STATUS_FORSIGNING == $document->status) {
                Yii::$app->monitoring->log('document:documentForSigning', 'document', $document->id, [
                    'terminalId' => $document->terminalId
                ]);
            }

            Yii::$app->monitoring->log('document:documentStatusChange', 'document', $document->id, $params);
        }

        return $result;
    }

    /**
     * Get status events for specific document
     *
     * @param integer $documentId Document ID
     * @return array
     */
    public static function getStatusEvents($documentId)
    {
        return MonitorLogAR::find()
                ->where([
                    'entity' => 'document',
                    'entityId' => $documentId,
                    'eventCode' => self::getDocumentEventNames()
                ])
                ->orderBy(['id' => SORT_DESC])
                ->all();
    }

    /**
     * Get list of events for documents
     *
     * @return array
     */
    public static function getDocumentEventNames()
    {
        return [
            'DocumentForSigning',
            'DocumentProcessError',
            'DocumentRegistered',
            'DocumentStatusChange',
            'DocumentBusinessStatusChange',
            'CryptoProSigningError',
            'ViewDocument',
            'CreateDocument',
            'VerifyDocumentError',
            'VerifyDocumentSuccess',
            'SignDocument',
            'SendDocument',
            'PrintDocument',
            'PreAuthDocument',
            'AuthDocument',
            'CorrectDocument',
            'DeleteDocument',
            'ISOReceiveStatus',
            'DocumentContSignError',
            'certificateExpired',
            'certificateNotFound',
            'invalidCertificate'
        ];
    }

    public static function getCurrentDocumentEventNames($id)
    {
        $events = MonitorLogAR::find()
            ->where([
                'entity' => 'document',
                'entityId' => $id,
                'eventCode' => self::getDocumentEventNames()
            ])
            ->orderBy(['id' => SORT_DESC])
            ->distinct()
            ->all();

        $currentEvents = [];

        foreach($events as $event) {
            $currentEvents[$event->eventCode] = Yii::t('monitor/events', $event->eventCode);
        }

        return $currentEvents;
    }

   	public static function getDayUniqueCount($type = 'other', $terminalId = null)
	{
        $day = date('z', time());
        $key = 'dayUniqCnt';

        if ($terminalId) {
            $key .= ':' . $terminalId;
        }

        $redis = Yii::$app->redis;
        $redisKey = RedisHelper::getKeyName($key . ':' . $type . ':' . $day);

        $value = (int) $redis->incr($redisKey);

        if ($value === 1) {
            /**
             * ключ ранее не существовал, поставить срок устаревания 2-е суток (с запасом в сутки)
             */
            $redis->expire($redisKey, 3600 * 48);
        }

        return $value;
	}

    public static function getTypeGroup($type)
    {
        if ($module = Yii::$app->registry->getTypeModule($type)) {
            return $module->serviceId;
        } else {
            return Document::TYPE_UNKNOWN;
        }
    }

    /**
     * Подсчет неудачных попыток импорта документов
     */
    public static function getNewImportErrorsCount()
    {
        $today = new DateTime();

        $todayFrom = $today;
        $todayFrom->setTime(0, 0, 0);
        $todayFromFormat = $todayFrom->format('Y-m-d H:i:s');

        $todayTo = $today;
        $todayTo->setTime(23, 59, 59);
        $todayToFormat = $todayTo->format('Y-m-d H:i:s');

        $queryErrors = ImportError::find()
                        ->andWhere(['between', 'dateCreate', $todayFromFormat, $todayToFormat])
                        ->count();

        return $queryErrors;
    }

    /**
     * Получение статусов документа с учетом нужных ext-моделей
     */
    public static function getStatusLabelsAll()
    {
        $statusLabels = array_merge(Document::getStatusLabels(), SwiftFinDocumentExt::getStatusLabels());
        asort($statusLabels);

        return $statusLabels;
    }

    /**
     * Получение статуса документа или статуса ext-model,
     * если документ в статусе "Обрабатывается сервисом"
     */
    public static function getStatusLabel($document)
    {
        $status = [
            'name' => $document->status,
            'label' => $document->getStatusLabel()
        ];

        if ($document->status == Document::STATUS_SERVICE_PROCESSING) {
            $extModel = $document->extModel;
            if ($extModel && property_exists($extModel, 'extStatus')) {
                $extStatus = $extModel->extStatus;

                $status['name'] = $extStatus;
                $status['label'] = $extModel->getStatusLabel();
            }
        }

        return $status;
    }

    /**
     * Список бизнес-статусов
     */
    public static function getBusinessStatusesList()
    {
        return [
            'RCVD' => Yii::t('edm', 'Received'),
            'ACTC' => Yii::t('edm', 'Completely accepted'),
            'ACCP' => Yii::t('edm', 'Accepted for processing'),
            'RJCT' => Yii::t('edm', 'Rejected'),
            'ACSP' => Yii::t('edm', 'Accepted'),
            'ACSC' => Yii::t('edm', 'Processed'),
            'PDNG' => Yii::t('edm', 'Pending'),
            'PART' => Yii::t('edm', 'Partially'),
            'PRJT' => Yii::t('edm', 'Partially rejected'),
        ];
    }

    public static function getBusinessStatusesListWithPartially()
    {
        return [
            'RCVD' => Yii::t('edm', 'Received'),
            'ACTC' => Yii::t('edm', 'Completely accepted'),
            'ACCP' => Yii::t('edm', 'Accepted for processing'),
            'RJCT' => Yii::t('edm', 'Rejected'),
            'ACSP' => Yii::t('edm', 'Accepted'),
            'ACSC' => Yii::t('edm', 'Processed'),
            'PDNG' => Yii::t('edm', 'Pending'),
            'PART' => Yii::t('edm', 'Partially'),
            'PRJT' => Yii::t('edm', 'Partially rejected'),
            'PACP' => Yii::t('edm', 'Partially accepted'),
            'PPNG' => Yii::t('edm', 'Partially pending')
        ];
    }

    public static function waitForDocumentsToLeaveStatus($documentsIds, $status, $checkInterval = 0.3, $timeout = 15)
    {
        $startTime = time();

        while (time() <= $startTime + $timeout) {
            $documentsCount = Document::find()
                ->where(['in', 'id', $documentsIds])
                ->andWhere(['status' => $status])
                ->count();

            if ($documentsCount == 0) {
                return true;
            }

            usleep($checkInterval * 1000000);
        }

        return false;
    }

    public static function createAndSendDocument(BaseType $typeModel, $senderId, $receiverId, $uuidReference = null)
    {
        $terminal = Terminal::find()->where(['terminalId' => $senderId])->one();

        $context = static::createDocumentContext(
            $typeModel,
            [
                'type'               => $typeModel->getType(),
                'direction'          => Document::DIRECTION_OUT,
                'origin'             => Document::ORIGIN_SERVICE,
                'terminalId'         => $terminal->id,
                'sender'             => $terminal->terminalId,
                'receiver'           => $receiverId,
                'signaturesRequired' => 0,
                'uuidReference'      => $uuidReference,
            ]
        );

        if ($context === false) {
            Yii::warning('Failed to create document context');

            return false;
        }

        return DocumentTransportHelper::createSendingState($context['document']);
    }

}
