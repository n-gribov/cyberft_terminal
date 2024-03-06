<?php

namespace common\models\raiffeisenxml\response\MandatorySaleTicketType;

/**
 * Class representing OSellAType
 */
class OSellAType
{

    /**
     * Дата валютирования (дата зачисления рублей)
     *
     * @property \DateTime $valueDate
     */
    private $valueDate = null;

    /**
     * Сумма
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Курс сделки
     *
     * @property float $rate
     */
    private $rate = null;

    /**
     * Всего Сумма комиссии
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $chargeSum
     */
    private $chargeSum = null;

    /**
     * Gets as valueDate
     *
     * Дата валютирования (дата зачисления рублей)
     *
     * @return \DateTime
     */
    public function getValueDate()
    {
        return $this->valueDate;
    }

    /**
     * Sets a new valueDate
     *
     * Дата валютирования (дата зачисления рублей)
     *
     * @param \DateTime $valueDate
     * @return static
     */
    public function setValueDate(\DateTime $valueDate)
    {
        $this->valueDate = $valueDate;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма
     *
     * @return \common\models\raiffeisenxml\response\CurrAmountType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма
     *
     * @param \common\models\raiffeisenxml\response\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\response\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as rate
     *
     * Курс сделки
     *
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Sets a new rate
     *
     * Курс сделки
     *
     * @param float $rate
     * @return static
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * Gets as chargeSum
     *
     * Всего Сумма комиссии
     *
     * @return \common\models\raiffeisenxml\response\CurrAmountType
     */
    public function getChargeSum()
    {
        return $this->chargeSum;
    }

    /**
     * Sets a new chargeSum
     *
     * Всего Сумма комиссии
     *
     * @param \common\models\raiffeisenxml\response\CurrAmountType $chargeSum
     * @return static
     */
    public function setChargeSum(\common\models\raiffeisenxml\response\CurrAmountType $chargeSum)
    {
        $this->chargeSum = $chargeSum;
        return $this;
    }


}

