<?php
namespace common\base;

use common\base\interfaces\ElasticSearchable;
use Exception;
use Yii;

abstract class BaseType extends Model implements ElasticSearchable
{
    const TYPE = 'BaseType';
    const TRANSPORT_TYPE = null;

    // Заполняет модель распарсенными данными из строки
    public function loadFromString($data, $isFile = false, $encoding = null)
    {
        return false;
    }

    public function __toString()
    {
        // method __toString must not throw exception
        try {
            return $this->getModelDataAsString(false); // keep xml declaration
        } catch(Exception $ex) {
            Yii::error($ex->getMessage());

            return '';
        }
    }

    // Возвращает модель как строку
    public function getModelDataAsString()
    {
        return '';
    }

    public function getType()
    {
        return static::TYPE;
    }

	public function getSearchType()
	{
		return $this->getType();
	}

	public function getSearchId()
	{
		return $this->id;
	}

    public function __get($name)
    {
        if (strpos($name, '.') !== false) {
            $parts = explode('.', $name);
            $related = parent::__get($parts[0]);

            return $related->{$parts[1]};
        }

        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if (strpos($name, '.') !== false) {
            $parts = explode('.', $name);
            $related = parent::__get($parts[0]);
            $related->{$parts[1]} = $value;

            return;
        }

        parent::__set($name, $value);
    }

    public function onUnsafeAttribute($name, $value)
    {
        if (strpos($name, '.') !== false) {
            $parts = explode('.', $name);
            $related = parent::__get($parts[0]);
            $related->{$parts[1]} = $value;

            return;
        }

        parent::onUnsafeAttribute($name, $value);
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return array|bool
     */
    public abstract function getSearchFields();

    public function getTransportType()
    {
        return static::TRANSPORT_TYPE ?: $this->getType();
    }
}
