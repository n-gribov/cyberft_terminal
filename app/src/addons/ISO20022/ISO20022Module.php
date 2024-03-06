<?php
namespace addons\ISO20022;

use addons\edm\EdmModule;
use addons\ISO20022\models\Auth018Type;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\Auth025Type;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use addons\ISO20022\models\ISO20022DocumentExt;
use addons\ISO20022\models\ISO20022Search;
use addons\ISO20022\models\Pain002Type;
use addons\ISO20022\models\Pain001Type;
use addons\ISO20022\models\Auth027Type;
use common\base\BaseBlock;
use common\document\Document;
use common\helpers\CryptoProHelper;
use common\helpers\FileHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\monitor\models\MonitorLogAR;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Psr\Log\LogLevel;
use Yii;

class ISO20022Module extends BaseBlock
{
    const EXT_USER_MODEL_CLASS = '\addons\ISO20022\models\ISO20022UserExt';
    const SERVICE_ID = 'ISO20022';
    const RESOURCE_IN = 'in';
    const RESOURCE_OUT = 'out';
    const RESOURCE_TEMP = 'temp';
    //const RESOURCE_IMPORT = 'in';
    const RESOURCE_IMPORT_ERROR = 'error';

    const SETTINGS_CODE = 'ISO20022:ISO20022';

    public function registerMessage(CyberXmlDocument $cyxDoc, $documentId)
	{
        $typeModel = $cyxDoc->getContent()->getTypeModel();

        $class = Yii::$app->registry->getTypeExtModelClass($cyxDoc->docType);
        $extModel = $class::findOne(['documentId' => $documentId]);

        if (!$extModel) {
            throw new Exception('DocumentExt #' . $documentId . ' not found!');
        }

        $extModel->extStatus = ISO20022DocumentExt::STATUS_CRYPTOPRO_VERIFICATION;
        $extModel->save(false, ['extStatus']);
        $filePath = Yii::getAlias('@temp/') . FileHelper::uniqueName();

        $content = (string) $typeModel;

        file_put_contents($filePath, $content);
        $verify = CryptoProHelper::verify(static::SERVICE_ID, $filePath, $cyxDoc->senderId, $cyxDoc->receiverId);

        unlink($filePath);

        if ($verify === false) {
            $this->log('Document id ' . $documentId . ' failed CryptoPro verification');

            $params = [
                'logLevel' => LogLevel::ERROR,
                'previousStatus' => $extModel->extStatus,
                'status' => $extModel->extStatus
            ];

            $document = Document::findOne($documentId);

            if ($document) {
                $params['terminalId'] = $document->terminalId;
            }

            // Зарегистрировать событие ошибки обработки документа в модуле мониторинга
            Yii::$app->monitoring->log(
                'document:documentProcessError', 'document', $documentId, $params
            );

            $extModel->extStatus = ISO20022DocumentExt::STATUS_CRYPTOPRO_VERIFICATION_FAILED;
            $extModel->save(false, ['extStatus']);

            return false;
        }

        $extModel->extStatus = ISO20022DocumentExt::STATUS_CRYPTOPRO_VERIFICATION_SUCCESS;
        $extModel->save(false, ['extStatus']);
        $type = $typeModel->getType();

        if ($type == Auth026Type::TYPE
            || $type == Auth024Type::TYPE
            || $type == Auth025Type::TYPE
            || $type == Auth018Type::TYPE
        ) {
            $this->processAttachments($typeModel, $extModel);
        } else if ($type == Auth027Type::TYPE) {
            $this->processAuth027($typeModel, $extModel, $documentId);
        } else if (
            $type == Camt053Type::TYPE
            || $type == Camt054Type::TYPE
        ) {
            $this->processCamt($typeModel, $extModel);
        } else if ($type == Pain001Type::TYPE) {
            $this->processPain001($typeModel, $extModel);
        } else if ($type == Pain002Type::TYPE) {
            $this->processPain002($typeModel, $extModel, $documentId);
        } else {
            $this->processOther($typeModel, $extModel);
        }

        Yii::$app->resque->enqueue('\addons\ISO20022\jobs\ExportJob', ['documentId' => $documentId]);

		return true;
	}

