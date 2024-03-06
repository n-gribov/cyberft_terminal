<?php

namespace common\models\raiffeisenxml\request\DealPassCred181IRaifType\SpecDataAType\PercentAType;

/**
 * Class representing LIBORAType
 */
class LIBORAType
{

    /**
     * Код ставки ЛИБОР
     *
     * @property string $value
     */
    private $value = null;

    /**
     * ISO код валюты
     *
     * @property string $isoCode
     */
    private $isoCode = null;

    /**
     * Gets as value
     *
     * Код ставки ЛИБОР
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
     * Код ставки ЛИБОР
     *
     * @param string $value
     * @return static
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

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


}

