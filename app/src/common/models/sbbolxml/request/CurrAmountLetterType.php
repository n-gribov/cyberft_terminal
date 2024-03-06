<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CurrAmountLetterType
 *
 * Денежная сумма с указанием трёхбуквенного кода валюты
 * XSD Type: CurrAmountLetterType
 */
class CurrAmountLetterType
{

    /**
     * Сумма
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * Трёхбуквенный код валюты
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
     * Трёхбуквенный код валюты
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
     * Трёхбуквенный код валюты
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

