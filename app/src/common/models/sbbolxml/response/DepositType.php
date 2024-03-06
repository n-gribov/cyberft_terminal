<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DepositType
 *
 * Данные по депозиту
 * XSD Type: DepositType
 */
class DepositType
{

    /**
     * Вид вклада (депозита)
     *
     * @property string $depositType
     */
    private $depositType = null;

    /**
     * Сумма вклада (депозита)
     *
     * @property float $depositSum
     */
    private $depositSum = null;

    /**
     * Цифровой код валюты суммы вклада
     *
     * @property string $depositSumCurrCode
     */
    private $depositSumCurrCode = null;

    /**
     * Срок вклада (депозита)
     *
     * @property integer $depositTerm
     */
    private $depositTerm = null;

    /**
     * Дата начала действия договора
     *
     * @property \DateTime $depositFromDate
     */
    private $depositFromDate = null;

    /**
     * Дата окончания действия договора
     *
     * @property \DateTime $depositToDate
     */
    private $depositToDate = null;

    /**
     * Процентная ставка по договору
     *
     * @property float $interestRate
     */
    private $interestRate = null;

    /**
     * Процентная ставка при досрочном расторжении
     *
     * @property float $interestRateOnClose
     */
    private $interestRateOnClose = null;

    /**
     * Периодичность выплаты процентов
     *
     * @property string $interestPayment
     */
    private $interestPayment = null;

    /**
     * Сумма выплаченных процентов
     *
     * @property float $paymentAmount
     */
    private $paymentAmount = null;

    /**
     * Цифровой код валюты
     *
     * @property string $paymentCurrCode
     */
    private $paymentCurrCode = null;

    /**
     * Сумма начисленных процентов
     *
     * @property float $accruedAmount
     */
    private $accruedAmount = null;

    /**
     * Цифровой код валюты
     *
     * @property string $accruedCurrCode
     */
    private $accruedCurrCode = null;

    /**
     * Признак возможности пополнения
     *
     * @property boolean $amountChange
     */
    private $amountChange = null;

    /**
     * Возможность пополнить до
     *
     * @property \DateTime $fillUpDate
     */
    private $fillUpDate = null;

    /**
     * Признак возможности отзыва
     *
     * @property boolean $review
     */
    private $review = null;

    /**
     * Возможность отозвать не ранее
     *
     * @property \DateTime $reviewFromDate
     */
    private $reviewFromDate = null;

    /**
     * Gets as depositType
     *
     * Вид вклада (депозита)
     *
     * @return string
     */
    public function getDepositType()
    {
        return $this->depositType;
    }

    /**
     * Sets a new depositType
     *
     * Вид вклада (депозита)
     *
     * @param string $depositType
     * @return static
     */
    public function setDepositType($depositType)
    {
        $this->depositType = $depositType;
        return $this;
    }

    /**
     * Gets as depositSum
     *
     * Сумма вклада (депозита)
     *
     * @return float
     */
    public function getDepositSum()
    {
        return $this->depositSum;
    }

    /**
     * Sets a new depositSum
     *
     * Сумма вклада (депозита)
     *
     * @param float $depositSum
     * @return static
     */
    public function setDepositSum($depositSum)
    {
        $this->depositSum = $depositSum;
        return $this;
    }

    /**
     * Gets as depositSumCurrCode
     *
     * Цифровой код валюты суммы вклада
     *
     * @return string
     */
    public function getDepositSumCurrCode()
    {
        return $this->depositSumCurrCode;
    }

    /**
     * Sets a new depositSumCurrCode
     *
     * Цифровой код валюты суммы вклада
     *
     * @param string $depositSumCurrCode
     * @return static
     */
    public function setDepositSumCurrCode($depositSumCurrCode)
    {
        $this->depositSumCurrCode = $depositSumCurrCode;
        return $this;
    }

    /**
     * Gets as depositTerm
     *
     * Срок вклада (депозита)
     *
     * @return integer
     */
    public function getDepositTerm()
    {
        return $this->depositTerm;
    }

    /**
     * Sets a new depositTerm
     *
     * Срок вклада (депозита)
     *
     * @param integer $depositTerm
     * @return static
     */
    public function setDepositTerm($depositTerm)
    {
        $this->depositTerm = $depositTerm;
        return $this;
    }

    /**
     * Gets as depositFromDate
     *
     * Дата начала действия договора
     *
     * @return \DateTime
     */
    public function getDepositFromDate()
    {
        return $this->depositFromDate;
    }

