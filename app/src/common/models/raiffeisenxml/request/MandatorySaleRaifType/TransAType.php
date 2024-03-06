<?php

namespace common\models\raiffeisenxml\request\MandatorySaleRaifType;

/**
 * Class representing TransAType
 */
class TransAType
{

    /**
     * Сумма для зачисления
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Реквизиты текущего валютного счёта клиента (наш банк)
     *
     * @property \common\models\raiffeisenxml\request\AccountNoNameType $acc
     */
    private $acc = null;

    /**
     * Gets as sum
     *
     * Сумма для зачисления
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма для зачисления
     *
     * @param float $sum
     * @return static
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as acc
     *
     * Реквизиты текущего валютного счёта клиента (наш банк)
     *
     * @return \common\models\raiffeisenxml\request\AccountNoNameType
     */
    public function getAcc()
    {
        return $this->acc;
    }

    /**
     * Sets a new acc
     *
     * Реквизиты текущего валютного счёта клиента (наш банк)
     *
     * @param \common\models\raiffeisenxml\request\AccountNoNameType $acc
     * @return static
     */
    public function setAcc(\common\models\raiffeisenxml\request\AccountNoNameType $acc)
    {
        $this->acc = $acc;
        return $this;
    }


}

