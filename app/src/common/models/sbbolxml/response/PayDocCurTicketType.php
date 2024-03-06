<?php

namespace common\models\sbbolxml\response;

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
     * @property \common\models\sbbolxml\response\CurrAmountType $convSum
     */
    private $convSum = null;

    /**
     * Сумма комиссии за перевод
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $chargeSum
     */
    private $chargeSum = null;

    /**
     * Фактическая сумма списанной валюты
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $debetSum
     */
    private $debetSum = null;

    /**
     * Фактическая сумма переведённой валюты
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $transSum
     */
    private $transSum = null;

    /**
     * Доп. статус СБК (поле РЦК)
     *
     * @property string $rzkStatus
     */
    private $rzkStatus = null;

    /**
     * Последнее действие с документом в СБК (поле РЦК)
     *
     * @property string $rzkAction
     */
    private $rzkAction = null;

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
     * @return \common\models\sbbolxml\response\CurrAmountType
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
     * @param \common\models\sbbolxml\response\CurrAmountType $convSum
     * @return static
     */
    public function setConvSum(\common\models\sbbolxml\response\CurrAmountType $convSum)
    {
        $this->convSum = $convSum;
        return $this;
    }

    /**
     * Gets as chargeSum
     *
     * Сумма комиссии за перевод
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
     * Сумма комиссии за перевод
     *
     * @param \common\models\sbbolxml\response\CurrAmountType $chargeSum
     * @return static
     */
    public function setChargeSum(\common\models\sbbolxml\response\CurrAmountType $chargeSum)
    {
        $this->chargeSum = $chargeSum;
        return $this;
    }

    /**
     * Gets as debetSum
     *
     * Фактическая сумма списанной валюты
     *
     * @return \common\models\sbbolxml\response\CurrAmountType
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
     * @param \common\models\sbbolxml\response\CurrAmountType $debetSum
     * @return static
     */
    public function setDebetSum(\common\models\sbbolxml\response\CurrAmountType $debetSum)
    {
        $this->debetSum = $debetSum;
        return $this;
    }

    /**
     * Gets as transSum
     *
     * Фактическая сумма переведённой валюты
     *
     * @return \common\models\sbbolxml\response\CurrAmountType
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
     * @param \common\models\sbbolxml\response\CurrAmountType $transSum
     * @return static
     */
    public function setTransSum(\common\models\sbbolxml\response\CurrAmountType $transSum)
    {
        $this->transSum = $transSum;
        return $this;
    }

    /**
     * Gets as rzkStatus
     *
     * Доп. статус СБК (поле РЦК)
     *
     * @return string
     */
    public function getRzkStatus()
    {
        return $this->rzkStatus;
    }

    /**
     * Sets a new rzkStatus
     *
     * Доп. статус СБК (поле РЦК)
     *
     * @param string $rzkStatus
     * @return static
     */
    public function setRzkStatus($rzkStatus)
    {
        $this->rzkStatus = $rzkStatus;
        return $this;
    }

    /**
     * Gets as rzkAction
     *
     * Последнее действие с документом в СБК (поле РЦК)
     *
     * @return string
     */
    public function getRzkAction()
    {
        return $this->rzkAction;
    }

    /**
     * Sets a new rzkAction
     *
     * Последнее действие с документом в СБК (поле РЦК)
     *
     * @param string $rzkAction
     * @return static
     */
    public function setRzkAction($rzkAction)
    {
        $this->rzkAction = $rzkAction;
        return $this;
    }


}

