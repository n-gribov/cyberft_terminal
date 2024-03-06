<?php

namespace common\models\sbbolxml\request\MandatorySaleType;

/**
 * Class representing SellAType
 */
class SellAType
{

    /**
     * Флаг «ПРОДАТЬ в сумме» : 0 - не установлен, 1 - установлен
     *
     * @property boolean $flagNSSell
     */
    private $flagNSSell = null;

    /**
     * Тип сделки
     *
     * @property \common\models\sbbolxml\request\TermDealTypeRequiredType $dealType
     */
    private $dealType = null;

    /**
     * Сумма для необязательной продажи валюты
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Реквизиты счета зачисления рублей (номер счета б. заполнен)
     *
     * @property \common\models\sbbolxml\request\OurRubAccountTypeRequiredType $acc
     */
    private $acc = null;

    /**
     * Gets as flagNSSell
     *
     * Флаг «ПРОДАТЬ в сумме» : 0 - не установлен, 1 - установлен
     *
     * @return boolean
     */
    public function getFlagNSSell()
    {
        return $this->flagNSSell;
    }

    /**
     * Sets a new flagNSSell
     *
     * Флаг «ПРОДАТЬ в сумме» : 0 - не установлен, 1 - установлен
     *
     * @param boolean $flagNSSell
     * @return static
     */
    public function setFlagNSSell($flagNSSell)
    {
        $this->flagNSSell = $flagNSSell;
        return $this;
    }

    /**
     * Gets as dealType
     *
     * Тип сделки
     *
     * @return \common\models\sbbolxml\request\TermDealTypeRequiredType
     */
    public function getDealType()
    {
        return $this->dealType;
    }

    /**
     * Sets a new dealType
     *
     * Тип сделки
     *
     * @param \common\models\sbbolxml\request\TermDealTypeRequiredType $dealType
     * @return static
     */
    public function setDealType(\common\models\sbbolxml\request\TermDealTypeRequiredType $dealType)
    {
        $this->dealType = $dealType;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма для необязательной продажи валюты
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
     * Сумма для необязательной продажи валюты
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\sbbolxml\request\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as acc
     *
     * Реквизиты счета зачисления рублей (номер счета б. заполнен)
     *
     * @return \common\models\sbbolxml\request\OurRubAccountTypeRequiredType
     */
    public function getAcc()
    {
        return $this->acc;
    }

    /**
     * Sets a new acc
     *
     * Реквизиты счета зачисления рублей (номер счета б. заполнен)
     *
     * @param \common\models\sbbolxml\request\OurRubAccountTypeRequiredType $acc
     * @return static
     */
    public function setAcc(\common\models\sbbolxml\request\OurRubAccountTypeRequiredType $acc)
    {
        $this->acc = $acc;
        return $this;
    }


}

