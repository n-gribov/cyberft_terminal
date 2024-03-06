<?php
namespace common\base\interfaces;

/**
 * Интерфейс для документов, которые могут индексироваться в ElasticSearch
 */
interface ElasticSearchable
{
    /**
     * Метод получает уникальный ид для хранения
     * @return string
     */
    public function getSearchId();

    /**
     * Метод получает тип документа для хранения
     * Тип является как бы "таблицей" в базе Elastic, т.е. это
     * не обязательно исконный тип документа, а скорее serviceId модуля документа
     * @return string
     */
    public function getSearchType();

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields();

}
