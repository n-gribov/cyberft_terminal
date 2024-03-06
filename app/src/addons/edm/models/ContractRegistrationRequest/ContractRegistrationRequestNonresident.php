<?php

namespace addons\edm\models\ContractRegistrationRequest;

use Yii;
use yii\db\ActiveRecord;
use common\helpers\Countries;

/**
 * Данные о нерезидентах для документа 'Паспорт сделки'
 * Class ContractRegistrationRequestNonresident
 * @package addons\edm\models\ContractRegistrationRequest
 *
 * @property integer $id
 * @property string  $name
 * @property string  $countryCode
 * @property integer $amount
 * @property integer $percent
 * @property boolean $isCredit
 * @property integer $requestId
 *
 * @author n.poymanov
 */
class ContractRegistrationRequestNonresident extends ActiveRecord
{
    public static function tableName()
    {
        return 'contractRegistrationRequestNonresident';
    }

    public function rules()
    {
        return [
            [['name', 'countryCode'], 'required'],
            [['amount', 'percent', 'documentId'], 'safe'],
            ['isCredit', 'default', 'value' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('edm', 'Nonresident name'),
            'countryCode' => Yii::t('edm', 'Country code'),
            'amount' => Yii::t('edm', 'Amount'),
            'percent' => Yii::t('edm', 'Nonresident percent'),
        ];
    }

    public function getCountryName()
    {
        return Countries::getName($this->countryCode);
    }

    public function getNumericCountryCode()
    {
        return Countries::getNumericCode($this->countryCode);
    }

    public function getNonresidentsCreditNamePrintable()
    {
        return $this->name;
    }

    public function getNonresidentsCreditAmountPrintable()
    {
        return $this->amount;
    }

    public function getNonresidentsCreditPercentPrintable()
    {
        return $this->percent;
    }
}