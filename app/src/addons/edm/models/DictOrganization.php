<?php

namespace addons\edm\models;

use Yii;
use yii\db\ActiveRecord;
use addons\edm\helpers\Dict;
use common\models\Terminal;
use yii\helpers\ArrayHelper;

/**
 * @property string $name
 * @property string $type
 * @property string $inn
 * @property string $kpp
 * @property string $terminalId
 * @property string $address
 * @property string $locationLatin
 * @property string $nameLatin
 * @property string $addressLatin
 * @property string $ogrn
 * @property string $dateEgrul
 * @property string $state
 * @property string $city
 * @property string $street
 * @property string $building
 * @property string $district
 * @property string $locality
 * @property string $buildingNumber
 * @property string $apartment
 * @property EdmPayerAccount[] $accounts
 * @property Terminal $terminal
 * @property string $typeLabel
 * @property string $fullAddress
 * @property string $propertyType
 * @property string $propertyTypeCode
 * @property-read null|string $fullAddressLatin
 * @property integer disablePayeeDetailsValidation
 */

class DictOrganization extends ActiveRecord
{
    /*
     * Типы организаций - физическое/юридическое лицо
     */
    const TYPE_INDIVIDUAL = 'IND';
    const TYPE_ENTITY = 'ENT';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edmDictOrganization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['name', 'type', 'terminalId'], 'required'
            ],
            [['kpp', 'inn'],
                'required',
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictorganization-type').val() == 'ENT'; }"
            ],
            [
                'kpp', 'string', 'min' => 9, 'max' => 9,
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictorganization-type').val() == 'ENT'; }"
            ],
            [
                'inn', 'string', 'min' => 12, 'max' => 12,
                'when' => function($model) { return $model->type == 'IND'; },
                'whenClient' => "function (attribute, value) { return $('#dictorganization-type').val() == 'IND'; }"
            ],
            [
                'inn', 'string', 'min' => 10, 'max' => 10,
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictorganization-type').val() == 'ENT'; }"
            ],
            [
                'kpp', 'string', 'min' => 0, 'max' => 0,
                'when' => function($model) { return $model->type == 'IND'; },
                'whenClient' => "function (attribute, value) { return $('#dictorganization-type').val() == 'IND'; }",
            ],
            [
                'kpp',
                'match',
                'pattern' => '/^[0-9]{9}$/',
                'message' => Yii::t('app', 'The {attribute} must contain only digits'),
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictorganization-type').val() == 'ENT'; }"
            ],
            [
                'inn',
                'validateCodeNumber',
                'params' => [
                    'options' => [10, 12],
                    'nozero12' => true,
                ]
            ],
            [
                'kpp',
                'validateCodeNumber',
                'params' => [
                    'options' => [9],
                    'nozero12' => true,
                ]
            ],
            [['type'], 'in', 'range' => array_keys(self::typeValues())],
            ['type', 'default', 'value' => self::TYPE_ENTITY],
            [['name', 'address'],  'string', 'max' => 255],
            [['nameLatin', 'locationLatin', 'addressLatin'], 'string', 'max' => 33],
            [['nameLatin', 'addressLatin', 'locationLatin'], 'match', 'pattern' => '/^[\w\s,.\-;\!@#%^&*()\$\\\\\/]+$/'],
            [['ogrn', 'dateEgrul', 'state', 'city', 'street', 'building', 'district', 'locality', 'buildingNumber', 'apartment', 'propertyTypeCode', 'disablePayeeDetailsValidation'], 'safe'],
            ['ogrn', 'string', 'max' => 13],
            ['ogrn', 'string', 'min' => 13]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'          => Yii::t('edm', 'Organization name'),
            'type'          => Yii::t('edm', 'Type'),
            'inn'           => Yii::t('edm', 'INN'),
            'kpp'           => Yii::t('edm', 'KPP'),
            'terminalId'   => Yii::t('app/terminal', 'Terminal ID'),
            'typeLabel'  => Yii::t('edm', 'Type'),
            'address' => Yii::t('edm', 'Address'),
            'nameLatin' => Yii::t('edm', 'Organization name in latin'),
            'addressLatin' => Yii::t('edm', 'Address'),
            'locationLatin' => Yii::t('edm', 'Location'),
            'ogrn' => Yii::t('edm', 'OGRN'),
            'dateEgrul' => Yii::t('edm', 'Date of entry to EGRUL'),
            'state' => Yii::t('edm', 'State'),
            'city' => Yii::t('edm', 'City'),
            'street' => Yii::t('edm', 'Street'),
            'building' => Yii::t('edm', 'Building'),
            'district' => Yii::t('edm', 'District'),
            'locality' => Yii::t('edm', 'Locality'),
            'buildingNumber' => Yii::t('edm', 'Building number'),
            'apartment' => Yii::t('edm', 'Apartment'),
            'propertyTypeCode' => Yii::t('edm', 'Property type'),
            'disablePayeeDetailsValidation' => Yii::t('edm', 'Disable payee details validation'),
        ];
    }

    /**
     * Список типов организаций
     * @return array
     */
    public static function typeValues()
    {
        return [
            self::TYPE_ENTITY => Yii::t('edm', 'Entity'),
            self::TYPE_INDIVIDUAL       => Yii::t('edm', 'Individual'),
        ];
    }

    /*
     * Получение наименования
     * типа организации
     */
    public function getTypeLabel()
    {
        $types = self::typeValues();

        if (isset($types[$this->type])) {
            return $types[$this->type];
        }

        return null;
    }

    /**
     * Валидация ИНН/КПП
     * @param $attribute
     * @param array $params
     */
    public function validateCodeNumber($attribute, $params = [])
    {
        Dict::validateCodeNumber($this, $attribute, $params);
    }

    /*
     * Связь с таблицей терминалов
     * для получения имени терминала
     */
    public function getTerminal()
    {
        return $this->hasOne(Terminal::className(), ['id' => 'terminalId']);
    }

    /**
     * Получение связанных с организацией счетов
     */
    public function getAccounts()
    {
        return $this->hasMany(EdmPayerAccount::className(), ['organizationId' => 'id']);
    }

    public function getBankAccounts($bankBiks = null, $payerName = null, $filter = null)
    {
        $query = EdmPayerAccount::find()
            ->where(['organizationId' => $this->id])
            ->andFilterWhere(['bankBik' => $bankBiks])
            ->andFilterWhere(['payerName' => $payerName])
            ->andFilterWhere(['id' => $filter]);

        return $query->all();
    }

    public function getBankAccountsWithoutPayerName($bankBiks = null, $filter = null)
    {
        $query = EdmPayerAccount::find()
            ->where(['organizationId' => $this->id])
            ->andWhere("(payerName is null or payerName='')")
            ->andFilterWhere(['bankBik' => $bankBiks])
            ->andFilterWhere(['id' => $filter]);

        return $query->all();
    }

    public function getBanks($bankBiks = null)
    {
        //$terminal = $this->terminal;
        $joinCondition = [
            'and',
            'bank.bik=accnt.bankBik',
            ['accnt.organizationId' => $this->id],
            //['bank.terminalId' => $terminal->terminalId]
        ];

        if ($bankBiks) {
            $joinCondition[] = ['bank.bik' => $bankBiks];
        }

        $query = DictBank::find()
            ->from(DictBank::tableName() . ' bank')
            ->innerJoin(EdmPayerAccount::tableName() . ' accnt', $joinCondition)
            ->orderBy(['bank.name' => SORT_ASC]);

        return $query->all();
    }

    public function getBanksWithoutPayerName($bankBiks = null)
    {
        //$terminal = $this->terminal;
        $joinCondition = [
            'and',
            'bank.bik=accnt.bankBik',
            "(accnt.payerName is null or accnt.payerName='')",
            ['accnt.organizationId' => $this->id],
            //['bank.terminalId' => $terminal->terminalId]
        ];

        if ($bankBiks) {
            $joinCondition[] = ['bank.bik' => $bankBiks];
        }

        $query = DictBank::find()
            ->from(DictBank::tableName() . ' bank')
            ->innerJoin(EdmPayerAccount::tableName() . ' accnt', $joinCondition)
            ->orderBy(['bank.name' => SORT_ASC]);

        return $query->all();
    }

    public function getPropertyType()
    {
        return $this->hasOne(DictPropertyType::className(), ['code' => 'propertyTypeCode']);
    }

    /**
     * Получение организации по id
     */
    public static function getNameById($id)
    {
        $model = static::findOne($id);
        return empty($model) ? null : $model->name;
    }

    public function getFullAddress()
    {
        $addressParts = [
            $this->state,
            ($this->city && $this->city != $this->state ? $this->city : null),
            $this->district,
            ($this->locality && $this->locality != $this->city && $this->locality != $this->state ? $this->city : null),
            $this->street,
            ($this->buildingNumber ? "д. {$this->buildingNumber}" : null),
            ($this->building ? "к. {$this->building}" : null),
            ($this->apartment ? "кв. {$this->apartment}" : null),
        ];
        return implode(
            ', ',
            array_filter($addressParts)
        );
    }

    public function getFullAddressLatin(): ?string
    {
        $address = implode(
            ', ',
            array_filter([$this->locationLatin, $this->addressLatin])
        );
        return $address === '' ? null : $address;
    }

    public static function propertyTypeValues()
    {
        $propertyTypes = DictPropertyType::find()->all();

        $topEntriesCodes = ['ООО', 'ОАО', 'ЗАО', 'ПАО', 'АО', 'ИП'];

        usort(
            $propertyTypes,
            function (DictPropertyType $a, DictPropertyType $b) use ($topEntriesCodes) {
                $aIsTop = in_array($a->code, $topEntriesCodes);
                $bIsTop = in_array($b->code, $topEntriesCodes);
                if ($aIsTop !== $bIsTop) {
                    return intval($bIsTop) - intval($aIsTop);
                } else if ($aIsTop) {
                    return array_search($a->code, $topEntriesCodes) - array_search($b->code, $topEntriesCodes);
                }
                return strcmp($a->name, $b->name);
            }
        );

        return ArrayHelper::map($propertyTypes, 'code', 'name');
    }
}
