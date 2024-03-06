<?php

namespace common\models\raiffeisenxml\response\MandatorySaleTicketType;

/**
 * Class representing SellAType
 */
class SellAType
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
     * Сумма зачисленный рублей
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $sumRub
     */
    private $sumRub = null;

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

    /**
     * Gets as sumRub
     *
     * Сумма зачисленный рублей
     *
     * @return \common\models\raiffeisenxml\response\CurrAmountType
     */
    public function getSumRub()
    {
        return $this->sumRub;
    }

    /**
     * Sets a new sumRub
     *
     * Сумма зачисленный рублей
     *
     * @param \common\models\raiffeisenxml\response\CurrAmountType $sumRub
     * @return static
     */
    public function setSumRub(\common\models\raiffeisenxml\response\CurrAmountType $sumRub)
    {
        $this->sumRub = $sumRub;
        return $this;
    }


}

