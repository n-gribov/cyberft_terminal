<?php
namespace addons\ISO20022\jobs;

use addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuType;
use common\base\RegularJob;
use common\components\Resque;
use common\components\storage\Resource;
use common\components\TerminalId;
use common\helpers\Address;
use common\helpers\FileHelper;
use common\helpers\sbbol\SBBOLHelper;
use common\helpers\TerminalAddressResolver;
use common\models\Terminal;
use common\models\ImportError;
use common\helpers\DocumentHelper;
use common\document\Document;
use common\modules\transport\helpers\DocumentTransportHelper;
use addons\edm\helpers\EdmHelper;
use addons\edm\helpers\FormatDetector1C;
use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\Pain001Type;
use addons\ISO20022\models\ISO20022Type;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\EdmPayerAccount;
use Exception;
use Resque_Job_DontPerform;

use Yii;

class ImportJob extends RegularJob
{
    private $_module;
    private $_terminalId;
    private $_importResource;
    private $_importResource1C;
    private $_importResourceIBANK;
    private $_importResourceFreeFormat;
    private $_errorResource;
    private $_jobResource;

    public function setUp()
    {
        try {
            $this->_logCategory = 'ISO20022';

            parent::setUp();

            $serviceId = ISO20022Module::SERVICE_ID;

            $this->_terminalId = Yii::$app->terminals->address;
            $this->_module = Yii::$app->getModule($serviceId);
            $this->_importResource = Yii::$app->registry->getImportResource($serviceId);
            $this->_importResource1C = Yii::$app->registry->getImportResource($serviceId, '1c');
            $this->_importResourceIBANK = Yii::$app->registry->getImportResource($serviceId, 'IBANK');
            $this->_importResourceFreeFormat = Yii::$app->registry->getImportResource($serviceId, 'freeformat');
            $this->_errorResource = Yii::$app->registry->getImportResource($serviceId, 'error');
            $this->_jobResource = Yii::$app->registry->getImportResource($serviceId, 'job');
        } catch(Exception $ex) {
            $this->log($ex->getMessage(), true);

            throw new Resque_Job_DontPerform('Unable to continue');
        }

        if (empty($this->_module)) {
            throw new Resque_Job_DontPerform('ISO20022 module not found');
        } else if (
            empty($this->_importResource)
            || empty($this->_jobResource)
            || empty($this->_importResource1C)
            || empty($this->_importResourceIBANK)
            || empty($this->_importResourceFreeFormat)
        ) {
            Yii::info('ISO20022 resource configuration error');

            throw new Resque_Job_DontPerform('ISO20022 resource configuration error');
        }
    }

	public function perform()
	{
	    $this->log('Importing ISO20022 data', false, 'regular-jobs');

        try {
            if ($this->_module->settings->importSearchSenderReceiver == true) {
                $fileList = $this->getFiles($this->_importResource);
            } else {
                $fileList = $this->getDirs($this->_importResource);
            }

            // 1C
            $import1c = $this->get1cFiles($this->_importResource1C);
            // IBANK
            $importIBank = $this->getDirs($this->_importResourceIBANK);
            // Free format
            $importFreeFormat = $this->getFiles($this->_importResourceFreeFormat, 'freeformat');

            $fileList = array_merge($fileList, $import1c, $importIBank, $importFreeFormat);
        } catch (Exception $ex) {
            $this->log($ex->getMessage(), true);

            return;
        }

        $count = 0;
        foreach ($fileList as $fileInfo) {
            /**
             * Перемещаем файл с возможно настроенного SFTP-ресурса на локальный ресурс
             * (если локальный ресурс находится в общем семействе SFTP-ресурсов,
             * то в конфигах он должен иметь атрибут ignoreSftp = true)
             * Если при перемещении возникла ошибка или эксепшн, то скорее всего не работает SFTP.
             * В этом случае просто скипаем файл, чтобы он обработался в следующий раз.
             */
            try {
                $content = file_get_contents($fileInfo['file']);
            } catch (Exception $ex) {
                $this->log('Exception while moving file ' . $fileInfo['file'] . ': ' . $ex->getMessage());
                $content = null;
            }

            if (empty($content)) {
                $this->log('Could not read from file ' . $fileInfo['file']);

                continue;
            }

            $filePath = $this->_jobResource->getPath() . '/' . FileHelper::mb_basename($fileInfo['file']);
            $result = file_put_contents($filePath, $content);

            if (!$result) {
                $this->log('Could not write to file ' . $filePath);

                continue;
            }

            unlink($fileInfo['file']);
            /**
             * Файл перемещен, теперь он доступен локально и его можно ставить на обработку.
             * Все ошибки, которые с ним могут случиться, уже не будут связаны с SFTP
             * и поэтому его можно будет перемещать в error.
             */

            $painTypeModel = $this->checkForSberbank($filePath, $fileInfo);

            // Если это pain.001, предназначенный для Сбербанка, используем другой обработчик для документа
            if ($painTypeModel) {
                $this->importSberbank($filePath, $painTypeModel, $fileInfo);
            } else {

                $initialType = isset($fileInfo['type']) ? $fileInfo['type'] : null;
                if ($initialType == 'freeformat') {
                    $stateClass = 'addons\ISO20022\states\out\Auth026OutState';
                } else {
                    $stateClass = 'addons\ISO20022\states\out\ISO20022OutState';
                }

                Yii::$app->resque->enqueue(
                    'common\jobs\StateJob',
                    [
                        'stateClass' => $stateClass,
                        'params' => serialize([
                            'filePath' => $filePath,
                            'initialType' => $initialType,
                            'sender' => isset($fileInfo['sender']) ? $fileInfo['sender'] : null,
                            'receiver' => isset($fileInfo['receiver']) ? $fileInfo['receiver'] : null,
                        ])
                    ],
                    true,
                    Resque::OUTGOING_QUEUE
                );
            }

            $count++;

            if ($count > 99) {
                break;
            }
        }
	}

