<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "compoundCondition".
 *
 * @property integer $id
 * @property string $serviceId
 * @property string $type
 * @property string $searchPath
 * @property string $body
 */
class CompoundCondition extends ActiveRecord
{
	private $_conditions;

	public function init()
	{
		$this->_conditions = [];
	}

	public function rules()
	{
		return [
			[['serviceId'], 'required'],
		];
	}

	public static function tableName()
	{
		return 'compoundCondition';
	}

	public function beforeSave($insert)
	{
		parent::beforeSave($insert);
//		if (empty($this->_conditions)) {
//			return false;
//		}

		$this->body = serialize($this->_conditions);

		return true;
	}

	public function afterFind()
	{
		parent::afterFind();
		$this->_conditions = unserialize($this->body);
		if (empty($this->_conditions)) {
			$this->_conditions = [];
		}

		return true;
	}

	public function getConditions()
	{
		return $this->_conditions;
	}

	public function setConditions($conditions)
	{
		$this->_conditions = $conditions;
	}

	public function getId() {
		return $this->getPrimaryKey();
	}
}