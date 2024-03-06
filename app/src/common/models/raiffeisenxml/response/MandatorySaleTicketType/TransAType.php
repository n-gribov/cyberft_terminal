<?php

namespace common\models\raiffeisenxml\response\MandatorySaleTicketType;

/**
 * Class representing TransAType
 */
class TransAType
{

    /**
     * Сумма
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Всего Сумма комиссии
     *
     * @property \common\models\raiffeisenxml\response\CurrAmountType $chargeSum
     */
    private $chargeSum = null;

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

