<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CurrencyType
 *
 * Валюта
 * XSD Type: CurrencyType
 */
class CurrencyType
{

    /**
     * Цифровой код валюты
     *
     * @property string $currCode
     */
    private $currCode = null;

    /**
     * Трёхбуквенный код валюты
     *
     * @property string $currIsoCode
     */
    private $currIsoCode = null;

    /**
     * Наименование валюты
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as currCode
     *
     * Цифровой код валюты
     *
     * @return string
     */
    public function getCurrCode()
    {
        return $this->currCode;
    }

    /**
     * Sets a new currCode
     *
     * Цифровой код валюты
     *
     * @param string $currCode
     * @return static
     */
    public function setCurrCode($currCode)
    {
        $this->currCode = $currCode;
        return $this;
    }

    /**
     * Gets as currIsoCode
     *
     * Трёхбуквенный код валюты
     *
     * @return string
     */
    public function getCurrIsoCode()
    {
        return $this->currIsoCode;
    }

    /**
     * Sets a new currIsoCode
     *
     * Трёхбуквенный код валюты
     *
     * @param string $currIsoCode
     * @return static
     */
    public function setCurrIsoCode($currIsoCode)
    {
        $this->currIsoCode = $currIsoCode;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование валюты
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
     * Наименование валюты
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

