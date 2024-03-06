<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing FinalTransactDataType
 *
 * Итоговые данные расчетов по контракту - раздел V ВБК
 * XSD Type: FinalTransactData
 */
class FinalTransactDataType
{

    /**
     * Дата расчета
     *
     * @property \DateTime $transactionDate
     */
    private $transactionDate = null;

    /**
     * Сумма денежных средств,
     *  поступивших по контракту в пользу резидента (всего зачислено)
     *
     * @property float $totalCredit
     */
    private $totalCredit = null;

    /**
     * Сумма денежных средств,
     *  переведенных по контракту в пользу нерезидента (всего списано)
     *
     * @property float $totalDebit
     */
    private $totalDebit = null;

    /**
     * Сумма по подтверждающим документам,
     *  увеличивающим обязательства нерезидента
     *
     * @property float $incNonresidLiabilitySum
     */
    private $incNonresidLiabilitySum = null;

    /**
     * Сумма по подтверждающим документам,
     *  увеличивающим обязательства резидента
     *
     * @property float $incResidentLiabilitySum
     */
    private $incResidentLiabilitySum = null;

    /**
     * Сумма по подтверждающим документам,
     *  уменьшающим обязательства нерезидента перед резидентом
     *
     * @property float $decNonresToResidentLiabSum
     */
    private $decNonresToResidentLiabSum = null;

    /**
     * Сумма по подтверждающим документам,
     *  уменьшающим обязательства резидента перед нерезидентом
     *
     * @property float $decResidentToNonresLiabSum
     */
    private $decResidentToNonresLiabSum = null;

    /**
     * Сальдо расчетов
     *
     * @property float $balance
     */
    private $balance = null;

    /**
     * Gets as transactionDate
     *
     * Дата расчета
     *
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * Sets a new transactionDate
     *
     * Дата расчета
     *
     * @param \DateTime $transactionDate
     * @return static
     */
    public function setTransactionDate(\DateTime $transactionDate)
    {
        $this->transactionDate = $transactionDate;
        return $this;
    }

    /**
     * Gets as totalCredit
     *
     * Сумма денежных средств,
     *  поступивших по контракту в пользу резидента (всего зачислено)
     *
     * @return float
     */
    public function getTotalCredit()
    {
        return $this->totalCredit;
    }

    /**
     * Sets a new totalCredit
     *
     * Сумма денежных средств,
     *  поступивших по контракту в пользу резидента (всего зачислено)
     *
     * @param float $totalCredit
     * @return static
     */
    public function setTotalCredit($totalCredit)
    {
        $this->totalCredit = $totalCredit;
        return $this;
    }

    /**
     * Gets as totalDebit
     *
     * Сумма денежных средств,
     *  переведенных по контракту в пользу нерезидента (всего списано)
     *
     * @return float
     */
    public function getTotalDebit()
    {
        return $this->totalDebit;
    }

    /**
     * Sets a new totalDebit
     *
     * Сумма денежных средств,
     *  переведенных по контракту в пользу нерезидента (всего списано)
     *
     * @param float $totalDebit
     * @return static
     */
    public function setTotalDebit($totalDebit)
    {
        $this->totalDebit = $totalDebit;
        return $this;
    }

    /**
     * Gets as incNonresidLiabilitySum
     *
     * Сумма по подтверждающим документам,
     *  увеличивающим обязательства нерезидента
     *
     * @return float
     */
    public function getIncNonresidLiabilitySum()
    {
        return $this->incNonresidLiabilitySum;
    }

    /**
     * Sets a new incNonresidLiabilitySum
     *
     * Сумма по подтверждающим документам,
     *  увеличивающим обязательства нерезидента
     *
     * @param float $incNonresidLiabilitySum
     * @return static
     */
    public function setIncNonresidLiabilitySum($incNonresidLiabilitySum)
    {
        $this->incNonresidLiabilitySum = $incNonresidLiabilitySum;
        return $this;
    }

    /**
     * Gets as incResidentLiabilitySum
     *
     * Сумма по подтверждающим документам,
     *  увеличивающим обязательства резидента
     *
     * @return float
     */
    public function getIncResidentLiabilitySum()
    {
        return $this->incResidentLiabilitySum;
    }

    /**
     * Sets a new incResidentLiabilitySum
     *
     * Сумма по подтверждающим документам,
     *  увеличивающим обязательства резидента
     *
     * @param float $incResidentLiabilitySum
     * @return static
     */
    public function setIncResidentLiabilitySum($incResidentLiabilitySum)
    {
        $this->incResidentLiabilitySum = $incResidentLiabilitySum;
        return $this;
    }

    /**
     * Gets as decNonresToResidentLiabSum
     *
     * Сумма по подтверждающим документам,
     *  уменьшающим обязательства нерезидента перед резидентом
     *
     * @return float
     */
    public function getDecNonresToResidentLiabSum()
    {
        return $this->decNonresToResidentLiabSum;
    }

    /**
     * Sets a new decNonresToResidentLiabSum
     *
     * Сумма по подтверждающим документам,
     *  уменьшающим обязательства нерезидента перед резидентом
     *
     * @param float $decNonresToResidentLiabSum
     * @return static
     */
    public function setDecNonresToResidentLiabSum($decNonresToResidentLiabSum)
    {
        $this->decNonresToResidentLiabSum = $decNonresToResidentLiabSum;
        return $this;
    }

    /**
     * Gets as decResidentToNonresLiabSum
     *
     * Сумма по подтверждающим документам,
     *  уменьшающим обязательства резидента перед нерезидентом
     *
     * @return float
     */
    public function getDecResidentToNonresLiabSum()
    {
        return $this->decResidentToNonresLiabSum;
    }

    /**
     * Sets a new decResidentToNonresLiabSum
     *
     * Сумма по подтверждающим документам,
     *  уменьшающим обязательства резидента перед нерезидентом
     *
     * @param float $decResidentToNonresLiabSum
     * @return static
     */
    public function setDecResidentToNonresLiabSum($decResidentToNonresLiabSum)
    {
        $this->decResidentToNonresLiabSum = $decResidentToNonresLiabSum;
        return $this;
    }

    /**
     * Gets as balance
     *
     * Сальдо расчетов
     *
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Sets a new balance
     *
     * Сальдо расчетов
     *
     * @param float $balance
     * @return static
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }


}

