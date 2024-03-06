<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing ParamType
 *
 * Отдельный параметр произвольного вида
 * XSD Type: Param
 */
class ParamType
{

    /**
     * Название параметра
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Значение параметра
     *
     * @property string $value
     */
    private $value = null;

    /**
     * Gets as name
     *
     * Название параметра
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Название параметра
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as value
     *
     * Значение параметра
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets a new value
     *
     * Значение параметра
     *
     * @param string $value
     * @return static
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


}

