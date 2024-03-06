<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing FinalDebtPrincipalDataType
 *
 * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
 * XSD Type: FinalDebtPrincipalData
 */
class FinalDebtPrincipalDataType
{

    /**
     * Дата расчета
     *
     * @property \DateTime $calculationDate
     */
    private $calculationDate = null;

    /**
     * Код валюты
     *
     * @property string $currCode
     */
    private $currCode = null;

    /**
     * Код валюты
     *
     * @property float $loanAmount
     */
    private $loanAmount = null;

    /**
     * Код валюты
     *
     * @property float $enrollAmount
     */
    private $enrollAmount = null;

    /**
     * Код валюты
     *
     * @property float $decreaseAmount
     */
    private $decreaseAmount = null;

    /**
     * Код валюты
     *
     * @property float $increaseAmount
     */
    private $increaseAmount = null;

    /**
     * Код валюты
     *
     * @property float $debtPrincipalAmount
     */
    private $debtPrincipalAmount = null;

    /**
     * Gets as calculationDate
     *
     * Дата расчета
     *
     * @return \DateTime
     */
    public function getCalculationDate()
    {
        return $this->calculationDate;
    }

    /**
     * Sets a new calculationDate
     *
     * Дата расчета
     *
     * @param \DateTime $calculationDate
     * @return static
     */
    public function setCalculationDate(\DateTime $calculationDate)
    {
        $this->calculationDate = $calculationDate;
        return $this;
    }

    /**
     * Gets as currCode
     *
     * Код валюты
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
     * Код валюты
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
     * Gets as loanAmount
     *
     * Код валюты
     *
     * @return float
     */
    public function getLoanAmount()
    {
        return $this->loanAmount;
    }

    /**
     * Sets a new loanAmount
     *
     * Код валюты
     *
     * @param float $loanAmount
     * @return static
     */
    public function setLoanAmount($loanAmount)
    {
        $this->loanAmount = $loanAmount;
        return $this;
    }

    /**
     * Gets as enrollAmount
     *
     * Код валюты
     *
     * @return float
     */
    public function getEnrollAmount()
    {
        return $this->enrollAmount;
    }

    /**
     * Sets a new enrollAmount
     *
     * Код валюты
     *
     * @param float $enrollAmount
     * @return static
     */
    public function setEnrollAmount($enrollAmount)
    {
        $this->enrollAmount = $enrollAmount;
        return $this;
    }

    /**
     * Gets as decreaseAmount
     *
     * Код валюты
     *
     * @return float
     */
    public function getDecreaseAmount()
    {
        return $this->decreaseAmount;
    }

    /**
     * Sets a new decreaseAmount
     *
     * Код валюты
     *
     * @param float $decreaseAmount
     * @return static
     */
    public function setDecreaseAmount($decreaseAmount)
    {
        $this->decreaseAmount = $decreaseAmount;
        return $this;
    }

    /**
     * Gets as increaseAmount
     *
     * Код валюты
     *
     * @return float
     */
    public function getIncreaseAmount()
    {
        return $this->increaseAmount;
    }

    /**
     * Sets a new increaseAmount
     *
     * Код валюты
     *
     * @param float $increaseAmount
     * @return static
     */
    public function setIncreaseAmount($increaseAmount)
    {
        $this->increaseAmount = $increaseAmount;
        return $this;
    }

    /**
     * Gets as debtPrincipalAmount
     *
     * Код валюты
     *
     * @return float
     */
    public function getDebtPrincipalAmount()
    {
        return $this->debtPrincipalAmount;
    }

    /**
     * Sets a new debtPrincipalAmount
     *
     * Код валюты
     *
     * @param float $debtPrincipalAmount
     * @return static
     */
    public function setDebtPrincipalAmount($debtPrincipalAmount)
    {
        $this->debtPrincipalAmount = $debtPrincipalAmount;
        return $this;
    }


}

