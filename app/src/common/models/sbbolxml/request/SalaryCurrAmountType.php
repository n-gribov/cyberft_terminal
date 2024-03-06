<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing SalaryCurrAmountType
 *
 * Денежная сумма с указанием кода валюты для использования в зарплатной ведомости
 * XSD Type: SalaryCurrAmountType
 */
class SalaryCurrAmountType
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
     * Трёхбуквенный код валюты
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


}

