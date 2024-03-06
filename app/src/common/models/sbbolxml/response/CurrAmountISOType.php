<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CurrAmountISOType
 *
 * Денежная сумма с указанием ISO кода валюты
 * XSD Type: CurrAmountISOType
 */
class CurrAmountISOType
{

    /**
     * Сумма
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * ISO код валюты
     *
     * @property string $currCode
     */
    private $currCode = null;

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
     * ISO код валюты
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
     * ISO код валюты
     *
     * @param string $currCode
     * @return static
     */
    public function setCurrCode($currCode)
    {
        $this->currCode = $currCode;
        return $this;
    }


}