    private function checkForSberbank($file, $fileInfo)
    {
        if (substr($fileInfo['file'], -4) == '.zip') {
            return false;
        }

        $typeModel = ISO20022Type::getModelFromString($file, true, 'cp1251');

        // Только pain.001
        if (!$typeModel || $typeModel->type != Pain001Type::TYPE) {
            return false;
        }

        $sberbankTerminal = SBBOLHelper::getGatewayTerminalAddress();

        if (!$sberbankTerminal) {
            return false;
        }

        $result = false;

        if ($this->_module->settings->importSearchSenderReceiver) {
            if ($typeModel->searchReceiver() == Address::truncateAddress($sberbankTerminal)) {
                $result = $typeModel;
            }
        } else {
            if (isset($fileInfo['receiver']) && $fileInfo['receiver'] == $sberbankTerminal) {
                $result = $typeModel;
            }
        }

        return $result;
    }

    private function getFiles(Resource $resource, $type = null)
    {
        $fileList = [];

        $files = $resource->getContents();
        foreach($files as $file) {
            if (is_dir($file)) {
                continue;
            }

            $file = ['file' => $file];
            if ($type) {
                $file['type'] = $type;
            }

            $fileList[] = $file;
        }

        return $fileList;
    }

    private function get1cFiles($resource)
    {
        $fileList = [];
        $files = $resource->getContents($resource->path);

        foreach ($files as $file) {
            $fileContent = file_get_contents($file);
            $type1c = FormatDetector1C::getTypeByDocHeaders($fileContent, 'cp1251');
            $fileList[] = ['type' => $type1c, 'file' => $file];
        }

        return $fileList;
    }

