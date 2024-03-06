<?php

namespace addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm;

use common\models\listitem\NestedListItem;
use Yii;

class PaymentScheduleItem extends NestedListItem
{
    public $id;
    public $debtDate;
    public $debtAmount;
    public $interestDate;
    public $interestAmount;
    public $specialConditions;

    public function rules()
    {
        return [
            ['id', 'integer'],
            [['debtDate', 'interestDate'], 'date', 'format' => 'dd.MM.yyyy'],
            [['debtAmount', 'interestAmount'], 'double', 'min' => 0.01, 'numberPattern' => '/^\d+(\.\d{1,2})?$/'],
            ['specialConditions', 'string'],
            [['debtDate', 'debtAmount', 'interestDate', 'interestAmount'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'debtDate' => Yii::t('edm', 'Main debt repayment date'),
            'debtAmount' => Yii::t('edm', 'Main debt repayment amount'),
            'interestDate' => Yii::t('edm', 'Interest repayment date'),
            'interestAmount' => Yii::t('edm', 'Interest repayment amount'),
            'specialConditions' => Yii::t('edm', 'Special conditions'),
        ];
    }
}
