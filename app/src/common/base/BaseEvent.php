<?php

namespace common\base;

use ReflectionClass;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

abstract class BaseEvent extends Model
{
	const CLASS_NAME_PREFIX = 'Event';
	
	protected $_id;
	protected $_date;
	protected $_code;
	
	protected $_eventData;

	public function init()
	{
		parent::init();
		
		// Создаем значение code - сокращенное имя события
		$myClassName = (new ReflectionClass(get_called_class()))->getShortName();
		if (StringHelper::startsWith($myClassName, static::CLASS_NAME_PREFIX)) {
			$myClassName = substr($myClassName, strlen(static::CLASS_NAME_PREFIX));
		}
		$this->setCode(Inflector::variablize($myClassName));
	}
	
	public static function getInstance($code, $params)
	{
		$myClass = new ReflectionClass(get_called_class());
		$className = $myClass->getNamespaceName() . '\Event' . ucfirst($code);
		if (class_exists($className)) {
			return new $className($params);
		}

		return null;
	}

	/**
	 * Возвращает данные в неком читабельном виде
	 * @return string
	 */
	public function __toString()
	{
		$result = "Event '{$this->_code}' on {$this->_date}, event data: {$this->serialize()}";

		return $result;
	}

	/**
	 * Сериализует данные для хранения в БД
	 * @return string
	 */
	public function serialize()
	{
		return json_encode($this->getEventData());
	}

	/**
	 * Преобразует данные из сериализованного вида
	 * @param type $jsonData
	 * @return array
	 */
	public function deserialize($jsonData)
	{
		return json_decode($jsonData, true);
	}

	public function setId($id)
	{
		$this->_id = $id;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function setDate($date)
	{
		$this->_date = $date;
	}

	public function getDate()
	{
		return $this->_date;
	}

	public function setCode($s)
	{
		$this->_code = $s;
	}


	public function getCode()
	{
		return $this->_code;
	}

	/**
	 * @param array $data
	 */
	public function setEventData($data)
	{
		if (is_string($data)) {
			$this->_eventData = $this->deserialize($data);
		} else {
			$this->_eventData = $data;
		}
		$this->populateAttributes();
	}

	/**
	 * @return array
	 */
	public function getEventData()
	{
		$this->packAttributes();
		
		return $this->_eventData;
	}
	
	/**
	 * Функция наполняет public-атрибуты класса значениями из _eventData
	 */
	protected function populateAttributes()
	{
		foreach ($this->attributes() as $attribute) {
			if (isset($this->_eventData[$attribute])) {
				$this->$attribute = $this->_eventData[$attribute];
			}
		}
	}
	
	/**
	 * Функция помещает значения public-атрибутов класса в массив _eventData
	 */
	protected function packAttributes()
	{
		$this->_eventData = [];
		foreach ($this->attributes as $attribute => $value) {
			$this->_eventData[$attribute] = $value;
		}
	}	

}
