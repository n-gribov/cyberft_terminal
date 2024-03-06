<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing PermBalanceType
 *
 * Данные по продукту
 * XSD Type: PermBalanceType
 */
class PermBalanceType
{

    /**
     * Наименование продукта
     *
     * @property string $pbType
     */
    private $pbType = null;

    /**
     * Специальные условия. Возможные значения: "Нет", "5%", "10%"
     *
     * @property string $pbCondition
     */
    private $pbCondition = null;

    /**
     * Сумма неснижаемого остатка
     *
     * @property float $pbSum
     */
    private $pbSum = null;

    /**
     * Цифровой код валюты суммы НСО
     *
     * @property string $pbSumCurrCode
     */
    private $pbSumCurrCode = null;

    /**
     * Срок поддержания НСО
     *
     * @property integer $pbTerm
     */
    private $pbTerm = null;

    /**
     * Дата начала действия договора
     *
     * @property \DateTime $pbFromDate
     */
    private $pbFromDate = null;

    /**
     * Дата окончания действия договора
     *
     * @property \DateTime $pbToDate
     */
    private $pbToDate = null;

    /**
     * Процентная ставка по договору
     *
     * @property float $interestRate
     */
    private $interestRate = null;

    /**
     * Сумма выплаченных процентов
     *
     * @property float $paymentAmount
     */
    private $paymentAmount = null;

    /**
     * Сумма начисленных процентов
     *
     * @property float $accruedAmount
     */
    private $accruedAmount = null;

    /**
     * Gets as pbType
     *
     * Наименование продукта
     *
     * @return string
     */
    public function getPbType()
    {
        return $this->pbType;
    }

    /**
     * Sets a new pbType
     *
     * Наименование продукта
     *
     * @param string $pbType
     * @return static
     */
    public function setPbType($pbType)
    {
        $this->pbType = $pbType;
        return $this;
    }

    /**
     * Gets as pbCondition
     *
     * Специальные условия. Возможные значения: "Нет", "5%", "10%"
     *
     * @return string
     */
    public function getPbCondition()
    {
        return $this->pbCondition;
    }

    /**
     * Sets a new pbCondition
     *
     * Специальные условия. Возможные значения: "Нет", "5%", "10%"
     *
     * @param string $pbCondition
     * @return static
     */
    public function setPbCondition($pbCondition)
    {
        $this->pbCondition = $pbCondition;
        return $this;
    }

    /**
     * Gets as pbSum
     *
     * Сумма неснижаемого остатка
     *
     * @return float
     */
    public function getPbSum()
    {
        return $this->pbSum;
    }

    /**
     * Sets a new pbSum
     *
     * Сумма неснижаемого остатка
     *
     * @param float $pbSum
     * @return static
     */
    public function setPbSum($pbSum)
    {
        $this->pbSum = $pbSum;
        return $this;
    }

    /**
     * Gets as pbSumCurrCode
     *
     * Цифровой код валюты суммы НСО
     *
     * @return string
     */
    public function getPbSumCurrCode()
    {
        return $this->pbSumCurrCode;
    }

    /**
     * Sets a new pbSumCurrCode
     *
     * Цифровой код валюты суммы НСО
     *
     * @param string $pbSumCurrCode
     * @return static
     */
    public function setPbSumCurrCode($pbSumCurrCode)
    {
        $this->pbSumCurrCode = $pbSumCurrCode;
        return $this;
    }

    /**
     * Gets as pbTerm
     *
     * Срок поддержания НСО
     *
     * @return integer
     */
    public function getPbTerm()
    {
        return $this->pbTerm;
    }

    /**
     * Sets a new pbTerm
     *
     * Срок поддержания НСО
     *
     * @param integer $pbTerm
     * @return static
     */
    public function setPbTerm($pbTerm)
    {
        $this->pbTerm = $pbTerm;
        return $this;
    }

    /**
     * Gets as pbFromDate
     *
     * Дата начала действия договора
     *
     * @return \DateTime
     */
    public function getPbFromDate()
    {
        return $this->pbFromDate;
    }

    /**
     * Sets a new pbFromDate
     *
     * Дата начала действия договора
     *
     * @param \DateTime $pbFromDate
     * @return static
     */
    public function setPbFromDate(\DateTime $pbFromDate)
    {
        $this->pbFromDate = $pbFromDate;
        return $this;
    }

    /**
     * Gets as pbToDate
     *
     * Дата окончания действия договора
     *
     * @return \DateTime
     */
    public function getPbToDate()
    {
        return $this->pbToDate;
    }

    /**
     * Sets a new pbToDate
     *
     * Дата окончания действия договора
     *
     * @param \DateTime $pbToDate
     * @return static
     */
    public function setPbToDate(\DateTime $pbToDate)
    {
        $this->pbToDate = $pbToDate;
        return $this;
    }

    /**
     * Gets as interestRate
     *
     * Процентная ставка по договору
     *
     * @return float
     */
    public function getInterestRate()
    {
        return $this->interestRate;
    }

    /**
     * Sets a new interestRate
     *
     * Процентная ставка по договору
     *
     * @param float $interestRate
     * @return static
     */
    public function setInterestRate($interestRate)
    {
        $this->interestRate = $interestRate;
        return $this;
    }

    /**
     * Gets as paymentAmount
     *
     * Сумма выплаченных процентов
     *
     * @return float
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    /**
     * Sets a new paymentAmount
     *
     * Сумма выплаченных процентов
     *
     * @param float $paymentAmount
     * @return static
     */
    public function setPaymentAmount($paymentAmount)
    {
        $this->paymentAmount = $paymentAmount;
        return $this;
    }

    /**
     * Gets as accruedAmount
     *
     * Сумма начисленных процентов
     *
     * @return float
     */
    public function getAccruedAmount()
    {
        return $this->accruedAmount;
    }

    /**
     * Sets a new accruedAmount
     *
     * Сумма начисленных процентов
     *
     * @param float $accruedAmount
     * @return static
     */
    public function setAccruedAmount($accruedAmount)
    {
        $this->accruedAmount = $accruedAmount;
        return $this;
    }


}

