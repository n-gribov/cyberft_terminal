<?php

namespace common\models\sbbolxml\response\MandatorySaleTicketType;

/**
 * Class representing SellAType
 */
class SellAType
{

    /**
     * Продано, 0 - Нет, 1 - Да
     *
     * @property boolean $checked
     */
    private $checked = null;

    /**
     * Дата валютирования (дата зачисления рублей)
     *
     * @property \DateTime $valueDate
     */
    private $valueDate = null;

    /**
     * Списано валюты
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Зачислено рублей
     *
     * @property float $enrollSum
     */
    private $enrollSum = null;

    /**
     * Курс сделки
     *
     * @property float $rate
     */
    private $rate = null;

    /**
     * Всего Сумма комиссии
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $chargeSum
     */
    private $chargeSum = null;

    /**
     * Gets as checked
     *
     * Продано, 0 - Нет, 1 - Да
     *
     * @return boolean
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Sets a new checked
     *
     * Продано, 0 - Нет, 1 - Да
     *
     * @param boolean $checked
     * @return static
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
        return $this;
    }

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
     * Списано валюты
     *
     * @return \common\models\sbbolxml\response\CurrAmountType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Списано валюты
     *
     * @param \common\models\sbbolxml\response\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\sbbolxml\response\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as enrollSum
     *
     * Зачислено рублей
     *
     * @return float
     */
    public function getEnrollSum()
    {
        return $this->enrollSum;
    }

    /**
     * Sets a new enrollSum
     *
     * Зачислено рублей
     *
     * @param float $enrollSum
     * @return static
     */
    public function setEnrollSum($enrollSum)
    {
        $this->enrollSum = $enrollSum;
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
     * @return \common\models\sbbolxml\response\CurrAmountType
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
     * @param \common\models\sbbolxml\response\CurrAmountType $chargeSum
     * @return static
     */
    public function setChargeSum(\common\models\sbbolxml\response\CurrAmountType $chargeSum)
    {
        $this->chargeSum = $chargeSum;
        return $this;
    }


}

