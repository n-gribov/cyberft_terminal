<?php

namespace addons\edm\models;

use yii\db\ActiveRecord;

class DictForeignCurrencyPaymentBeneficiary extends ActiveRecord
{
    public static function tableName()
    {
        return 'edmForeignCurrencyPaymentBeneficiary';
    }

    public function rules()
    {
        return [
            [['account', 'description', 'terminalId'], 'required'],
            [['account', 'description'], 'string', 'max' => 255],
        ];
    }
}