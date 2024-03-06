<?php

namespace addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm;

use common\helpers\Countries;
use common\models\listitem\NestedListItem;
use Yii;

/**
 * @property string|null $beneficiaryCountryName
 */
class Receipt extends NestedListItem
{
    public $id;
    public $beneficiaryName;
    public $beneficiaryCountryCode;
    public $amount;
    public $shareOfLoanAmount;

    public function rules()
    {
        return [
            ['id', 'integer'],
            [['beneficiaryName', 'beneficiaryCountryCode'], 'string'],
            ['amount', 'double', 'min' => 0.01, 'numberPattern' => '/^\d+(\.\d{1,2})?$/'],
            ['shareOfLoanAmount', 'double', 'min' => 0, 'max' => 100],
            [['beneficiaryName', 'beneficiaryCountryCode', 'amount', 'shareOfLoanAmount'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'beneficiaryName' => Yii::t('edm', 'Non-resident name'),
            'beneficiaryCountryCode' => Yii::t('edm', 'Country'),
            'beneficiaryCountryName' => Yii::t('edm', 'Country'),
            'amount' => Yii::t('edm', 'Amount'),
            'shareOfLoanAmount' => Yii::t('edm', 'Share of loan amount'),
        ];
    }

    public function getBeneficiaryCountryName()
    {
        return $this->beneficiaryCountryCode ? Countries::getNameByNumericCode($this->beneficiaryCountryCode) : null;
    }
}
