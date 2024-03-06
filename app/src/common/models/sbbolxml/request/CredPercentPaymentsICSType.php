<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CredPercentPaymentsICSType
 *
 * Процентные платежи, предусмотренные кредитным договором (без учета платежей в погашение основного долга)
 * XSD Type: CredPercentPaymentsICS
 */
class CredPercentPaymentsICSType
{

    /**
     * Фиксированный размер процентной ставки (% годовых)
     *
     * @property float $fixPercent
     */
    private $fixPercent = null;

    /**
     * Код ставки LIBOR например, Л06
     *
     * @property string $lIBOR
     */
    private $lIBOR = null;

    /**
     * Другие методы определения процентной ставки
     *
     * @property string $otherMethod
     */
    private $otherMethod = null;

    /**
     * Размер процентной надбавки (% годовых)
     *
     * @property float $incPercent
     */
    private $incPercent = null;

    /**
     * Код периодичности платежей в погашение основного долга (В настоящей реализации не
     *  используется. Задел на будущее.)
     *
     * @property integer $debtCode1
     */
    private $debtCode1 = null;

    /**
     * Код периодичности платежей в счет процентных платежей (В настоящей реализации не
     *  используется. Задел на будущее.)
     *
     * @property integer $debtCode2
     */
    private $debtCode2 = null;

    /**
     * Gets as fixPercent
     *
     * Фиксированный размер процентной ставки (% годовых)
     *
     * @return float
     */
    public function getFixPercent()
    {
        return $this->fixPercent;
    }

    /**
     * Sets a new fixPercent
     *
     * Фиксированный размер процентной ставки (% годовых)
     *
     * @param float $fixPercent
     * @return static
     */
    public function setFixPercent($fixPercent)
    {
        $this->fixPercent = $fixPercent;
        return $this;
    }

    /**
     * Gets as lIBOR
     *
     * Код ставки LIBOR например, Л06
     *
     * @return string
     */
    public function getLIBOR()
    {
        return $this->lIBOR;
    }

    /**
     * Sets a new lIBOR
     *
     * Код ставки LIBOR например, Л06
     *
     * @param string $lIBOR
     * @return static
     */
    public function setLIBOR($lIBOR)
    {
        $this->lIBOR = $lIBOR;
        return $this;
    }

    /**
     * Gets as otherMethod
     *
     * Другие методы определения процентной ставки
     *
     * @return string
     */
    public function getOtherMethod()
    {
        return $this->otherMethod;
    }

    /**
     * Sets a new otherMethod
     *
     * Другие методы определения процентной ставки
     *
     * @param string $otherMethod
     * @return static
     */
    public function setOtherMethod($otherMethod)
    {
        $this->otherMethod = $otherMethod;
        return $this;
    }

    /**
     * Gets as incPercent
     *
     * Размер процентной надбавки (% годовых)
     *
     * @return float
     */
    public function getIncPercent()
    {
        return $this->incPercent;
    }

    /**
     * Sets a new incPercent
     *
     * Размер процентной надбавки (% годовых)
     *
     * @param float $incPercent
     * @return static
     */
    public function setIncPercent($incPercent)
    {
        $this->incPercent = $incPercent;
        return $this;
    }

    /**
     * Gets as debtCode1
     *
     * Код периодичности платежей в погашение основного долга (В настоящей реализации не
     *  используется. Задел на будущее.)
     *
     * @return integer
     */
    public function getDebtCode1()
    {
        return $this->debtCode1;
    }

    /**
     * Sets a new debtCode1
     *
     * Код периодичности платежей в погашение основного долга (В настоящей реализации не
     *  используется. Задел на будущее.)
     *
     * @param integer $debtCode1
     * @return static
     */
    public function setDebtCode1($debtCode1)
    {
        $this->debtCode1 = $debtCode1;
        return $this;
    }

    /**
     * Gets as debtCode2
     *
     * Код периодичности платежей в счет процентных платежей (В настоящей реализации не
     *  используется. Задел на будущее.)
     *
     * @return integer
     */
    public function getDebtCode2()
    {
        return $this->debtCode2;
    }

    /**
     * Sets a new debtCode2
     *
     * Код периодичности платежей в счет процентных платежей (В настоящей реализации не
     *  используется. Задел на будущее.)
     *
     * @param integer $debtCode2
     * @return static
     */
    public function setDebtCode2($debtCode2)
    {
        $this->debtCode2 = $debtCode2;
        return $this;
    }


}

