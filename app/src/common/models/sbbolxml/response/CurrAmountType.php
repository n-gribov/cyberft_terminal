<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CurrAmountType
 *
 * Денежная сумма с указанием трёхзначного кода валюты (сумма может отсутствовать)
 * XSD Type: CurrAmountType
 */
class CurrAmountType
{

    /**
     * Сумма
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * Цифровой код валюты
     *
     * @property string $currCode
     */
    private $currCode = null;

    /**
     * ISO код валюты
     *
     * @property string $currIsoCode
     */
    private $currIsoCode = null;

    /**
     * Gets as docSum
     *
     * Сумма
     *
     * @return float
     */
    public function getDocSum()
    {
        return $this->docSum;
    }

    /**
     * Sets a new docSum
     *
     * Сумма
     *
     * @param float $docSum
     * @return static
     */
    public function setDocSum($docSum)
    {
        $this->docSum = $docSum;
        return $this;
    }

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
     * ISO код валюты
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
     * ISO код валюты
     *
     * @param string $currIsoCode
     * @return static
     */
    public function setCurrIsoCode($currIsoCode)
    {
        $this->currIsoCode = $currIsoCode;
        return $this;
    }


}
