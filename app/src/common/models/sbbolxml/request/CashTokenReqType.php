<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CashTokenReqType
 *
 * Заказ купюрности
 * XSD Type: CashTokenReq
 */
class CashTokenReqType
{

    /**
     * Количество
     *
     * @property integer $count
     */
    private $count = null;

    /**
     * Номинал купюры/монеты
     *
     * @property float $cashTokenValue
     */
    private $cashTokenValue = null;

    /**
     * Наименование купюры/монеты
     *
     * @property string $cashTokenName
     */
    private $cashTokenName = null;

    /**
     * Цифровой код валюты
     *
     * @property string $cashTokenCurrCode
     */
    private $cashTokenCurrCode = null;

    /**
     * Тип денежного знака
     *
     * @property integer $cashTokenType
     */
    private $cashTokenType = null;

    /**
     * Gets as count
     *
     * Количество
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Sets a new count
     *
     * Количество
     *
     * @param integer $count
     * @return static
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * Gets as cashTokenValue
     *
     * Номинал купюры/монеты
     *
     * @return float
     */
    public function getCashTokenValue()
    {
        return $this->cashTokenValue;
    }

    /**
     * Sets a new cashTokenValue
     *
     * Номинал купюры/монеты
     *
     * @param float $cashTokenValue
     * @return static
     */
    public function setCashTokenValue($cashTokenValue)
    {
        $this->cashTokenValue = $cashTokenValue;
        return $this;
    }

    /**
     * Gets as cashTokenName
     *
     * Наименование купюры/монеты
     *
     * @return string
     */
    public function getCashTokenName()
    {
        return $this->cashTokenName;
    }

    /**
     * Sets a new cashTokenName
     *
     * Наименование купюры/монеты
     *
     * @param string $cashTokenName
     * @return static
     */
    public function setCashTokenName($cashTokenName)
    {
        $this->cashTokenName = $cashTokenName;
        return $this;
    }

    /**
     * Gets as cashTokenCurrCode
     *
     * Цифровой код валюты
     *
     * @return string
     */
    public function getCashTokenCurrCode()
    {
        return $this->cashTokenCurrCode;
    }

    /**
     * Sets a new cashTokenCurrCode
     *
     * Цифровой код валюты
     *
     * @param string $cashTokenCurrCode
     * @return static
     */
    public function setCashTokenCurrCode($cashTokenCurrCode)
    {
        $this->cashTokenCurrCode = $cashTokenCurrCode;
        return $this;
    }

    /**
     * Gets as cashTokenType
     *
     * Тип денежного знака
     *
     * @return integer
     */
    public function getCashTokenType()
    {
        return $this->cashTokenType;
    }

    /**
     * Sets a new cashTokenType
     *
     * Тип денежного знака
     *
     * @param integer $cashTokenType
     * @return static
     */
    public function setCashTokenType($cashTokenType)
    {
        $this->cashTokenType = $cashTokenType;
        return $this;
    }


}

