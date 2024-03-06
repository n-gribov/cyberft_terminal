<?php

namespace addons\edm\models;

use addons\edm\validators\CbrKeyingValidator;
use common\helpers\Currencies;

use Yii;
use yii\db\ActiveRecord;
use addons\edm\helpers\Dict;

/**
 * @property integer $id
 * @property string $bankBik
 * @property string $type
 * @property string $role
 * @property integer $kpp
 * @property string $inn
 * @property string $account
 * @property string $currency
 * @property string $name
 * @property string $fullName
 * @property string $terminalId

 * @property DictBank $bank
 */
class DictContractor extends ActiveRecord
{
	const ROLE_PAYER       = 'PAYER';
	const ROLE_BENEFICIARY = 'BENEFICIARY';
    const TYPE_INDIVIDUAL = 'IND';
    const TYPE_ENTITY = 'ENT';

    /**
     * @inheritdoc
     */
	public static function tableName()
    {
		return 'edmDictContractor';
	}

	/**
	 * @return array
	 */
	public static function roleValues()
    {
		return [
			self::ROLE_PAYER       => Yii::t('edm', 'Payer'),
			self::ROLE_BENEFICIARY => Yii::t('edm', 'Beneficiary'),
		];
	}

	public static function typeValues()
    {
		return [
            self::TYPE_ENTITY => Yii::t('edm', 'Entity'),
			self::TYPE_INDIVIDUAL       => Yii::t('edm', 'Individual'),
		];
	}

    /**
	 * @return string
	 */
	public function getRoleLabel()
    {
		$roles = self::roleValues();

		if (isset($roles[$this->role])) {
			return $roles[$this->role];
		}

		return null;
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
            [['bankBik', 'account', 'name'], 'required'],
            [['kpp', 'inn'],
                'required',
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictcontractor-type').val() == 'ENT'; }"
            ],

			['bankBik', 'string', 'min' => 9, 'max' => 9],

            [
                'kpp', 'string', 'min' => 9, 'max' => 9,
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictcontractor-type').val() == 'ENT'; }"
            ],
            [
                'inn', 'string', 'min' => 12, 'max' => 12,
                'when' => function($model) { return $model->type == 'IND'; },
                'whenClient' => "function (attribute, value) { return $('#dictcontractor-type').val() == 'IND'; }"
            ],
            [
                'inn', 'string', 'min' => 10, 'max' => 10,
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictcontractor-type').val() == 'ENT'; }"
            ],
            [
                'kpp', 'string', 'min' => 0, 'max' => 0,
                'when' => function($model) { return $model->type == 'IND'; },
                'whenClient' => "function (attribute, value) { return $('#dictcontractor-type').val() == 'IND'; }"
            ],
			[
				'kpp',
                'match',
				'pattern' => '/^[0-9]{9}$/',
				'message' => Yii::t('app', 'The {attribute} must contain only digits'),
                'when' => function($model) { return $model->type == 'ENT'; },
                'whenClient' => "function (attribute, value) { return $('#dictcontractor-type').val() == 'ENT'; }"
			],
			[
				'bankBik',
                'match',
				'pattern' => '/^[0-9]{9}$/',
				'message' => Yii::t('app', 'The {attribute} must contain only digits')
			],

			['role', 'in', 'range' => array_keys(self::roleValues())],

			[['account'], 'string', 'min' => 20, 'max' => 20],
			[
				'account', 'match',
				'pattern' => '/^[0-9]{20}$/',
				'message' => Yii::t('edm', 'The {attribute} must contain only digits')
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

			[['currency'], 'string', 'max' => 3],
            [['currency'], 'in', 'range' => Currencies::getCodes()],

			[['type'], 'in', 'range' => array_keys(self::typeValues())],
            ['type', 'default', 'value' => self::TYPE_ENTITY],
            [['name'], 'string', 'max' => 255],
//			[
//				['terminalId'], 'required',
//			 	'when' => function($model) {
//					return ($model->role == self::ROLE_PAYER);
//				},
//				'whenClient' => "function (attribute, value) {
//					return $('#edmdictcontractor-role').val() == '".self::ROLE_PAYER."';
//				}"
//			],
//			['terminalId', TerminalIdValidator::className()],
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
            'role'       => Yii::t('app', 'Role'),
            'kpp'        => Yii::t('edm', 'KPP'),
            'inn'        => Yii::t('edm', 'INN'),
            'account'    => Yii::t('edm', 'Account'),
            'currency'   => Yii::t('app', 'Currency'),
            'name'       => Yii::t('edm', 'Contractor name'),
            'terminalId' => Yii::t('app/terminal', 'Terminal ID'),
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
}
