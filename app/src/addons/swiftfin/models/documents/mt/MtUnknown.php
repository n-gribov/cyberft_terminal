<?php
namespace addons\swiftfin\models\documents\mt;

use Yii;
use yii\base\Model;

/**
 * MtUnknown Document
 * Служит для преодоления проблемы непределимого типа содержимого документа.
 * Данный объект создается как реакция на "битый" документ, который невзирая 
 * ни на что должен нормально пройти через Терминал и зарегистрироваться.
 *
 * Содержит набор функций-заглушек.
 */

class MtUnknown extends Model {

	public $label;
	public $formable = false;
	public $view;

	protected $_data;
	
	/**
	 * 
	 * @return array
	 */
	public function attributeTags()
	{
		return [];
	}

	/**
	 * 
	 * @return array
	 */
	public function attributeLabels()
	{
		return [
			'data'	=> Yii::t('doc/mt', 'Document data'),
		];
	}
	
	/**
	 * @return string
	 */
	public function getOperationReference() {
		return '';
	}
	
	/**
	 * @return null
	 */
	public function getCurrency() 
	{
		return null;
	}

	/**
	 * @return null
	 */
	public function getSum() {
		return null;
	}

	/**
	 * @return null
	 */
	public function getDate() {
		return null;
	}		

	/**
	 * @return string
	 */
	public function getType()
	{
		return '0';
	}

	/**
	 * 
	 * @param string $value
	 */
	public function setType($value) 
	{
	}
	
	/**
	 * 
	 * @param string $attribute
	 * @return string
	 */
	public function getAttributeConfig($attribute)
	{
		return '';
	}
	
	public function scenarios()
	{
		return [
			// Твик, для того чтобы срабатывал load()
			self::SCENARIO_DEFAULT => $this->attributes()
		];
	}

	public function getAttributeTag($attribute)
	{
		$tags = $this->getAttributeSafeTags();
		return isset($tags[$attribute]) ? $tags[$attribute] : '';
	}
	
	public function getTagAttribute($tag)
	{
		$tags = $this->getAttributeSafeTags();		
		$attributes = array_flip($tags);
		
		return isset($attributes[$tag]) ? $attributes[$tag] : '';
	}
	
	public function getAttributeSafeTags()
	{
		$tags = $this->attributeTags();

		// Выдаем только те теги, к которым имеются аттрибуты
		$tags = array_intersect_key($tags, array_flip($this->attributes()));

		return $tags;		
	}

	public function setBody($value)
	{
		$this->_data = $value;
	}

	/**
	 * Тело документа, то есть собранные в строку аттрибуты модели
	 * @return type
	 */
	public function getBody()
	{
		return $this->_data;
	}
	
	public function getDataReadable()
	{
		return '-- No readable data available --';
	}
	
	/**
	 * Загрузить данные из строки в MT формате
	 * @param $data
	 * @return bool
	 */
	protected function loadFromString($data)
	{
		$this->setData($data);

		return true;
	}

	/**
	 * Валидация данного МТ документа всегда обречена на провал
	 * 
	 * @return boolean
	 */
	public function validateExternal()
	{
		$this->addError('data', 'Unknown or empty document type');
		return false;
	}
	
	/**
	 * Валидация данного МТ документа всегда обречена на провал
	 * 
	 * @return boolean
	 */
	public function validate($attributeNames = null, $clearErrors = true)
	{
		return $this->validateExternal();
	}

	/**
	 * Функция возвращает значение атрибута Дата валютирования
	 * @return string
	 */
	public function getValueDate()
	{
		return null;
	}
	
}
