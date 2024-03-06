<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CurrConvTicketType
 *
 *
 * XSD Type: CurrConvTicket
 */
class CurrConvTicketType
{

    /**
     * Дата валютирования
     *
     * @property \DateTime $valueDate
     */
    private $valueDate = null;

    /**
     * Курс сделки
     *
     * @property float $rate
     */
    private $rate = null;

    /**
     * Сумма комиссии за покупку/продажу валюты
     *
     * @property \common\models\sbbolxml\response\CurrAmountISOType $chargeSum
     */
    private $chargeSum = null;

    /**
     * Cумма покупаемой (покупка, конверсия) или продаваемой валюты (продажа) – в
     *  зависимости от назначения документа
     *
     * @property \common\models\sbbolxml\response\CurrAmountISOType $dealSum
     */
    private $dealSum = null;

    /**
     * Выручка в рублях от продажи валюты (продажа)
     *  или
     *  списанная/перечисленная сумма на покупку в рублях или валюте (покупка, конверсия)
     *
     * @property \common\models\sbbolxml\response\CurrAmountISOType $revSum
     */
    private $revSum = null;

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
     * Сумма комиссии за покупку/продажу валюты
     *
     * @return \common\models\sbbolxml\response\CurrAmountISOType
     */
    public function getChargeSum()
    {
        return $this->chargeSum;
    }

    /**
     * Sets a new chargeSum
     *
     * Сумма комиссии за покупку/продажу валюты
     *
     * @param \common\models\sbbolxml\response\CurrAmountISOType $chargeSum
     * @return static
     */
    public function setChargeSum(\common\models\sbbolxml\response\CurrAmountISOType $chargeSum)
    {
        $this->chargeSum = $chargeSum;
        return $this;
    }

    /**
     * Gets as dealSum
     *
     * Cумма покупаемой (покупка, конверсия) или продаваемой валюты (продажа) – в
     *  зависимости от назначения документа
     *
     * @return \common\models\sbbolxml\response\CurrAmountISOType
     */
    public function getDealSum()
    {
        return $this->dealSum;
    }

    /**
     * Sets a new dealSum
     *
     * Cумма покупаемой (покупка, конверсия) или продаваемой валюты (продажа) – в
     *  зависимости от назначения документа
     *
     * @param \common\models\sbbolxml\response\CurrAmountISOType $dealSum
     * @return static
     */
    public function setDealSum(\common\models\sbbolxml\response\CurrAmountISOType $dealSum)
    {
        $this->dealSum = $dealSum;
        return $this;
    }

    /**
     * Gets as revSum
     *
     * Выручка в рублях от продажи валюты (продажа)
     *  или
     *  списанная/перечисленная сумма на покупку в рублях или валюте (покупка, конверсия)
     *
     * @return \common\models\sbbolxml\response\CurrAmountISOType
     */
    public function getRevSum()
    {
        return $this->revSum;
    }

    /**
     * Sets a new revSum
     *
     * Выручка в рублях от продажи валюты (продажа)
     *  или
     *  списанная/перечисленная сумма на покупку в рублях или валюте (покупка, конверсия)
     *
     * @param \common\models\sbbolxml\response\CurrAmountISOType $revSum
     * @return static
     */
    public function setRevSum(\common\models\sbbolxml\response\CurrAmountISOType $revSum)
    {
        $this->revSum = $revSum;
        return $this;
    }


}

