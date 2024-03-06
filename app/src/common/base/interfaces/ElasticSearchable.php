<?php
namespace common\base\interfaces;
/**
 * Для документов, которые хотят индексироваться в Elastic Search
 *
 * @author a.nikolaenko
 */
interface ElasticSearchable
{
	//public $uuid;

	/**
	 * Получить уникальный ид для хранения
	 * @return string
	 */
	public function getSearchId();

	/**
	 * Получить тип документа для хранения
	 * Тип является как бы "таблицей" в базе Elastic, т.е. это
	 * не обязательно исконный тип документа, а скорее serviceId модуля документа
	 * @return string
	 */
	public function getSearchType();

	/**
	 * Получить набор полей для хранения
	 * @return array
	 */
	public function getSearchFields();

}