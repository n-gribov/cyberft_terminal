<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DebtPaymentLoanICSType
 *
 * Описание графика платежей по возврату основного долга и процентных платежей
 * XSD Type: DebtPaymentLoanICS
 */
class DebtPaymentLoanICSType
{

    /**
     * ISO-код валюты договора (3-х буквенный код валюты)
     *
     * @property string $currIsoCode
     */
    private $currIsoCode = null;

    /**
     * Сумма платежа в счет основного долга
     *
     * @property float $principalPayment
     */
    private $principalPayment = null;

    /**
     * Дата платежа в счет основного долга
     *
     * @property \DateTime $principalDate
     */
    private $principalDate = null;

    /**
     * Сумма платежа в счет процентных платежей
     *
     * @property float $sumPay
     */
    private $sumPay = null;

    /**
     * Дата платежа в счет процентных платежей
     *
     * @property \DateTime $datePay
     */
    private $datePay = null;

    /**
     * Описание особых условий
     *
     * @property string $payComments
     */
    private $payComments = null;

    /**
     * Gets as currIsoCode
     *
     * ISO-код валюты договора (3-х буквенный код валюты)
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
     * ISO-код валюты договора (3-х буквенный код валюты)
     *
     * @param string $currIsoCode
     * @return static
     */
    public function setCurrIsoCode($currIsoCode)
    {
        $this->currIsoCode = $currIsoCode;
        return $this;
    }

    /**
     * Gets as principalPayment
     *
     * Сумма платежа в счет основного долга
     *
     * @return float
     */
    public function getPrincipalPayment()
    {
        return $this->principalPayment;
    }

    /**
     * Sets a new principalPayment
     *
     * Сумма платежа в счет основного долга
     *
     * @param float $principalPayment
     * @return static
     */
    public function setPrincipalPayment($principalPayment)
    {
        $this->principalPayment = $principalPayment;
        return $this;
    }

    /**
     * Gets as principalDate
     *
     * Дата платежа в счет основного долга
     *
     * @return \DateTime
     */
    public function getPrincipalDate()
    {
        return $this->principalDate;
    }

    /**
     * Sets a new principalDate
     *
     * Дата платежа в счет основного долга
     *
     * @param \DateTime $principalDate
     * @return static
     */
    public function setPrincipalDate(\DateTime $principalDate)
    {
        $this->principalDate = $principalDate;
        return $this;
    }

    /**
     * Gets as sumPay
     *
     * Сумма платежа в счет процентных платежей
     *
     * @return float
     */
    public function getSumPay()
    {
        return $this->sumPay;
    }

    /**
     * Sets a new sumPay
     *
     * Сумма платежа в счет процентных платежей
     *
     * @param float $sumPay
     * @return static
     */
    public function setSumPay($sumPay)
    {
        $this->sumPay = $sumPay;
        return $this;
    }

    /**
     * Gets as datePay
     *
     * Дата платежа в счет процентных платежей
     *
     * @return \DateTime
     */
    public function getDatePay()
    {
        return $this->datePay;
    }

    /**
     * Sets a new datePay
     *
     * Дата платежа в счет процентных платежей
     *
     * @param \DateTime $datePay
     * @return static
     */
    public function setDatePay(\DateTime $datePay)
    {
        $this->datePay = $datePay;
        return $this;
    }

    /**
     * Gets as payComments
     *
     * Описание особых условий
     *
     * @return string
     */
    public function getPayComments()
    {
        return $this->payComments;
    }

    /**
     * Sets a new payComments
     *
     * Описание особых условий
     *
     * @param string $payComments
     * @return static
     */
    public function setPayComments($payComments)
    {
        $this->payComments = $payComments;
        return $this;
    }


}