    private function importSberbank($file, Pain001Type $typeModel, $fileInfo)
    {
        if ($this->_module->settings->importSearchSenderReceiver) {
            $sender = $typeModel->searchSender();
            $sender = Address::fixSender($sender);

            $receiverSwiftCode = $typeModel->searchReceiver();
            $receiver = TerminalAddressResolver::resolveReceiver($receiverSwiftCode);
        } else {
            $sender = $fileInfo['sender'];
            $receiver = $fileInfo['receiver'];
        }

        if (empty($sender)) {
            Yii::error('Pain.001 => SberbankPaymentOrder failed. Empty sender.');
            $this->moveToInvalid($file);
            return false;
        }

        if(empty($receiver)) {
            Yii::error('Pain.001 => SberbankPaymentOrder failed. Empty receiver.');
            $this->moveToInvalid($file, $receiver);
            return false;
        }

        $xml = simplexml_load_file($file);

        try {
            $paymentOrders = EdmHelper::createSBBOLPayDocRuTypeModelsFromPain001Xml($xml);
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $this->moveToInvalid($file);
            return false;
        }

        foreach($paymentOrders as $paymentOrder) {
            $terminal = Terminal::findOne(['terminalId' => $sender]);

            if (empty($terminal)) {
                Yii::error('Pain.001 => SberbankPaymentOrder failed. Can\'t find sender terminal id.');
                $this->moveToInvalid($file, $receiver);
                return false;
            }

            $documents[] = [
                'sender' => $sender,
                'receiver' => $receiver,
                'terminalId' => $terminal->id,
                'typeModel' => $paymentOrder
            ];
        }

        foreach($documents as $document) {

            /** @var SBBOLPayDocRuType $documentTypeModel */
            $documentTypeModel = $document['typeModel'];

            if ($this->duplicatesberbankexists($documentTypeModel)) {
                Yii::error("Pain.001 => SberbankPaymentOrder failed. Duplicate message uuid {$documentTypeModel->docExtId}");

                Yii::$app->monitoring->log('ISO20022:DuplicateDocument', null, null, [
                    'docPath' => $fileInfo['file'],
                    'msgId' => $documentTypeModel->docExtId
                ]);

                // Запись в журнал ошибок импорта
                ImportError::createError([
                    'type'                  => ImportError::TYPE_ISO20022,
                    'identity'              => $documentTypeModel->docExtId,
                    'filename'              => FileHelper::mb_basename($fileInfo['file']),
                    'errorDescriptionData'  => [
                        'text' => 'Document already exists'
                    ],
                    'documentType'          => $typeModel->getType(),
                    'documentCurrency'      => 'RUB',
                    'documentNumber'        => @$documentTypeModel->request->getPayDocRu()->getAccDoc()->getAccDocNo(),
                    'senderTerminalAddress' => $sender,
                ]);

                $this->moveToInvalid($file, $receiver);
                return false;
            }

            $extAttributes = [
                'count' => 1,
                'sum' => $documentTypeModel->sum,
                'date' => $documentTypeModel->date,
                'accountNumber' => $documentTypeModel->payerAccount,
                'msgId' => $documentTypeModel->docExtId

            ];

            // Поиск счета по номеру
            $account = EdmPayerAccount::findOne(['number' => $documentTypeModel->payerAccount]);

            if ($account) {
                $extAttributes['accountId'] = $account->id;
                $extAttributes['orgId'] = $account->organizationId;

                $currency = $account->edmDictCurrencies;

                if ($currency) {
                    $extAttributes['currency'] = $currency->name;
                }
            } else {
                Yii::error("Pain.001 => SberbankPaymentOrder failed. Unknown sender account - {$documentTypeModel->payerAccount}");

                // Запись в журнал ошибок импорта
                ImportError::createError([
                    'type'                  => ImportError::TYPE_ISO20022,
                    'filename'              => FileHelper::mb_basename($fileInfo['file']),
                    'errorDescriptionData'  => [
                        'text'   => 'Unknown sender account {account}',
                        'params' => ['account' => $documentTypeModel->payerAccount]
                    ],
                    'documentType'          => $typeModel->getType(),
                    'documentCurrency'      => 'RUB',
                    'documentNumber'        => @$documentTypeModel->request->getPayDocRu()->getAccDoc()->getAccDocNo(),
                    'senderTerminalAddress' => $sender,
                ]);

                $this->moveToInvalid($file, $receiver);
                return false;
            }

            $result = DocumentHelper::createDocumentContext(
                $document['typeModel'],
                [
                    'type' => $documentTypeModel->getType(),
                    'direction' => Document::DIRECTION_OUT,
                    'origin' => Document::ORIGIN_WEB,
                    'terminalId' => $document['terminalId'],
                    'sender' => $document['sender'],
                    'receiver' => $document['receiver'],
                ],
                $extAttributes
            );

            if (!isset($result['document'])) {
                Yii::error('Pain.001 => SberbankPaymentOrder failed. Failed to create document.');
                $this->moveToInvalid($file, $receiver);
                return false;
            }

            DocumentTransportHelper::processDocument($result['document']);

            // Создание платежного поручения по SBBOLPayDocRu
            $paymentOrderType = PaymentOrderType::createFromSBBOLPayDocRu($document['typeModel']);
            $paymentOrderType->setDate(date('d.m.Y', strtotime($paymentOrderType->dateCreated)));

            $paymentOrder = new PaymentRegisterPaymentOrder();
            $paymentOrder->loadFromTypeModel($paymentOrderType);
            $paymentOrder->date = $paymentOrderType->dateCreated;
            $paymentOrder->registerId = $result['document']->id;
            $paymentOrder->terminalId = $document['terminalId'];
            $paymentOrder->save();
        }

        unlink($file);
    }

    private function getDirs(Resource $resource)
    {
        $fileList = [];

        $receivers = array_values($resource->getDirSubfolders($resource->path, false));
        foreach ($receivers as $receiverId) {
            if (!TerminalId::validate($receiverId)) {
                continue;
            }

            $files = $resource->getContents($resource->path . '/' . $receiverId);
            foreach ($files as $file) {
                $fileList[] = ['sender' => $this->_terminalId, 'receiver' => $receiverId, 'file' => $file];
            }
        }

        return $fileList;
    }

    private function duplicateSberbankExists(SBBOLPayDocRuType $typeModel)
    {
        $doc = Document::find()
            ->innerJoin(
                'documentExtEdmPaymentRegister ext',
                'ext.documentId = document.id'
            )
            ->where(
                [
                    'document.direction' => Document::DIRECTION_OUT,
                    'document.type' => $typeModel->type,
                    'ext.msgId' => $typeModel->request->getPayDocRu()->getDocExtId()
                ]
            )
            ->one();

        return !empty($doc);
    }

    protected function moveToInvalid($filePath, $dir = null)
    {
        try {
            if ($this->_module->settings->importSearchSenderReceiver) {
                $this->_errorResource->copyFile($filePath);
            } else {
                $this->_errorResource->copyFile($filePath, $dir);
            }
        } catch(Exception $ex) {
            $this->log("Error importing file {$filePath}: could not move");
            $this->log($ex->getMessage());
        }

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }

}