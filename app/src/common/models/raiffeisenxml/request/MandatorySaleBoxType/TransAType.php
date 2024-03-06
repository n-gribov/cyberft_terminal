<?php

namespace common\models\raiffeisenxml\request\MandatorySaleBoxType;

/**
 * Class representing TransAType
 */
class TransAType
{

    /**
     * Сумма для зачисления
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Код вида валютной операции
     *
     * @property string $vo
     */
    private $vo = null;

    /**
     * Реквизиты счёта зачисления валюты (другой банк)
     *
     * @property \common\models\raiffeisenxml\request\AccCURType $accTrans
     */
    private $accTrans = null;

    /**
     * Реквизиты текущего валютного счёта клиента (наш банк)
     *
     * @property \common\models\raiffeisenxml\request\AccountRUType $acc
     */
    private $acc = null;

    /**
     * Gets as sum
     *
     * Сумма для зачисления
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
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
     * @param \common\models\raiffeisenxml\request\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\request\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as vo
     *
     * Код вида валютной операции
     *
     * @return string
     */
    public function getVo()
    {
        return $this->vo;
    }

    /**
     * Sets a new vo
     *
     * Код вида валютной операции
     *
     * @param string $vo
     * @return static
     */
    public function setVo($vo)
    {
        $this->vo = $vo;
        return $this;
    }

    /**
     * Gets as accTrans
     *
     * Реквизиты счёта зачисления валюты (другой банк)
     *
     * @return \common\models\raiffeisenxml\request\AccCURType
     */
    public function getAccTrans()
    {
        return $this->accTrans;
    }

    /**
     * Sets a new accTrans
     *
     * Реквизиты счёта зачисления валюты (другой банк)
     *
     * @param \common\models\raiffeisenxml\request\AccCURType $accTrans
     * @return static
     */
    public function setAccTrans(\common\models\raiffeisenxml\request\AccCURType $accTrans)
    {
        $this->accTrans = $accTrans;
        return $this;
    }

    /**
     * Gets as acc
     *
     * Реквизиты текущего валютного счёта клиента (наш банк)
     *
     * @return \common\models\raiffeisenxml\request\AccountRUType
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
     * @param \common\models\raiffeisenxml\request\AccountRUType $acc
     * @return static
     */
    public function setAcc(\common\models\raiffeisenxml\request\AccountRUType $acc)
    {
        $this->acc = $acc;
        return $this;
    }


}