    public function onDocumentStatusChange(Document $document)
    {
//        $extModel = $document->extModel;
//        if (
//            $extModel
//            && ($document->type == Auth024Type::TYPE)
//            && $document->status == Document::STATUS_DELIVERED
//            && $extModel->fileName && !$extModel->storedFileId
//        ) {
//            if ($extModel->extStatus != ISO20022DocumentExt::STATUS_AWAITING_ATTACHMENT) {
//                $document->status = Document::STATUS_SERVICE_PROCESSING;
//                $extModel->extStatus = ISO20022DocumentExt::STATUS_AWAITING_ATTACHMENT;
//                $extModel->save(false);
//
//                return;
//            } else {
//                // Если документ доставлен, то extStatus должен быть пустым
//                // Иначе продолжает быть в ожидании доставки вложения
//                $extModel->extStatus = '';
//                $extModel->save(false);
//            }
//        }

        if ($document->direction == Document::DIRECTION_IN
                && $document->status == Document::STATUS_ATTACHMENT_UNDELIVERED
        ) {
            try {
                // Отправить Status Report
                DocumentTransportHelper::statusReport($document, [
                    'statusCode' => 'ATDE',
                    'errorCode' => '555',
                    'errorDescription' => 'Error: Attachment not delivered'
                ]);
            } catch (Exception $ex) {
                $this->log('Error onDocumentStatusChange: ' . $ex->getMessage());
            }
        }

        parent::onDocumentStatusChange($document);
    }

	public function getDocument($id)
	{
		return ISO20022Search::findOne($id);
	}

    public function storeFileOut($path, $filename = '')
    {
        return $this->storeFile($path, self::RESOURCE_OUT, $filename);
    }

    public function storeFileIn($path, $filename = '')
    {
        return $this->storeFile($path, self::RESOURCE_IN, $filename);
    }

    public function storeDataOut($data, $filename = '')
    {
        return $this->storeData($data, self::RESOURCE_OUT, $filename);
    }

    // изолировать подписание ISO20022, CYB-3739
  	public function isSignatureRequired($origin, $terminalId = null)
	{
    	return false;
	}

    public function isAutoSignatureRequired($origin, $terminalId = null)
    {
        return false;
    }

    /**
     * @param $typeModel
     * @param $extModel
     * @param $documentId
     */
    private function processPain002($typeModel, $extModel, $documentId)
    {
        $extModel->msgId = $typeModel->msgId;
        $extModel->originalFilename = $typeModel->originalFilename;
        $extModel->save(false);

        try {
            /** @var EdmModule $edmModule */
            $edmModule = Yii::$app->getModule(EdmModule::SERVICE_ID);
            $edmModule->processPain002($typeModel, $documentId);
        } catch (\Exception $exception) {
            $this->log("Failed to process pain.002 in EDM module, caused by: $exception");
        }

        // Поиск в ExtModel документа по его message id и запись туда нужной информации
        $docExt = ISO20022DocumentExt::find()->where(['msgId' => $typeModel->originalMsgId])->one();

        if (!$docExt) {
            return null;
        }

        // Получаем оригинальный документ, к которому относится pain.002, чтобы получить его uuid
        $extDocId = $docExt->documentId;
        $document = Document::findOne($extDocId);

        // Бизнес статус и описание ошибки заполняем, только если пришли какие-то значения
        $statusCode = $typeModel->getStatusCodeByType($document->type);
        $errorDescription = $typeModel->getErrorDescriptionByType($document->type);

        if ($statusCode) {
            $docExt->statusCode = $statusCode;
        }

        if ($errorDescription) {
            $docExt->errorDescription = $errorDescription;
        }

        $docExt->errorCode = $typeModel->errorCode;
        $docExt->save(false);

        $extDocUuid = $document->uuid;

        // Заносим uuid оригинального документа связи с pain.002
        $documentPain002 = Document::findOne($documentId);
        $documentPain002->uuidReference = $extDocUuid;
        $documentPain002->save(false);

        // Зарегистрировать событие получения статуса документа ISO в модуле мониторинга
        Yii::$app->monitoring->log(
            'user:ISOReceiveStatus',
            'document',
            $extDocId,
            [
                'initiatorType' => MonitorLogAR::INITIATOR_TYPE_SYSTEM,
                'msgId' => $typeModel->originalMsgId,
                'status' => $typeModel->getStatusCodeByType($document->type),
                'reason' => $errorDescription,
                'terminalId' => $document->terminalId
            ]
        );
    }

