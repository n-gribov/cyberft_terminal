<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CashOrderPurposeType
 *
 * Цель снятия
 * XSD Type: CashOrderPurpose
 */
class CashOrderPurposeType
{

    /**
     * Код символа кассовой отчетности
     *
     * @property string $cashSymbolCode
     */
    private $cashSymbolCode = null;

    /**
     * Наименование символа кассовой отчетности
     *
     * @property string $cashSymbolName
     */
    private $cashSymbolName = null;

    /**
     * Сумма
     *
     * @property float $amount
     */
    private $amount = null;

    /**
     * Gets as cashSymbolCode
     *
     * Код символа кассовой отчетности
     *
     * @return string
     */
    public function getCashSymbolCode()
    {
        return $this->cashSymbolCode;
    }

    /**
     * Sets a new cashSymbolCode
     *
     * Код символа кассовой отчетности
     *
     * @param string $cashSymbolCode
     * @return static
     */
    public function setCashSymbolCode($cashSymbolCode)
    {
        $this->cashSymbolCode = $cashSymbolCode;
        return $this;
    }

    /**
     * Gets as cashSymbolName
     *
     * Наименование символа кассовой отчетности
     *
     * @return string
     */
    public function getCashSymbolName()
    {
        return $this->cashSymbolName;
    }

    /**
     * Sets a new cashSymbolName
     *
     * Наименование символа кассовой отчетности
     *
     * @param string $cashSymbolName
     * @return static
     */
    public function setCashSymbolName($cashSymbolName)
    {
        $this->cashSymbolName = $cashSymbolName;
        return $this;
    }

    /**
     * Gets as amount
     *
     * Сумма
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Sets a new amount
     *
     * Сумма
     *
     * @param float $amount
     * @return static
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }


}

