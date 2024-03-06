<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing CountryNameType
 *
 *
 * XSD Type: CountryName
 */
class CountryNameType
{

    /**
     * Код страны иностранного контрагента
     *
     * @property string $code
     */
    private $code = null;

    /**
     * Название страны иностранного контрагента
     *  или "не указано"
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as code
     *
     * Код страны иностранного контрагента
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * Код страны иностранного контрагента
     *
     * @param string $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as name
     *
     * Название страны иностранного контрагента
     *  или "не указано"
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
     * Название страны иностранного контрагента
     *  или "не указано"
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


}

