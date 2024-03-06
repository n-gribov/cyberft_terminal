<?php

namespace addons\edm\models\ForeignCurrencyOperation;

use yii\db\ActiveRecord;
use Yii;

/**
 * @property string $templateName
 * @property string $sum
 * @property string $currency
 * @property string $payerAccount
 * @property string $payerInn
 * @property string $payerName
 * @property string $payerAddress
 * @property string $payerLocation
 * @property string $payerBank
 * @property string $payerBankName
 * @property string $payerBankAddress
 * @property string $intermediaryBank
 * @property string $intermediaryBankAccount
 * @property string $intermediaryBankNameAndAddress
 * @property string $beneficiary
 * @property string $beneficiaryAccount
 * @property string $beneficiaryBank
 * @property string $beneficiaryBankAccount
 * @property string $beneficiaryBankNameAndAddress
 * @property string $information
 * @property string $commission
 * @property string $commissionSum
 * @property string $additionalInformation
 * @property integer $terminalId
 * @property string $immediatePayment
 * @property string $beneficiaryAddress
 * @property string $beneficiaryLocation
 * @property string $commissionAccount
 */
class ForeignCurrencyPaymentTemplate extends ActiveRecord
{
    public $payerAccountSelect;
    public $beneficiaryAccountSelect;

    public static function tableName()
    {
        return 'edm_ForeignCurrencyPaymentTemplates';
    }

    public function attributeLabels()
    {
        // Заголовки атрибутов полностью совпадают с базовым типом "Валютный платеж"
        return (new ForeignCurrencyPaymentType())->attributeLabels();
    }

    public function rules()
    {
        return [
            [
                [
                    'sum', 'payerAccount', 'payerInn',
                    'beneficiaryAccount', 'beneficiary',
                    'commission', 'terminalId', 'templateName'
                ], 'required'
            ],
            [
                'sum', 'double', 'min' => 1.00
            ],
            [
                [
                    'information', 'currency', 'beneficiaryAccountSelect', 'payerAccountSelect',
                    'additionalInformation', 'payerBank',
                    'payerBankName', 'payerBankAddress',
                    'intermediaryBank', 'intermediaryBankNameAndAddress', 'intermediaryBankAccount',
                    'beneficiaryBank', 'beneficiaryBankNameAndAddress', 'beneficiaryBankAccount',
                    'payerName', 'payerAddress', 'payerLocation'
                ], 'safe'
            ],
            [
                'beneficiaryBank', 'required', 'when' => function($model) {
                // swiftbic получателя должен быть заполнен, если данные не заполнены вручную
                return empty($this->beneficiaryBankNameAndAddress);
            }
            ],
            ['payerAccount', 'organizationDataValidation'],
            ['beneficiaryAccount', 'string', 'max' => '34'],
            [[
                'payerAddress',
                'payerName', 'payerLocation'
            ], 'string', 'max' => '35'],
            [['beneficiary', 'beneficiaryBankNameAndAddress', 'intermediaryBankNameAndAddress'], 'string', 'max' => '140'],
            ['beneficiaryAccount', 'match', 'pattern' => '/^[a-zA-Z0-9\s]*$/i'],
            [
                'commissionSum', 'number', 'min' => 0
            ],
//            [
//                'commissionSum', 'required' ,'when' => function($model) {
//                return $model->commission == ForeignCurrencyPaymentType::COMMISSION_BEN;
//            }
//            ],
        ];
    }

    /**
     * Проверка заполнения полей на латинском у организации, к которой относится счет плательщика
     */
    public function organizationDataValidation($attribute, $params)
    {
        if (empty($this->payerName) || empty($this->payerAddress) || empty($this->payerLocation)) {
            $this->addError($attribute, Yii::t('edm', 'Organization data must be in latin letters'));
        }
    }

    public function getImmediatePaymentDescription()
    {
        return ForeignCurrencyPaymentType::getImmediatePaymentLabel($this->immediatePayment);
    }
}
