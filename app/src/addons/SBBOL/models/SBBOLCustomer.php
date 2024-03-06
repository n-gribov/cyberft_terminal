<?php

namespace addons\SBBOL\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sbbol_customer".
 *
 * @property string $id
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
 * @property string $holdingHeadId
 * @property string $login
 * @property string $password
 * @property string $senderName
 * @property string $certAuthId
 * @property integer $lastCertNumber
 * @property string $bankBranchSystemName
 * @property SBBOLCustomer $holdingHeadCustomer
 * @property SBBOLCustomerAccount[] $accounts
 * @property SBBOLCustomerKeyOwner[] $keyOwners
 * @property SBBOLOrganization $organization
 * @property string|null $terminalAddress
 */
class SBBOLCustomer extends ActiveRecord
{
    public static function tableName()
    {
        return 'sbbol_customer';
    }

    public function rules()
    {
        return [
            [['shortName', 'fullName', 'holdingHeadId', 'inn'], 'required'],
            [['isHoldingHead'], 'boolean'],
            [['lastCertNumber'], 'integer'],
            [['shortName', 'fullName', 'internationalName'], 'string', 'max' => 1000],
            [['inn', 'kpp', 'ogrn'], 'string', 'max' => 32],
            [['dateOgrn', 'addressBuilding', 'addressBuildingBlock', 'addressApartment'], 'string', 'max' => 10],
            [['countryCode'], 'string', 'max' => 5],
            [['addressState', 'addressDistrict', 'addressSettlement', 'addressStreet'], 'string', 'max' => 255],
            [['id', 'holdingHeadId', 'certAuthId', 'bankBranchSystemName'], 'string', 'max' => 100],
            [['login'], 'string', 'max' => 200],
            [['propertyType'], 'string', 'max' => 300],
            [['password', 'senderName'], 'string', 'max' => 500],
            [['holdingHeadId'], 'validateHoldingHeadId'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                   => Yii::t('app/sbbol', 'Organization ID'),
            'shortName'            => Yii::t('app/sbbol', 'Name'),
            'fullName'             => Yii::t('app/sbbol', 'Full name'),
            'internationalName'    => Yii::t('app/sbbol', 'International name'),
            'propertyType'         => Yii::t('app/sbbol', 'Property type'),
            'inn'                  => Yii::t('app/sbbol', 'INN'),
            'kpp'                  => Yii::t('app/sbbol', 'KPP'),
            'ogrn'                 => Yii::t('app/sbbol', 'OGRN'),
            'dateOgrn'             => Yii::t('app/sbbol', 'OGRN date'),
            'countryCode'          => Yii::t('app/sbbol', 'Country code'),
            'addressState'         => Yii::t('app/sbbol', 'State'),
            'addressDistrict'      => Yii::t('app/sbbol', 'District'),
            'addressSettlement'    => Yii::t('app/sbbol', 'Settlement'),
            'addressStreet'        => Yii::t('app/sbbol', 'Street'),
            'addressBuilding'      => Yii::t('app/sbbol', 'Building'),
            'addressBuildingBlock' => Yii::t('app/sbbol', 'Building block'),
            'addressApartment'     => Yii::t('app/sbbol', 'Apartment'),
            'isHoldingHead'        => Yii::t('app/sbbol', 'Is holding head'),
            'holdingHeadId'        => Yii::t('app/sbbol', 'Holding head organization ID'),
            'login'                => Yii::t('app/sbbol', 'Login'),
            'password'             => Yii::t('app/sbbol', 'Password'),
            'senderName'           => Yii::t('app/sbbol', 'Sender name'),
            'certAuthId'           => Yii::t('app/sbbol', 'Certificate authority code'),
            'lastCertNumber'       => Yii::t('app/sbbol', 'Last certificate number'),
            'bankBranchSystemName' => Yii::t('app/sbbol', 'Code of bank branch that signed a contract'),
        ];
    }

    public function getHoldingHeadCustomer()
    {
        return $this->hasOne(SBBOLCustomer::class, ['id' => 'holdingHeadId']);
    }

    public function getAccounts()
    {
        return $this->hasMany(SBBOLCustomerAccount::class, ['customerId' => 'id']);
    }

    public function getKeyOwners()
    {
        return $this->hasMany(SBBOLCustomerKeyOwner::class, ['customerId' => 'id']);
    }

    public function getOrganization()
    {
        return $this->hasOne(SBBOLOrganization::class, ['inn' => 'inn']);
    }

    public function getTerminalAddress()
    {
        return $this->organization ? $this->organization->terminalAddress : null;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->decryptPassword();
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->encryptPassword();

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->decryptPassword();
    }

    private function decryptPassword()
    {
        if (!$this->password) {
            return;
        }
        $encryptionKey = getenv('COOKIE_VALIDATION_KEY');
        $this->password = \Yii::$app->security->decryptByKey(base64_decode($this->password), $encryptionKey);
    }

    private function encryptPassword()
    {
        if (!$this->password) {
            return;
        }
        $encryptionKey = getenv('COOKIE_VALIDATION_KEY');
        $this->password = base64_encode(\Yii::$app->security->encryptByKey($this->password, $encryptionKey));
    }

    public function validateHoldingHeadId($attribute, $params = [])
    {
        if ($this->isHoldingHead && $this->id === $this->holdingHeadId) {
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
}
