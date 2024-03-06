<?php

namespace common\models\raiffeisenxml\request\DealPassCon138IType\ComDataAType;

/**
 * Class representing CurrAType
 */
class CurrAType
{

    /**
     * ISO код валюты
     *
     * @property string $isoCode
     */
    private $isoCode = null;

    /**
     * Цифровой код валюты
     *
     * @property string $digCode
     */
    private $digCode = null;

    /**
     * Gets as isoCode
     *
     * ISO код валюты
     *
     * @return string
     */
    public function getIsoCode()
    {
        return $this->isoCode;
    }

    /**
     * Sets a new isoCode
     *
     * ISO код валюты
     *
     * @param string $isoCode
     * @return static
     */
    public function setIsoCode($isoCode)
    {
        $this->isoCode = $isoCode;
        return $this;
    }

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


}

