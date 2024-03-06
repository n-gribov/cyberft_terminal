<?php
namespace common\base;

use yii\base\Exception;
use yii\base\DynamicModel as DM;

class DynamicModel extends DM
{
	private $_attributes = [];

	public function __get($name)
	{
		try {
			return parent::__get($name);
		} catch (Exception $e) {
			return null;
		}
	}

	public function __set($name, $value)
	{
		try {
			parent::__set($name, $value);
		} catch (Exception $e) {

		}
	}
}