<?php

namespace addons\ISO20022\states\out;

use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterType;
use addons\ISO20022\helpers\ISO20022Helper;
use common\helpers\FileHelper;
use common\helpers\Uuid;
use common\models\ImportError;
use common\states\BaseDocumentStep;
use Yii;

class PaymentOrderConvertStep extends BaseDocumentStep
{
    public $name = '1c2pain001';
    private $_errorData = [];

    public function run()
    {
        if (!$this->state->module) {
            $this->state->module = Yii::$app->getModule('ISO20022');
        }

        $filePath = $this->state->filePath;
        $this->log('Registering file ' . $filePath);

        $fileContent = file_get_contents($filePath);

        // Различное формирование xml для платежного поручения и реестра платежных поручений

        if ($this->state->initialType == PaymentOrderType::TYPE) {
            $painXml = $this->createPain001FromPaymentOrder($fileContent);
        } else if ($this->state->initialType == PaymentRegisterType::TYPE) {
            $painXml = $this->createPain001FromPaymentRegister($fileContent);
        } else {
            $errorText = 'State error - type is not supported by this step: ' . $this->state->initialType;
            $this->logError($errorText);
            $this->_errorData[] = ['text' => $errorText];

            return false;
        }

        // если не определен отправитель
        if (empty($this->state->sender)) {
            $this->_errorData[] = ['text' => 'Error in payer field'];
            $this->logError('1C to pain.001 import error. Payer Account is not found');

            return false;
        }

        // если не определен получатель
        if (empty($this->state->receiver)) {
            $this->_errorData[] = ['text' => 'Error in payer bik field'];
            $this->logError('1C to pain.001 import error. Bank Terminal id not found');

            return false;
        }

        if (!$painXml) {
            $this->logError('1C to pain.001 import error. Conversion from 1C format failed');

            return false;
        }

        // Сгенерировать новое имя файла pain.001 - для включения в свойства документа,
        // чтение/запись в него не производятся, т.к. все данные уже прочитаны,
        // а рабочий файл будет удален

        $this->state->originalFilename = Uuid::generate() . '.xml';
        $this->state->xmlContent = $painXml;

        return true;
    }

    public function cleanup()
    {
        if (is_file($this->state->filePath)) {
            unlink($this->state->filePath);
        }
    }

    public function onFail()
    {
        $filePath = $this->state->filePath;

        if (is_file($filePath)) {
            $errorResource = Yii::$app->registry->getImportResource(
                    $this->state->module->getServiceId(), 'error'
            );

            if (!$errorResource) {
                $this->log('Error resource not configured, file not moved');

                return;
            }

            $errorResource->copyFile($filePath, empty($this->state->receiver) ? null : $this->state->receiver);
        }

        foreach($this->_errorData as $error) {
            ImportError::createError([
                'type'                  => ImportError::TYPE_ISO20022,
                'filename'              => FileHelper::mb_basename($filePath),
                'errorDescriptionData'  => $error,
                'documentType'          => $this->state->initialType,
                'senderTerminalAddress' => $this->state->sender,
            ]);
        }
        
    }

    private function createPain001FromPaymentOrder($fileContent)
    {
        $paymentOrder = new PaymentOrderType();
        $paymentOrder->loadFromString($fileContent, false);

        // Ошибка формирования paymentorder
        if ($paymentOrder->errors) {

            $this->logError('1C to pain.001 import error. Invalid 1c file');

            if (isset($paymentOrder->errors['body'])) {
                $errors = implode(',', $paymentOrder->errors['body']);
                $this->_errorData[] = [
                    'text' => 'Invalid 1c file: {errors}',
                    'params' => ['errors' => $errors]
                ];
            } else {
                $this->_errorData[] = ['text' => 'Invalid 1c file'];
            }

            return false;
        }

        try {
            $this->state->sender = $paymentOrder->sender;
            $this->state->receiver = $paymentOrder->recipient;
            $painXml = ISO20022Helper::createPain001XmlFromPaymentOrder($paymentOrder);
        } catch (Exception $ex) {
            $this->_errorData[] = ['text' => 'Failed to create pain.001 document from payment order'];
            $this->logError('1C to pain.001 import error. Pain.001 creating failed: ' . $ex->getMessage());

            return false;
        }

        return $painXml;
    }

    private function createPain001FromPaymentRegister($fileContent)
    {
        try {
            $painData = ISO20022Helper::createPain001From1cPaymentRegister($fileContent, 'windows-1251');
            $painXml = $painData['xml'];
            $this->state->sender = $painData['sender'];
            $this->state->receiver = $painData['receiver'];
        } catch (Exception $ex) {
            $this->_errorData[] = ['text' => 'Failed to create pain.001 document from payment register'];
            $this->logError('1C to pain.001 import error. Pain.001 creating failed: ' . $ex->getMessage());

            return false;
        }

        return $painXml;
    }

}