    /**
     * Sets a new depositFromDate
     *
     * Дата начала действия договора
     *
     * @param \DateTime $depositFromDate
     * @return static
     */
    public function setDepositFromDate(\DateTime $depositFromDate)
    {
        $this->depositFromDate = $depositFromDate;
        return $this;
    }

    /**
     * Gets as depositToDate
     *
     * Дата окончания действия договора
     *
     * @return \DateTime
     */
    public function getDepositToDate()
    {
        return $this->depositToDate;
    }

    /**
     * Sets a new depositToDate
     *
     * Дата окончания действия договора
     *
     * @param \DateTime $depositToDate
     * @return static
     */
    public function setDepositToDate(\DateTime $depositToDate)
    {
        $this->depositToDate = $depositToDate;
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
     * Gets as interestRateOnClose
     *
     * Процентная ставка при досрочном расторжении
     *
     * @return float
     */
    public function getInterestRateOnClose()
    {
        return $this->interestRateOnClose;
    }

    /**
     * Sets a new interestRateOnClose
     *
     * Процентная ставка при досрочном расторжении
     *
     * @param float $interestRateOnClose
     * @return static
     */
    public function setInterestRateOnClose($interestRateOnClose)
    {
        $this->interestRateOnClose = $interestRateOnClose;
        return $this;
    }

    /**
     * Gets as interestPayment
     *
     * Периодичность выплаты процентов
     *
     * @return string
     */
    public function getInterestPayment()
    {
        return $this->interestPayment;
    }

    /**
     * Sets a new interestPayment
     *
     * Периодичность выплаты процентов
     *
     * @param string $interestPayment
     * @return static
     */
    public function setInterestPayment($interestPayment)
    {
        $this->interestPayment = $interestPayment;
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
     * Gets as paymentCurrCode
     *
     * Цифровой код валюты
     *
     * @return string
     */
    public function getPaymentCurrCode()
    {
        return $this->paymentCurrCode;
    }

    /**
     * Sets a new paymentCurrCode
     *
     * Цифровой код валюты
     *
     * @param string $paymentCurrCode
     * @return static
     */
    public function setPaymentCurrCode($paymentCurrCode)
    {
        $this->paymentCurrCode = $paymentCurrCode;
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

    /**
     * Gets as accruedCurrCode
     *
     * Цифровой код валюты
     *
     * @return string
     */
    public function getAccruedCurrCode()
    {
        return $this->accruedCurrCode;
    }

    /**
     * Sets a new accruedCurrCode
     *
     * Цифровой код валюты
     *
     * @param string $accruedCurrCode
     * @return static
     */
    public function setAccruedCurrCode($accruedCurrCode)
    {
        $this->accruedCurrCode = $accruedCurrCode;
        return $this;
    }

    /**
     * Gets as amountChange
     *
     * Признак возможности пополнения
     *
     * @return boolean
     */
    public function getAmountChange()
    {
        return $this->amountChange;
    }

    /**
     * Sets a new amountChange
     *
     * Признак возможности пополнения
     *
     * @param boolean $amountChange
     * @return static
     */
    public function setAmountChange($amountChange)
    {
        $this->amountChange = $amountChange;
        return $this;
    }

    /**
     * Gets as fillUpDate
     *
     * Возможность пополнить до
     *
     * @return \DateTime
     */
    public function getFillUpDate()
    {
        return $this->fillUpDate;
    }

    /**
     * Sets a new fillUpDate
     *
     * Возможность пополнить до
     *
     * @param \DateTime $fillUpDate
     * @return static
     */
    public function setFillUpDate(\DateTime $fillUpDate)
    {
        $this->fillUpDate = $fillUpDate;
        return $this;
    }

    /**
     * Gets as review
     *
     * Признак возможности отзыва
     *
     * @return boolean
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Sets a new review
     *
     * Признак возможности отзыва
     *
     * @param boolean $review
     * @return static
     */
    public function setReview($review)
    {
        $this->review = $review;
        return $this;
    }

    /**
     * Gets as reviewFromDate
     *
     * Возможность отозвать не ранее
     *
     * @return \DateTime
     */
    public function getReviewFromDate()
    {
        return $this->reviewFromDate;
    }

    /**
     * Sets a new reviewFromDate
     *
     * Возможность отозвать не ранее
     *
     * @param \DateTime $reviewFromDate
     * @return static
     */
    public function setReviewFromDate(\DateTime $reviewFromDate)
    {
        $this->reviewFromDate = $reviewFromDate;
        return $this;
    }


}

