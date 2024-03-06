<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing PayDocCurTicketType
 *
 *
 * XSD Type: PayDocCurTicket
 */
class PayDocCurTicketType
{

    /**
     * Дата валютирования
     *
     * @property \DateTime $valueDate
     */
    private $valueDate = null;

    /**
     * Фактический курс конверсии
     *
     * @property float $rate
     */
    private $rate = null;

    /**
     * Сумма комиссии за конверсию
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $convSum
     */
    private $convSum = null;

    /**
     * Сумма комиссии за перевод
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $chargeSum
     */
    private $chargeSum = null;

    /**
     * Фактическая сумма списанной валюты
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $debetSum
     */
    private $debetSum = null;

    /**
     * Фактическая сумма переведённой валюты
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $transSum
     */
    private $transSum = null;

    /**
     * Gets as valueDate
     *
     * Дата валютирования
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
     * Дата валютирования
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
     * Gets as rate
     *
     * Фактический курс конверсии
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
     * Фактический курс конверсии
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
     * Gets as convSum
     *
     * Сумма комиссии за конверсию
     *
     * @return \common\models\raiffeisenxml\response\CurrAmountType
     */
    public function getConvSum()
    {
        return $this->convSum;
    }

    /**
     * Sets a new convSum
     *
     * Сумма комиссии за конверсию
     *
     * @param \common\models\raiffeisenxml\response\CurrAmountType $convSum
     * @return static
     */
    public function setConvSum(\common\models\raiffeisenxml\response\CurrAmountType $convSum)
    {
        $this->convSum = $convSum;
        return $this;
    }

    /**
     * Gets as chargeSum
     *
     * Сумма комиссии за перевод
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
     * Сумма комиссии за перевод
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
     * Gets as debetSum
     *
     * Фактическая сумма списанной валюты
     *
     * @return \common\models\raiffeisenxml\response\CurrAmountType
     */
    public function getDebetSum()
    {
        return $this->debetSum;
    }

    /**
     * Sets a new debetSum
     *
     * Фактическая сумма списанной валюты
     *
     * @param \common\models\raiffeisenxml\response\CurrAmountType $debetSum
     * @return static
     */
    public function setDebetSum(\common\models\raiffeisenxml\response\CurrAmountType $debetSum)
    {
        $this->debetSum = $debetSum;
        return $this;
    }

    /**
     * Gets as transSum
     *
     * Фактическая сумма переведённой валюты
     *
     * @return \common\models\raiffeisenxml\response\CurrAmountType
     */
    public function getTransSum()
    {
        return $this->transSum;
    }

    /**
     * Sets a new transSum
     *
     * Фактическая сумма переведённой валюты
     *
     * @param \common\models\raiffeisenxml\response\CurrAmountType $transSum
     * @return static
     */
    public function setTransSum(\common\models\raiffeisenxml\response\CurrAmountType $transSum)
    {
        $this->transSum = $transSum;
        return $this;
    }


}

