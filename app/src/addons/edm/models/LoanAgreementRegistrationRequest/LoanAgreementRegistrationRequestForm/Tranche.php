<?php

namespace addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm;

use addons\edm\models\DictVTBTranchePaymentPeriod;
use common\models\listitem\NestedListItem;
use Yii;

/**
 * @property string|null $paymentPeriodName
 */
class Tranche extends NestedListItem
{
    public $id;
    public $amount;
    public $paymentPeriodCode;
    public $receiptDate;

    public function rules()
    {
        return [
            ['id', 'integer'],
            ['amount', 'double', 'min' => 0.01, 'numberPattern' => '/^\d+(\.\d{1,2})?$/'],
            ['paymentPeriodCode', 'string'],
            ['receiptDate', 'date', 'format' => 'dd.MM.yyyy'],
            [['amount', 'paymentPeriodCode', 'receiptDate'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'amount' => Yii::t('edm', "Tranche's amount"),
            'paymentPeriodCode' => Yii::t('edm', 'Tranche usage period'),
            'paymentPeriodName' => Yii::t('edm', 'Tranche usage period'),
            'receiptDate' => Yii::t('edm', 'Expected tranche receipt date'),
        ];
    }

    public function getPaymentPeriodName()
    {
        $period = DictVTBTranchePaymentPeriod::findOneByCode($this->paymentPeriodCode);
        return $period ? $period->name : null;
    }
}