    /**
     * @param $typeModel
     * @param $extModel
     * @param $documentId
     */
    private function processAuth027(Auth027Type $typeModel, $extModel, $documentId)
    {
        $documentAuth027 = Document::findOne($documentId);

        try {
            /** @var EdmModule $edmModule */
            $edmModule = Yii::$app->getModule(EdmModule::SERVICE_ID);
            $edmModule->processAuth027($documentAuth027, $typeModel);
        } catch (\Exception $exception) {
            $this->log("Failed to process auth.027 in EDM module, caused by: $exception");
        }

        $extModel->msgId = $typeModel->msgId;
        $extModel->originalFilename = $typeModel->originalFilename;
        $extModel->save(false);

        $originalMsgId = $typeModel->originalMsgId;

        $docExt = ISO20022DocumentExt::find()
            ->innerJoin('document', 'document.id = documentId')
            ->where([
                'msgId'              => $originalMsgId,
                'document.direction' => Document::DIRECTION_OUT,
                'document.sender'    => $documentAuth027->receiver,
                'document.receiver'  => $documentAuth027->sender,
            ])
            ->one();

        if (!$docExt) {
            return null;
        }

        // Получаем оригинальный документ, к которому относится pain.002, чтобы получить его uuid
        $extDocId = $docExt->documentId;
        $document = Document::findOne($extDocId);

        // Бизнес статус и описание ошибки заполняем, только если пришли какие-то значения
        $statusCode = $typeModel->statusCode;
        $errorDescription = $typeModel->errorDescription;

        if ($statusCode) {
            $docExt->statusCode = $statusCode;
        }

        if ($errorDescription) {
            $docExt->errorDescription = $errorDescription;
        }

        $docExt->save(false);

        $extDocUuid = $document->uuid;

        // Заносим uuid оригинального документа связи с pain.002
        $documentAuth027->uuidReference = $extDocUuid;
        $documentAuth027->save(false);

        // Зарегистрировать событие получения статуса документа ISO в модуле мониторинга
        Yii::$app->monitoring->log(
            'user:ISOReceiveStatus',
            'document',
            $extDocId,
            [
                'initiatorType' => MonitorLogAR::INITIATOR_TYPE_SYSTEM,
                'msgId' => $originalMsgId,
                'status' => $statusCode,
                'reason' => $errorDescription
            ]
        );
    }

    /**
     * @param $typeModel
     * @param $extModel
     */
    private function processAttachments($typeModel, $extModel)
    {
        $extModel->subject = $typeModel->subject;
        $extModel->descr = $typeModel->descr;
        $extModel->typeCode = $typeModel->typeCode;

        if ($typeModel->getType() !== Auth026Type::TYPE){
            $fileName = $typeModel->fileName;
            $extModel->mmbId = $typeModel->mmbId;
        } else {
            $fileName = reset($typeModel->fileNames);
        }

        if ($fileName) {
            // Если модель использует сжатие в zip
            if ($typeModel->useZipContent) {
                // если используется единый конверт
                $fileName = $typeModel->zipFilename;
            } else if ($typeModel instanceof Auth026Type && !empty($typeModel->embeddedAttachments)) {
                
            } else {
                $extModel->extStatus = ISO20022DocumentExt::STATUS_AWAITING_ATTACHMENT;
            }
        }

        if (mb_substr($fileName, 0, 7) == 'attach_') {
            $fileName = mb_substr($fileName, 7);
        }

        $extModel->fileName = $fileName;
        $extModel->msgId = $typeModel->msgId;

        $extModel->save(false);
    }

    /**
     * @param $typeModel
     * @param $extModel
     */
    private function processCamt($typeModel, $extModel)
    {
        $extModel->msgId = $typeModel->msgId;
        $extModel->accountNumber = $typeModel->account;
        $extModel->periodStart = $typeModel->periodBegin;
        $extModel->periodEnd = $typeModel->periodEnd;
        $extModel->originalFilename = $typeModel->originalFilename;
        $extModel->save(false);
    }

    /**
     * @param $typeModel
     * @param $extModel
     */
    private function processPain001($typeModel, $extModel)
    {
        $extModel->msgId = $typeModel->msgId;
        $extModel->count = $typeModel->count;
        $extModel->sum = $typeModel->sum;
        $extModel->currency = $typeModel->currency;
        $extModel->originalFilename = $typeModel->originalFilename;
        $extModel->save(false);
    }

    /**
     * @param $typeModel
     * @param $extModel
     */
    private function processOther($typeModel, $extModel)
    {
        $extModel->msgId = $typeModel->msgId;
        $extModel->save(false);
    }

    public function getName(): string
    {
        return Yii::t('app', 'ISO20022');
    }
}
