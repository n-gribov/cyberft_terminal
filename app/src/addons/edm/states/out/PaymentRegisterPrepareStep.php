<?php

namespace addons\edm\states\out;

use addons\edm\helpers\EdmHelper;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\PaymentRegister\PaymentRegisterWizardForm;
use common\document\Document;
use common\helpers\FileHelper;
use common\models\ImportError;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\states\BaseDocumentStep;
use Yii;
use yii\helpers\ArrayHelper;

class PaymentRegisterPrepareStep extends BaseDocumentStep
{
    public $name = 'prepare';

    public function run()
    {
        $model = $this->state->model;

        $paymentOrders = [];

        $terminal = Terminal::findOne(['terminalId' =>  $this->state->sender]);
        if (empty($terminal)) {
            $this->log('Could not find terminal');

            return false;
        }


        $importErrors = [];

        foreach ($model as $po) {
            if (!$po->validate()) {
                $errors = [];
                foreach($po->errors as $field => $messages) {
                    $errors[] = $field . ': ' . join('; ', $messages);
                }

                $errorText = join(PHP_EOL, $errors);

                $this->log('PaymentOrder model is not valid: ' . $errorText);

                $importErrors[] = [$po->number, Yii::t('edm', 'Error in document {num}', ['num' => $po->number]) . ': ' . $errorText];

                continue;
            }

            if (EdmHelper::checkPaymentOrderDuplicate($po) !== false) {
                $importErrors[] = [$po->number, Yii::t('edm', 'Failed to create document {num} - number was used before', ['num' => $po->number])];

                continue;
            }
        }

        if (!empty($importErrors)) {

            foreach ($importErrors as $error) {
                list($documentNumber, $errorMessage) = $error;
                ImportError::createError([
                    'type'                  => ImportError::TYPE_EDM,
                    'filename'              => FileHelper::mb_basename($this->state->filePath),
                    'errorDescriptionData'  => [
                        'text' => $errorMessage
                    ],
                    'documentTypeGroup'     => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                    'documentCurrency'      => 'RUB',
                    'documentNumber'        => $documentNumber,
                    'senderTerminalAddress' => $this->state->sender,
                ]);
                $this->state->addApiImportError($errorMessage);
            }

            // Зарегистрировать событие некорректного документа в модуле мониторинга
            Yii::$app->monitoring->log('edm:InvalidDocument', null, null, [
                'docPath' => $this->state->filePath
            ]);

            return false;
        }

        foreach($model as $po) {
            $paymentOrder = new PaymentRegisterPaymentOrder();
            $paymentOrder->loadFromTypeModel($po);
            $paymentOrder->terminalId = $terminal->id;
            if (!$paymentOrder->save()) {
                $this->log('Failed to save payment order: ' . print_r($paymentOrder->errors, true));

                // Запись в журнал ошибок импорта
                ImportError::createError([
                    'type' => ImportError::TYPE_EDM,
                    'filename' => FileHelper::mb_basename($this->state->filePath),
                    'errorDescriptionData' => [
                        'text' => 'Failed to create payment order'
                    ],
                    'documentTypeGroup'     => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                    'documentCurrency'      => $paymentOrder->currency,
                    'documentNumber'        => $paymentOrder->number,
                    'senderTerminalAddress' => $this->state->sender,
                ]);

                return false;
            }

            $this->log('PaymentOrder ' . $paymentOrder->number . ' saved with id ' . $paymentOrder->id);
            $paymentOrders[] = $paymentOrder;
        }

        // Cоздание реестра платежных поручений

        $form = new PaymentRegisterWizardForm();
        $form->sender = $this->state->sender; // Yii::$app->exchange->defaultTerminalId;
        $form->recipient = '';

        $form->addPaymentOrders($paymentOrders);

        $account = EdmPayerAccount::find()->with(['bank'])->where(['number' => $form->account])->one();

        if ($account) {
            if (isset($account->bank) && !empty($account->bank->terminalId)) {
                $form->recipient = $account->bank->terminalId;
            } else {
                $form->addError('account', Yii::t('edm', 'Account does not have bank terminal id'));
            }
        } else {
            $form->addError('account', Yii::t('edm', 'Account not found'));
        }

        if ($form->hasErrors() || !$form->validate()) {
            $this->log(Yii::t('edm', 'Error creating payment register. {error}', ['error' => $form->getErrorsSummary()]));

            $errorSummary = $form->getErrorsSummary();
            
            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type'                  => ImportError::TYPE_EDM,
                'filename'              => FileHelper::mb_basename($this->state->filePath),
                'errorDescriptionData'  => [
                    'text'   => 'Failed to create payment register: {error}',
                    'params' => [
                        'error' => $errorSummary
                    ]
                ],
                'documentTypeGroup'     => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                'documentCurrency'      => 'RUB',
                'documentNumber'        => null,
                'senderTerminalAddress' => $this->state->sender,
            ]);

            $this->state->addApiImportError($errorSummary);

            PaymentRegisterPaymentOrder::deleteAll(['id' => ArrayHelper::getColumn($paymentOrders, 'id')]);

            return false;
        }

        // Атрибуты документа
        $docAttributes = ['origin' => Document::ORIGIN_FILE];
        if ($this->state->apiUuid) {
            $docAttributes['uuid'] = $this->state->apiUuid;
        }
        $document = EdmHelper::createPaymentRegister($form, $docAttributes);
        // Отправить документ на обработку в транспортном уровне
        DocumentTransportHelper::processDocument($document);

        /**
         * Документ создан и при необходимости отправлен, поэтому стейт останавливаем
         */
        $this->state->stop();

        return true;
    }

}