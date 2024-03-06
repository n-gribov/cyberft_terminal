<?php

namespace addons\edm\states\out;

use addons\edm\helpers\FormatDetector1C;
use addons\edm\helpers\FormatDetectorAuth024;
use addons\edm\helpers\FormatDetectorAuth025;
use addons\edm\helpers\FormatDetectorIBank;
use addons\edm\helpers\FormatDetectorPain001;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\IBank\IBankDocumentsPack;
use addons\edm\models\Pain001Fcy\Pain001FcyType;
use addons\edm\models\Pain001Fx\Pain001FxType;
use addons\edm\models\Pain001Rls\Pain001RlsType;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterType;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\Auth025Type;
use common\helpers\FileHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\ImportError;
use common\modules\transport\helpers\FormatDetectorCyberXml;
use common\states\BaseDocumentStep;
use Exception;
use Yii;

class DetectFormatStep extends BaseDocumentStep
{
    private $_formatDetectorList = [
        FormatDetector1C::class,
        FormatDetectorPain001::class,
        FormatDetectorAuth024::class,
        FormatDetectorAuth025::class,
        FormatDetectorIBank::class,
        FormatDetectorCyberXml::class,
    ];

    public $name = 'detect';

    public function run()
    {
        $this->state->module = Yii::$app->getModule('edm');
        $filePath = $this->state->documentFilePath;
        $this->log('Detecting file format for ' . $filePath);

        foreach ($this->_formatDetectorList as $formatDetector) {
            try {
                $model = $formatDetector::detect($filePath);

                $this->state->model = $model;
                if (
                    $model instanceof CyberXmlDocument
                    && $model->validateXSD()
                ) {
                    $this->state->format = 'CyberXML';

                    $this->state->sender = $model->senderId;
                    $this->state->cyxDoc = $model;

                    $typeModel = $model->getContent()->getTypeModel();

                    $this->state->typeModel = $typeModel;

                    break;
                } else if (is_array($model)) {
                    $this->state->format = 'PaymentRegister';
                    $this->state->sender = $model[0]->sender;
                    if (!$this->validatePaymentRegisterSender($model[0])) {
                        return false;
                    }

                    break;
                } else if ($model instanceof IBankDocumentsPack && $model->getVersion() === 1) {
                    $this->state->format = $this->isIBankCurrencyPaymentsRegister($model)
                        ? 'iBankCurrencyPaymentsRegister'
                        : 'iBankV1';
                    $this->state->sender = Yii::$app->exchange->address;

                    break;
                } else if ($model instanceof IBankDocumentsPack && $model->getVersion() === 2) {
                    $this->state->format = 'iBankV2';
                    $this->state->sender = Yii::$app->exchange->address;

                    break;
                } else if ($model instanceof Pain001FcyType) {
                    $this->state->format = Pain001FcyType::TYPE;
                    $this->state->sender = $model->sender;

                    break;
                } else if ($model instanceof Auth024Type) {
                    $this->state->format = Auth024Type::TYPE;
                    $this->state->sender = $model->sender;

                    break;
                } else if ($model instanceof Auth025Type) {
                    $this->state->format = Auth025Type::TYPE;
                    $this->state->sender = $model->sender;

                    break;
                } else if ($model instanceof Pain001FxType) {
                    $this->state->format = Pain001FxType::TYPE;
                    $this->state->sender = $model->sender;

                    break;
                } else if ($model instanceof Pain001RlsType) {
                    $this->state->format = Pain001RlsType::TYPE;
                    $this->state->sender = $model->sender;

                    break;
                }
            } catch (Exception $ex) {
                $this->log('Exception while or after using ' . $formatDetector . ' on file '
                        . $filePath . ': ' . $ex->getMessage());
            }
        }

        // all format detectors checked and no result? lol file is invalid
        if (!$this->state->format) {
            $this->log('File ' . $filePath . ' was not recognized as valid.');
            $errorResource = Yii::$app->registry->getImportResource($this->state->module->serviceId, 'error');
            if (empty($errorResource)) {
                $this->log('Import error resourse is not configured, invalid file will not be moved');
            } else {
                $errorResource->putFile($filePath, $filePath);
            }

            return false;
        }

        return true;
    }

    private function isIBankCurrencyPaymentsRegister(IBankDocumentsPack $ibankDocumentsPack): bool
    {
        if ($ibankDocumentsPack->count() <= 1) {
            return false;
        }

        foreach ($ibankDocumentsPack as $ibankDocument) {
            /** @var IBankDocument $ibankDocument */
            if ($ibankDocument->getType() !== 'PayDocCurr') {
                return false;
            }

            return true;
        }
    }

    private function validatePaymentRegisterSender(PaymentOrderType $paymentOrder): bool
    {
        if ($paymentOrder->sender) {
            return true;
        }
        $accountExists = EdmPayerAccount::find()
            ->where(['number' => $paymentOrder->payerCheckingAccount])
            ->exists();
        if (!$accountExists) {
            $errorMessage = Yii::t('edm', 'Payer account {number} is not found', ['number' => $paymentOrder->payerCheckingAccount]);
            $this->log($errorMessage);
            ImportError::createError([
                'type'                  => ImportError::TYPE_EDM,
                'filename'              => FileHelper::mb_basename($this->state->filePath),
                'errorDescriptionData'  => [
                    'text' => $errorMessage
                ],
                'documentType'          => PaymentRegisterType::TYPE,
                'documentTypeGroup'     => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                'documentCurrency'      => 'RUB',
                'documentNumber'        => null,
                'senderTerminalAddress' => $this->state->sender,
            ]);
            $this->state->addApiImportError($errorMessage);
        }
        return false;
    }

}