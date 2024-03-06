<?php

namespace addons\sbbol2\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "sbbol2_customer".
 *
 * @property integer $id
 * @property string $inn
 * @property string $fullName
 * @property string $terminalAddress
 * @property string $shortName
 * @property string $kpp
 * @property string $ogrn
 * @property string $okato
 * @property string $okpo
 * @property string $orgForm
 * @property string $addressArea
 * @property string $addressBuilding
 * @property string $addressCity
 * @property string $addressCountryCode
 * @property string $addressFlat
 * @property string $addressHouse
 * @property string $addressRegion
 * @property string $addressSettlement
 * @property string $addressSettlementType
 * @property string $addressStreet
 * @property string $addressZip
 * @property Sbbol2CustomerAccessToken $customerAccessToken
 * @property Sbbol2CustomerAccount[] $customerAccounts
 */
class Sbbol2Customer extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createDate',
                'updatedAtAttribute' => 'updateDate',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function tableName()
    {
        return 'sbbol2_customer';
    }

    public function rules()
    {
        return [
            [['inn', 'fullName'], 'required'],
            [['inn', 'terminalAddress'], 'string', 'max' => 32],
            ['fullName', 'string', 'max' => 1000],
            [['inn', 'terminalAddress'], 'unique'],
            [['terminalAddress'], 'default', 'value' => null],
        ];
    }

    public function attributeLabels()
    {
        return [
            'inn' => Yii::t('app/sbbol2', 'INN'),
            'shortName' => Yii::t('app/sbbol2', 'Short name'),
            'fullName' => Yii::t('app/sbbol2', 'Full name'),
            'terminalAddress' => Yii::t('app/sbbol2', 'Terminal address'),
            'kpp' => Yii::t('app/sbbol2', 'KPP'),
            'ogrn' => Yii::t('app/sbbol2', 'OGRN'),
            'okato' => Yii::t('app/sbbol2', 'OKATO'),
            'okpo' => Yii::t('app/sbbol2', 'OKPO'),
            'orgForm' => Yii::t('app/sbbol2', 'Organization Form'),
            'addressArea' => Yii::t('app/sbbol2', 'Area'),
            'addressBuilding' => Yii::t('app/sbbol2', 'Building'),
            'addressCity' => Yii::t('app/sbbol2', 'City'),
            'addressCountryCode' => Yii::t('app/sbbol2', 'Country code'),
            'addressFlat' => Yii::t('app/sbbol2', 'Flat'),
            'addressHouse' => Yii::t('app/sbbol2', 'House'),
            'addressRegion' => Yii::t('app/sbbol2', 'State'),
            'addressSettlement' => Yii::t('app/sbbol2', 'Settlement'),
            'addressSettlementType' => Yii::t('app/sbbol2', 'Settlement type'),
            'addressStreet' => Yii::t('app/sbbol2', 'Street'),
            'addressZip' => Yii::t('app/sbbol2', 'Zip'),
        ];
    }

    public function getCustomerAccessToken()
    {
        return $this->hasOne(Sbbol2CustomerAccessToken::className(), ['customerId' => 'id']);
    }

    public function getCustomerAccounts()
    {
        return $this->hasMany(Sbbol2CustomerAccount::class, ['customerId' => 'id']);
    }

}
