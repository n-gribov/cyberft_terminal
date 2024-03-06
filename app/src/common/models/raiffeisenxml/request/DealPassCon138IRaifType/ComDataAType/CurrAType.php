<?php

namespace common\models\raiffeisenxml\request\DealPassCon138IRaifType\ComDataAType;

/**
 * Class representing CurrAType
 */
class CurrAType
{

    /**
     * Цифровой код валюты
     *
     * @property string $digCode
     */
    private $digCode = null;

    /**
     * Наименование валюты
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as digCode
     *
     * Цифровой код валюты
     *
     * @return string
     */
    public function getDigCode()
    {
        return $this->digCode;
    }

    /**
     * Sets a new digCode
     *
     * Цифровой код валюты
     *
     * @param string $digCode
     * @return static
     */
    public function setDigCode($digCode)
    {
        $this->digCode = $digCode;
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

