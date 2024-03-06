<?php

namespace addons\edm\models\VTBContractRequest;

use addons\edm\models\DictCurrency;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $requestId
 * @property string $number
 * @property string $date
 * @property integer $type
 * @property float $amount
 * @property integer $currencyId
 * @property DictCurrency|null $currency
 */
class VTBContractRequestContract extends ActiveRecord
{
    const CONTRACT_TYPE_CONTRACT = 'contract';
    const CONTRACT_TYPE_CREDIT_AGREEMENT = 'creditAgreement';

    public static function tableName()
    {
        return 'documentExtEdmVTBContractRequestContract';
    }

    public function getCurrency()
    {
        return $this->hasOne(DictCurrency::className(), ['id' => 'currencyId']);
    }
}
