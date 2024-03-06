<?php


namespace addons\VTB\models;


use addons\edm\models\DictPropertyType;
use common\validators\TerminalIdValidator;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class VTBCustomer
 * @package addons\VTB\models
 * @property integer $id
 * @property integer $customerId
 * @property integer $clientId
 * @property string  $name
 * @property string  $fullName
 * @property integer $type
 * @property integer $propertyTypeId
 * @property string  $inn
 * @property string  $kpp
 * @property string  $okato
 * @property string  $okpo
 * @property string  $countryCode
 * @property string  $addressState
 * @property string  $addressDistrict
 * @property string  $addressSettlement
 * @property string  $addressStreet
 * @property string  $addressBuilding
 * @property string  $addressBuildingBlock
 * @property string  $addressApartment
 * @property string  $addressZipCode
 * @property string  $internationalName
 * @property string  $internationalAddressState
 * @property string  $internationalAddressSettlement
 * @property string  $internationalStreetAddress
 * @property string  $internationalZipCode
 * @property string  $terminalId
 * @property VTBCustomerAccount[] $accounts
 * @property DictPropertyType $propertyType
 */
class VTBCustomer extends ActiveRecord
{
    const SCENARIO_WEB_UPDATE = 'web_update';

    public static function tableName()
    {
        return 'vtb_customer';
    }

    public function rules()
    {
        return [
            [['customerId', 'type', 'propertyTypeId', 'clientId'], 'integer'],
            [
                [
                    'name', 'fullName', 'inn', 'kpp', 'countryCode', 'addressState', 'addressDistrict',
                    'addressBuilding', 'addressBuildingBlock', 'addressApartment',
                    'addressSettlement', 'addressStreet', 'internationalName', 'internationalAddressState',
                    'internationalAddressSettlement', 'internationalStreetAddress',
                    'okato', 'okpo', 'addressZipCode', 'internationalZipCode',
                ],
                'string'
            ],
            [['customerId', 'fullName', 'name'], 'required'],
            ['customerId', 'unique'],
            ['terminalId', TerminalIdValidator::className()],
            ['terminalId', 'default', 'value' => null],
        ];
    }

    public function attributeLabels()
    {
        return [
            'customerId'                     => Yii::t('app/vtb', 'Customer ID'),
            'clientId'                       => Yii::t('app/vtb', 'Unique complex identifier'),
            'type'                           => Yii::t('app/vtb', 'Customer type'),
            'propertyTypeId'                 => Yii::t('app/vtb', 'Property type'),
            'name'                           => Yii::t('app/vtb', 'Name'),
            'fullName'                       => Yii::t('app/vtb', 'Full name'),
            'inn'                            => Yii::t('app/vtb', 'INN'),
            'kpp'                            => Yii::t('app/vtb', 'KPP'),
            'okato'                          => Yii::t('app/vtb', 'OKATO code'),
            'okpo'                           => Yii::t('app/vtb', 'OKPO code'),
            'terminalId'                     => Yii::t('app/vtb', 'Terminal address'),
            'countryCode'                    => Yii::t('app/vtb', 'Country code'),
            'addressState'                   => Yii::t('app/vtb', 'Region'),
            'addressDistrict'                => Yii::t('app/vtb', 'District'),
            'addressSettlement'              => Yii::t('app/vtb', 'Settlement'),
            'addressStreet'                  => Yii::t('app/vtb', 'Street'),
            'addressBuilding'                => Yii::t('app/vtb', 'Building'),
            'addressBuildingBlock'           => Yii::t('app/vtb', 'Building block'),
            'addressApartment'               => Yii::t('app/vtb', 'Apartment'),
            'addressZipCode'                 => Yii::t('app/vtb', 'Zip code'),
            'internationalName'              => Yii::t('app/vtb', 'International name'),
            'internationalAddressState'      => Yii::t('app/vtb', 'International address region'),
            'internationalAddressSettlement' => Yii::t('app/vtb', 'International address settlement'),
            'internationalStreetAddress'     => Yii::t('app/vtb', 'International street address'),
            'internationalZipCode'           => Yii::t('app/vtb', 'International zip code'),
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'customerId', 'type', 'propertyTypeId', 'fullName', 'name', 'inn', 'kpp', 'terminalId', 'countryCode',
                'addressState', 'addressDistrict', 'addressSettlement', 'addressStreet',
                'addressBuilding', 'addressBuildingBlock', 'addressApartment',
                'internationalName', 'internationalAddressState', 'internationalAddressSettlement',
                'internationalStreetAddress', 'clientId', 'okato', 'okpo', 'addressZipCode', 'internationalZipCode'
            ],
            self::SCENARIO_WEB_UPDATE => ['terminalId'],
        ];
    }

    public static function refreshAll($data)
    {
        $oldCustomers = static::findAll(['not', ['customerId' => null]]);
        $terminalsIds = array_reduce(
            $oldCustomers,
            function ($carry, VTBCustomer $customer) {
                $carry[$customer->customerId] = $customer->terminalId;
                return $carry;
            },
            []
        );

        $newCustomersIds = ArrayHelper::getColumn($data, 'customerId');
        static::deleteAll(['not in', 'customerId', $newCustomersIds]);

        foreach ($data as $attributes) {
            $customer = self::findOneByCustomerId($attributes['customerId']);
            if ($customer === null) {
                $customer = new VTBCustomer();
            }
            $customer->setAttributes($attributes);
            if (array_key_exists($customer->customerId, $terminalsIds)) {
                $customer->terminalId = $terminalsIds[$customer->customerId];
            }
            // Сохранить модель в БД
            $customer->save();
        }
    }

    public static function findOneByCustomerId($customerId)
    {
        return static::findOne(['customerId' => $customerId]);
    }

    public function getAccounts()
    {
        return $this->hasMany(VTBCustomerAccount::class, ['customerId' => 'customerId']);
    }

    public function getPropertyType()
    {
        return $this->hasOne(DictPropertyType::class, ['vtbId' => 'propertyTypeId']);
    }
}
