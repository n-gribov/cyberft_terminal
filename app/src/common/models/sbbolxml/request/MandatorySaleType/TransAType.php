<?php

namespace common\models\sbbolxml\request\MandatorySaleType;

/**
 * Class representing TransAType
 */
class TransAType
{

    /**
     * Флаг «ЗАЧИСЛИТЬ в сумме» : 0 - не установлен, 1 - установлен
     *
     * @property boolean $flagNSPut
     */
    private $flagNSPut = null;

    /**
     * Зачислить на:
     *  0 - на наш текущий валютный счет в Сбербанке России
     *  1 - на счет в другом уполномоченном банке
     *  2 - на счет принципала и комитента
     *
     * @property string $transTo
     */
    private $transTo = null;

    /**
     * Реквизиты счёта зачисления валюты
     *
     * @property \common\models\sbbolxml\request\AccountCurrType $accTrans
     */
    private $accTrans = null;

    /**
     * Сумма для зачисления
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Gets as flagNSPut
     *
     * Флаг «ЗАЧИСЛИТЬ в сумме» : 0 - не установлен, 1 - установлен
     *
     * @return boolean
     */
    public function getFlagNSPut()
    {
        return $this->flagNSPut;
    }

    /**
     * Sets a new flagNSPut
     *
     * Флаг «ЗАЧИСЛИТЬ в сумме» : 0 - не установлен, 1 - установлен
     *
     * @param boolean $flagNSPut
     * @return static
     */
    public function setFlagNSPut($flagNSPut)
    {
        $this->flagNSPut = $flagNSPut;
        return $this;
    }

    /**
     * Gets as transTo
     *
     * Зачислить на:
     *  0 - на наш текущий валютный счет в Сбербанке России
     *  1 - на счет в другом уполномоченном банке
     *  2 - на счет принципала и комитента
     *
     * @return string
     */
    public function getTransTo()
    {
        return $this->transTo;
    }

    /**
     * Sets a new transTo
     *
     * Зачислить на:
     *  0 - на наш текущий валютный счет в Сбербанке России
     *  1 - на счет в другом уполномоченном банке
     *  2 - на счет принципала и комитента
     *
     * @param string $transTo
     * @return static
     */
    public function setTransTo($transTo)
    {
        $this->transTo = $transTo;
        return $this;
    }

    /**
     * Gets as accTrans
     *
     * Реквизиты счёта зачисления валюты
     *
     * @return \common\models\sbbolxml\request\AccountCurrType
     */
    public function getAccTrans()
    {
        return $this->accTrans;
    }

    /**
     * Sets a new accTrans
     *
     * Реквизиты счёта зачисления валюты
     *
     * @param \common\models\sbbolxml\request\AccountCurrType $accTrans
     * @return static
     */
    public function setAccTrans(\common\models\sbbolxml\request\AccountCurrType $accTrans)
    {
        $this->accTrans = $accTrans;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма для зачисления
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
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
     * @param \common\models\sbbolxml\request\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\sbbolxml\request\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }


}

