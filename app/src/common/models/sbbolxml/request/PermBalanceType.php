<?php

namespace common\models\sbbolxml\request;

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
     * Специальные условия. Возможные значения:
     *  «Нет»
     *  «+ 5%»
     *  «+ 10%»
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
     * Сумма НСО от
     *
     * @property float $pbSumFrom
     */
    private $pbSumFrom = null;

    /**
     * Сумма НСО до
     *
     * @property float $pbSumTo
     */
    private $pbSumTo = null;

    /**
     * Цифровой код валюты суммы вклада
     *
     * @property string $pbSumCurrCode
     */
    private $pbSumCurrCode = null;

    /**
     * Буквенный код валюты суммы вклада
     *
     * @property string $pbSumCurrCodeISO
     */
    private $pbSumCurrCodeISO = null;

    /**
     * Дата начала диапазона срока поддержания НСО
     *
     * @property \DateTime $pbStartDate
     */
    private $pbStartDate = null;

    /**
     * Дата окончания диапазона срока поддержания НСО
     *
     * @property \DateTime $pbEndDate
     */
    private $pbEndDate = null;

    /**
     * Срок поддержания НСО
     *
     * @property integer $pbTerm
     */
    private $pbTerm = null;

    /**
     * Допустимый срок поддержания НСО от
     *
     * @property integer $pbTermFrom
     */
    private $pbTermFrom = null;

    /**
     * Допустимый срок поддержания НСО до
     *
     * @property integer $pbTermTo
     */
    private $pbTermTo = null;

    /**
     * Процентная ставка
     *
     * @property float $interestRate
     */
    private $interestRate = null;

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
     * Специальные условия. Возможные значения:
     *  «Нет»
     *  «+ 5%»
     *  «+ 10%»
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
     * Специальные условия. Возможные значения:
     *  «Нет»
     *  «+ 5%»
     *  «+ 10%»
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
     * Gets as pbSumFrom
     *
     * Сумма НСО от
     *
     * @return float
     */
    public function getPbSumFrom()
    {
        return $this->pbSumFrom;
    }

    /**
     * Sets a new pbSumFrom
     *
     * Сумма НСО от
     *
     * @param float $pbSumFrom
     * @return static
     */
    public function setPbSumFrom($pbSumFrom)
    {
        $this->pbSumFrom = $pbSumFrom;
        return $this;
    }

    /**
     * Gets as pbSumTo
     *
     * Сумма НСО до
     *
     * @return float
     */
    public function getPbSumTo()
    {
        return $this->pbSumTo;
    }

    /**
     * Sets a new pbSumTo
     *
     * Сумма НСО до
     *
     * @param float $pbSumTo
     * @return static
     */
    public function setPbSumTo($pbSumTo)
    {
        $this->pbSumTo = $pbSumTo;
        return $this;
    }

    /**
     * Gets as pbSumCurrCode
     *
     * Цифровой код валюты суммы вклада
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
     * Цифровой код валюты суммы вклада
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
     * Gets as pbSumCurrCodeISO
     *
     * Буквенный код валюты суммы вклада
     *
     * @return string
     */
    public function getPbSumCurrCodeISO()
    {
        return $this->pbSumCurrCodeISO;
    }

    /**
     * Sets a new pbSumCurrCodeISO
     *
     * Буквенный код валюты суммы вклада
     *
     * @param string $pbSumCurrCodeISO
     * @return static
     */
    public function setPbSumCurrCodeISO($pbSumCurrCodeISO)
    {
        $this->pbSumCurrCodeISO = $pbSumCurrCodeISO;
        return $this;
    }

    /**
     * Gets as pbStartDate
     *
     * Дата начала диапазона срока поддержания НСО
     *
     * @return \DateTime
     */
    public function getPbStartDate()
    {
        return $this->pbStartDate;
    }

    /**
     * Sets a new pbStartDate
     *
     * Дата начала диапазона срока поддержания НСО
     *
     * @param \DateTime $pbStartDate
     * @return static
     */
    public function setPbStartDate(\DateTime $pbStartDate)
    {
        $this->pbStartDate = $pbStartDate;
        return $this;
    }

    /**
     * Gets as pbEndDate
     *
     * Дата окончания диапазона срока поддержания НСО
     *
     * @return \DateTime
     */
    public function getPbEndDate()
    {
        return $this->pbEndDate;
    }

    /**
     * Sets a new pbEndDate
     *
     * Дата окончания диапазона срока поддержания НСО
     *
     * @param \DateTime $pbEndDate
     * @return static
     */
    public function setPbEndDate(\DateTime $pbEndDate)
    {
        $this->pbEndDate = $pbEndDate;
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
     * Gets as pbTermFrom
     *
     * Допустимый срок поддержания НСО от
     *
     * @return integer
     */
    public function getPbTermFrom()
    {
        return $this->pbTermFrom;
    }

    /**
     * Sets a new pbTermFrom
     *
     * Допустимый срок поддержания НСО от
     *
     * @param integer $pbTermFrom
     * @return static
     */
    public function setPbTermFrom($pbTermFrom)
    {
        $this->pbTermFrom = $pbTermFrom;
        return $this;
    }

    /**
     * Gets as pbTermTo
     *
     * Допустимый срок поддержания НСО до
     *
     * @return integer
     */
    public function getPbTermTo()
    {
        return $this->pbTermTo;
    }

    /**
     * Sets a new pbTermTo
     *
     * Допустимый срок поддержания НСО до
     *
     * @param integer $pbTermTo
     * @return static
     */
    public function setPbTermTo($pbTermTo)
    {
        $this->pbTermTo = $pbTermTo;
        return $this;
    }

    /**
     * Gets as interestRate
     *
     * Процентная ставка
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
     * Процентная ставка
     *
     * @param float $interestRate
     * @return static
     */
    public function setInterestRate($interestRate)
    {
        $this->interestRate = $interestRate;
        return $this;
    }


}

