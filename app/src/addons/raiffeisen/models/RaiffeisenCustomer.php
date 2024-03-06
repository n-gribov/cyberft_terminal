<?php

namespace addons\raiffeisen\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $shortName
 * @property string $fullName
 * @property string $internationalName
 * @property string $propertyType
 * @property string $inn
 * @property string $kpp
 * @property string $ogrn
 * @property string $dateOgrn
 * @property string $countryCode
 * @property string $addressState
 * @property string $addressDistrict
 * @property string $addressSettlement
 * @property string $addressStreet
 * @property string $addressBuilding
 * @property string $addressBuildingBlock
 * @property string $addressApartment
 * @property boolean $isHoldingHead
 * @property integer $holdingHeadId
 * @property string $login
 * @property string $password
 * @property string $certificate
 * @property string $privateKeyPassword
 * @property string|null $terminalAddress
 * @property string $signatureType
 * @property RaiffeisenCustomer $holdingHeadCustomer
 * @property RaiffeisenCustomerAccount $accounts
 */
class RaiffeisenCustomer extends ActiveRecord
{
    public static function tableName()
    {
        return 'raiffeisen_customer';
    }

    public function rules()
    {
        return [
            [['shortName', 'fullName', 'inn', 'kpp', 'signatureType'], 'required'],
            [['isHoldingHead'], 'boolean'],
            [['shortName', 'fullName', 'internationalName'], 'string', 'max' => 1000],
            [['inn', 'kpp', 'ogrn', 'terminalAddress'], 'string', 'max' => 32],
            [['dateOgrn', 'addressBuilding', 'addressBuildingBlock', 'addressApartment'], 'string', 'max' => 10],
            [['countryCode'], 'string', 'max' => 5],
            [['addressState', 'addressDistrict', 'addressSettlement', 'addressStreet', 'signatureType'], 'string', 'max' => 255],
            [['login', 'propertyType', 'password', 'privateKeyPassword', 'certificate'], 'string'],
            [['holdingHeadId'], 'validateHoldingHeadId', 'skipOnEmpty' => false],
            ['terminalAddress', 'unique'],
            [
                [
                    'shortName', 'fullName', 'internationalName', 'propertyType', 'inn', 'kpp', 'ogrn', 'dateOgrn',
                    'countryCode', 'addressState', 'addressDistrict', 'addressSettlement', 'addressStreet',
                    'addressBuilding', 'addressBuildingBlock', 'addressApartment', 'isHoldingHead', 'holdingHeadId',
                    'login', 'password', 'terminalAddress', 'privateKeyPassword', 'certificate', 'signatureType',
                ],
                'default',
                'value' => null,
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'                     => Yii::t('app/raiffeisen', 'ID'),
            'shortName'              => Yii::t('app/raiffeisen', 'Name'),
            'fullName'               => Yii::t('app/raiffeisen', 'Full name'),
            'internationalName'      => Yii::t('app/raiffeisen', 'International name'),
            'propertyType'           => Yii::t('app/raiffeisen', 'Property type'),
            'inn'                    => Yii::t('app/raiffeisen', 'INN'),
            'kpp'                    => Yii::t('app/raiffeisen', 'KPP'),
            'ogrn'                   => Yii::t('app/raiffeisen', 'OGRN'),
            'dateOgrn'               => Yii::t('app/raiffeisen', 'OGRN date'),
            'countryCode'            => Yii::t('app/raiffeisen', 'Country code'),
            'addressState'           => Yii::t('app/raiffeisen', 'State'),
            'addressDistrict'        => Yii::t('app/raiffeisen', 'District'),
            'addressSettlement'      => Yii::t('app/raiffeisen', 'Settlement'),
            'addressStreet'          => Yii::t('app/raiffeisen', 'Street'),
            'addressBuilding'        => Yii::t('app/raiffeisen', 'Building'),
            'addressBuildingBlock'   => Yii::t('app/raiffeisen', 'Building block'),
            'addressApartment'       => Yii::t('app/raiffeisen', 'Apartment'),
            'isHoldingHead'          => Yii::t('app/raiffeisen', 'Is holding head'),
            'holdingHeadId'          => Yii::t('app/raiffeisen', 'Holding head organization ID'),
            'login'                  => Yii::t('app/raiffeisen', 'Login'),
            'password'               => Yii::t('app/raiffeisen', 'Password'),
            'certificate'            => Yii::t('app/raiffeisen', 'Certificate'),
            'privateKeyPassword'     => Yii::t('app/raiffeisen', 'Private key password'),
            'terminalAddress'        => Yii::t('app/raiffeisen', 'Terminal address'),
            'signatureType'          => Yii::t('app/raiffeisen', 'Signature type'),
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->decryptPasswords();
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->encryptPasswords();

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert && $this->isHoldingHead) {
            $this->updateAttributes(['holdingHeadId' => $this->id]);
        }
        $this->decryptPasswords();
    }

    private function decryptPasswords()
    {
        if (!$this->password) {
            return;
        }
        $encryptionKey = getenv('COOKIE_VALIDATION_KEY');
        $this->password = \Yii::$app->security->decryptByKey(base64_decode($this->password), $encryptionKey);
        $this->privateKeyPassword = \Yii::$app->security->decryptByKey(base64_decode($this->privateKeyPassword), $encryptionKey);
    }

    private function encryptPasswords()
    {
        if (!$this->password) {
            return;
        }
        $encryptionKey = getenv('COOKIE_VALIDATION_KEY');
        $this->password = base64_encode(\Yii::$app->security->encryptByKey($this->password, $encryptionKey));
        $this->privateKeyPassword = base64_encode(\Yii::$app->security->encryptByKey($this->privateKeyPassword, $encryptionKey));
    }

    public function validateHoldingHeadId($attribute, $params = [])
    {
        if ($this->isHoldingHead && $this->isNewRecord) {
            return;
        }

        $holdingHeadExists = static::find()
            ->where(['id' => $this->holdingHeadId])
            ->exists();

        if (!$holdingHeadExists) {
            $errorMessage = Yii::t(
                'yii',
                '{attribute} is invalid.',
                ['attribute' => $this->getAttributeLabel($attribute)]
            );
            $this->addError($attribute, $errorMessage);
        }
    }

    public function getHoldingHeadCustomer()
    {
        return $this->hasOne(RaiffeisenCustomer::class, ['id' => 'holdingHeadId']);
    }

    public function getAccounts()
    {
        return $this->hasMany(RaiffeisenCustomerAccount::class, ['customerId' => 'id']);
    }

    public static function getSignatureTypes(): array
    {
        return [
            'Первая подпись',
            'Вторая подпись',
            'Единственная подпись',
            'Подпись для ВК',
            'Визирующая подпись',
        ];
    }
}
