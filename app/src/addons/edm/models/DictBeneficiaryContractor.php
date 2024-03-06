<?php

namespace addons\edm\models;

use addons\edm\validators\CbrKeyingValidator;
use Yii;
use yii\db\ActiveRecord;
use addons\edm\helpers\Dict;
use common\models\Terminal;

/**
 * @property integer $id
 * @property string $name
 * @property string $bankBik
 * @property string $type
 * @property integer $kpp
 * @property string $inn
 * @property string $account
 * @property string $currencyId
 * @property string $terminalId
 */
class DictBeneficiaryContractor extends ActiveRecord
{
    const TYPE_INDIVIDUAL = 'IND';
    const TYPE_ENTITY = 'ENT';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edmDictBeneficiaryContractor';
    }

    public static function typeValues()
    {
        return [
            self::TYPE_ENTITY => Yii::t('edm', 'Entity'),
            self::TYPE_INDIVIDUAL       => Yii::t('edm', 'Individual'),
        ];
    }

    public function getTypeLabel()
    {
        $types = self::typeValues();

        if (isset($types[$this->type])) {
            return $types[$this->type];
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bankBik', 'account', 'name', 'currencyId', 'terminalId'], 'required'],
            ['name', 'string', 'max' => 160],
            [['inn'],
                'required',
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictbeneficiarycontractor-type').val() == 'ENT'; }"
            ],
            [['kpp'],
                'required',
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictbeneficiarycontractor-type').val() == 'ENT'; }"
            ],
            ['bankBik', 'string', 'min' => 9, 'max' => 9],

            [
                'kpp', 'string', 'min' => 9, 'max' => 9,
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictbeneficiarycontractor-type').val() == 'ENT'; }"
            ],
            [
                'inn', 'string', 'min' => 12, 'max' => 12,
                'when' => function($model) { return $model->type == 'IND'; },
                'whenClient' => "function (attribute, value) { return $('#dictbeneficiarycontractor-type').val() == 'IND'; }"
            ],
            [
                'inn', 'string', 'min' => 10, 'max' => 10,
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictbeneficiarycontractor-type').val() == 'ENT'; }"
            ],
            [
                'kpp', 'string', 'min' => 0, 'max' => 0,
                'when' => function($model) { return $model->type == 'IND'; },
                'whenClient' => "function (attribute, value) { return $('#dictbeneficiarycontractor-type').val() == 'IND'; }"
            ],
            [
                'kpp',
                'match',
                'pattern' => '/^[0-9]{9}$/',
                'message' => Yii::t('app', 'The {attribute} must contain only digits'),
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictbeneficiarycontractor-type').val() == 'ENT'; }"
            ],
            [
                'bankBik',
                'match',
                'pattern' => '/^[0-9]{9}$/',
                'message' => Yii::t('app', 'The {attribute} must contain only digits')
            ],
            [['account'], 'unique', 'targetAttribute' => ['account', 'terminalId', 'inn']],
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
            [['name'], 'string', 'max' => 255],
            ['account', CbrKeyingValidator::className(), 'bikKey' => 'bankBik']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'bankBik'    => Yii::t('edm', 'BIK'),
            'type'       => Yii::t('edm', 'Contractor type'),
            'typeLabel'  => Yii::t('edm', 'Contractor type'),
            'kpp'        => Yii::t('edm', 'KPP'),
            'inn'        => Yii::t('edm', 'INN'),
            'account'    => Yii::t('edm', 'Account'),
            'currencyId'   => Yii::t('app', 'Currency'),
            'name'       => Yii::t('edm', 'Beneficiary name'),
            'terminalId'   => Yii::t('app/terminal', 'Terminal ID'),
            'bankName' => Yii::t('edm', 'Bank name')
        ];
    }

    public function getFullName()
    {
        return "Счет: $this->account Название: $this->name";
    }

    public function getBank()
    {
        return $this->hasOne(DictBank::className(), ['bik' => 'bankBik']);
    }

    public function __toString()
    {
        return $this->getFullName();
    }

    public function validateCodeNumber($attribute, $params = [])
    {
        Dict::validateCodeNumber($this, $attribute, $params);
    }

    public function beforeValidate()
    {
        parent::beforeValidate();
        $this->account = str_replace('.', '', $this->account);
        return true;
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        $this->account = str_replace('.', '', $this->account);
        return true;

    }

    /*
 * Связь с таблицей терминалов
 * для получения имени терминала
 */
    public function getTerminal()
    {
        return $this->hasOne(Terminal::className(), ['id' => 'terminalId']);
    }
}
