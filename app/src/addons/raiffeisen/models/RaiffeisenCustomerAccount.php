<?php

namespace addons\raiffeisen\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $number
 * @property string $bankBik
 * @property string $bankName
 * @property string $currencyCode
 * @property integer $customerId
 * @property RaiffeisenCustomer $customer
 */
class RaiffeisenCustomerAccount extends ActiveRecord
{
    public static function tableName()
    {
        return 'raiffeisen_customerAccount';
    }

    public function rules()
    {
        return [
            [['number', 'bankBik', 'bankName', 'currencyCode', 'customerId'], 'required'],
            [['number'], 'string', 'max' => 100],
            [['bankBik'], 'string', 'max' => 20],
            [['bankName'], 'string', 'max' => 1000],
            [['currencyCode'], 'string', 'max' => 10],
            [['number'], 'unique'],
            [['customerId'], 'exist', 'skipOnError' => true, 'targetClass' => RaiffeisenCustomer::class, 'targetAttribute' => ['customerId' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'           => Yii::t('app/raiffeisen', 'ID'),
            'number'       => Yii::t('app/raiffeisen', 'Account number'),
            'bankBik'      => Yii::t('app/raiffeisen', 'Bank BIK'),
            'bankName'     => Yii::t('app/raiffeisen', 'Bank name'),
            'currencyCode' => Yii::t('app/raiffeisen', 'Currency code'),
            'customerId'   => Yii::t('app/raiffeisen', 'Customer'),
        ];
    }

    public function getCustomer()
    {
        return $this->hasOne(RaiffeisenCustomer::class, ['id' => 'customerId']);
    }
}
