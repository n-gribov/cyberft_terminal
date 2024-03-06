<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing PaymentType
 *
 *
 * XSD Type: Payment
 */
class PaymentType
{

    /**
     * Номер по порядку
     *
     * @property int $num
     */
    private $num = null;

    /**
     * @property string $currDigitalType
     */
    private $currDigitalType = null;

    /**
     * Дата платежа в счет основного долга
     *
     * @property \DateTime $principalDate
     */
    private $principalDate = null;

    /**
     * Сумма платежа в счет основного долга
     *
     * @property float $principalSum
     */
    private $principalSum = null;

    /**
     * Дата платежа в счет процентных платежей
     *
     * @property \DateTime $interestDate
     */
    private $interestDate = null;

    /**
     * Сумма платежа в счет процентных платежей
     *
     * @property float $interestSum
     */
    private $interestSum = null;

    /**
     * Описание особых условий
     *
     * @property string $desc
     */
    private $desc = null;

    /**
     * Gets as num
     *
     * Номер по порядку
     *
     * @return int
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Sets a new num
     *
     * Номер по порядку
     *
     * @param int $num
     * @return static
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * Gets as currDigitalType
     *
     * @return string
     */
    public function getCurrDigitalType()
    {
        return $this->currDigitalType;
    }

    /**
     * Sets a new currDigitalType
     *
     * @param string $currDigitalType
     * @return static
     */
    public function setCurrDigitalType($currDigitalType)
    {
        $this->currDigitalType = $currDigitalType;
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
     * Gets as principalSum
     *
     * Сумма платежа в счет основного долга
     *
     * @return float
     */
    public function getPrincipalSum()
    {
        return $this->principalSum;
    }

    /**
     * Sets a new principalSum
     *
     * Сумма платежа в счет основного долга
     *
     * @param float $principalSum
     * @return static
     */
    public function setPrincipalSum($principalSum)
    {
        $this->principalSum = $principalSum;
        return $this;
    }

    /**
     * Gets as interestDate
     *
     * Дата платежа в счет процентных платежей
     *
     * @return \DateTime
     */
    public function getInterestDate()
    {
        return $this->interestDate;
    }

    /**
     * Sets a new interestDate
     *
     * Дата платежа в счет процентных платежей
     *
     * @param \DateTime $interestDate
     * @return static
     */
    public function setInterestDate(\DateTime $interestDate)
    {
        $this->interestDate = $interestDate;
        return $this;
    }

    /**
     * Gets as interestSum
     *
     * Сумма платежа в счет процентных платежей
     *
     * @return float
     */
    public function getInterestSum()
    {
        return $this->interestSum;
    }

    /**
     * Sets a new interestSum
     *
     * Сумма платежа в счет процентных платежей
     *
     * @param float $interestSum
     * @return static
     */
    public function setInterestSum($interestSum)
    {
        $this->interestSum = $interestSum;
        return $this;
    }

    /**
     * Gets as desc
     *
     * Описание особых условий
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Sets a new desc
     *
     * Описание особых условий
     *
     * @param string $desc
     * @return static
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
        return $this;
    }


}

