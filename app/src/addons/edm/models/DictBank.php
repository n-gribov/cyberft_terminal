<?php

namespace addons\edm\models;

use common\validators\TerminalIdValidator;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property string $bik
 * @property string $account
 * @property string $name
 * @property string $fullname
 * @property string $city
 * @property string $terminalId

 * @property DictContractor[] $DictContractors
 */
class DictBank extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edmDictBank';
    }

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bik', 'name'], 'required'],

			[['bik'], 'string', 'min' => 9, 'max' => 9],
			[
				['bik'], 'match',
				'pattern' => '/^[0-9]{9}$/',
				'message' => Yii::t('edm', 'The {attribute} must contain only digits')
			],
			[['bik'], 'unique'],

			[['account'], 'string', 'min' => 20, 'max' => 20],
			[
				'account', 'match',
				'pattern' => '/^[0-9]{20}$/',
				'message' => Yii::t('edm', 'The {attribute} must contain only digits')
			],

            [['name', 'city'], 'string', 'max' => 255],
			['terminalId', TerminalIdValidator::className()],
			[['postalCode', 'address', 'okpo'], 'safe'],
            [['terminalId'], 'default', 'value' => null],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bik'        => Yii::t('edm', 'BIK'),
            'account'    => Yii::t('edm', 'Correspondent account'),
            'name'       => Yii::t('app', 'Name'),
            'city'       => Yii::t('app', 'City'),
            'terminalId' => Yii::t('app', 'Terminal'),
        ];
    }

    /**
	 * @return string
	 */
	public function getFullName() {
		return "БИК: $this->bik Банк: $this->name";
	}

    /**
     * @return ActiveQuery
     */
    public function getDictContractors()
    {
        return $this->hasMany(DictContractor::className(), ['bankBik' => 'bik']);
    }

	function __toString() {
		return $this->getFullName();
    }

    public function allTerminalIdIsNull()
    {
        $countBankWithNotNullTerminalId = DictBank::find()
            ->from(DictBank::tableName())
            ->andWhere(['is not', 'terminalId', null])
            ->count();

        if ($countBankWithNotNullTerminalId == 0) {
            return true; // все значения TerminalId пустые
        } else {
            return false; // есть не пустые значения TerminalId
        }
    }

    public static function getDictBankListWithTerminalId()
    {
        $query = DictBank::find()
            ->from(DictBank::tableName())
            ->where(['is not', 'terminalId', null]); // условие для отбора банков с не пустым кодом участника CyberFT
        $items = $query->all();
        return $items;
    }
}
