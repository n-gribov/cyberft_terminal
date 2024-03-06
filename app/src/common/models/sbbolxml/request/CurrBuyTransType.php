<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CurrBuyTransType
 *
 *
 * XSD Type: CurrBuyTrans
 */
class CurrBuyTransType
{

    /**
     * Расчетный счет списания средств на покупку валюты
     *
     * @property \common\models\sbbolxml\request\CurrBuyTransType\AccAType $acc
     */
    private $acc = null;

    /**
     * Сумма покупки
     *
     * @property \common\models\sbbolxml\request\CurrBuyTransType\AmountTransfAType $amountTransf
     */
    private $amountTransf = null;

    /**
     * Условия покупки
     *
     * @property \common\models\sbbolxml\request\TermDealType $termBuy
     */
    private $termBuy = null;

    /**
     * Реквизиты счёта зачисления валюты
     *
     * @property \common\models\sbbolxml\request\CurrBuyTransType\AccountNumTransfAType $accountNumTransf
     */
    private $accountNumTransf = null;

    /**
     * Средства в продаваемой валюте списать со счета/ средства на покупку перечислены
     *
     * @property \common\models\sbbolxml\request\CurrBuyTransType\PayDocBuyAType $payDocBuy
     */
    private $payDocBuy = null;

    /**
     * Комиссионное вознаграждение (ComAcc ИЛИ ComOrder)
     *
     * @property \common\models\sbbolxml\request\CommisionType $commis
     */
    private $commis = null;

    /**
     * Gets as acc
     *
     * Расчетный счет списания средств на покупку валюты
     *
     * @return \common\models\sbbolxml\request\CurrBuyTransType\AccAType
     */
    public function getAcc()
    {
        return $this->acc;
    }

    /**
     * Sets a new acc
     *
     * Расчетный счет списания средств на покупку валюты
     *
     * @param \common\models\sbbolxml\request\CurrBuyTransType\AccAType $acc
     * @return static
     */
    public function setAcc(\common\models\sbbolxml\request\CurrBuyTransType\AccAType $acc)
    {
        $this->acc = $acc;
        return $this;
    }

    /**
     * Gets as amountTransf
     *
     * Сумма покупки
     *
     * @return \common\models\sbbolxml\request\CurrBuyTransType\AmountTransfAType
     */
    public function getAmountTransf()
    {
        return $this->amountTransf;
    }

    /**
     * Sets a new amountTransf
     *
     * Сумма покупки
     *
     * @param \common\models\sbbolxml\request\CurrBuyTransType\AmountTransfAType $amountTransf
     * @return static
     */
    public function setAmountTransf(\common\models\sbbolxml\request\CurrBuyTransType\AmountTransfAType $amountTransf)
    {
        $this->amountTransf = $amountTransf;
        return $this;
    }

    /**
     * Gets as termBuy
     *
     * Условия покупки
     *
     * @return \common\models\sbbolxml\request\TermDealType
     */
    public function getTermBuy()
    {
        return $this->termBuy;
    }

    /**
     * Sets a new termBuy
     *
     * Условия покупки
     *
     * @param \common\models\sbbolxml\request\TermDealType $termBuy
     * @return static
     */
    public function setTermBuy(\common\models\sbbolxml\request\TermDealType $termBuy)
    {
        $this->termBuy = $termBuy;
        return $this;
    }

    /**
     * Gets as accountNumTransf
     *
     * Реквизиты счёта зачисления валюты
     *
     * @return \common\models\sbbolxml\request\CurrBuyTransType\AccountNumTransfAType
     */
    public function getAccountNumTransf()
    {
        return $this->accountNumTransf;
    }

    /**
     * Sets a new accountNumTransf
     *
     * Реквизиты счёта зачисления валюты
     *
     * @param \common\models\sbbolxml\request\CurrBuyTransType\AccountNumTransfAType $accountNumTransf
     * @return static
     */
    public function setAccountNumTransf(\common\models\sbbolxml\request\CurrBuyTransType\AccountNumTransfAType $accountNumTransf)
    {
        $this->accountNumTransf = $accountNumTransf;
        return $this;
    }

    /**
     * Gets as payDocBuy
     *
     * Средства в продаваемой валюте списать со счета/ средства на покупку перечислены
     *
     * @return \common\models\sbbolxml\request\CurrBuyTransType\PayDocBuyAType
     */
    public function getPayDocBuy()
    {
        return $this->payDocBuy;
    }

    /**
     * Sets a new payDocBuy
     *
     * Средства в продаваемой валюте списать со счета/ средства на покупку перечислены
     *
     * @param \common\models\sbbolxml\request\CurrBuyTransType\PayDocBuyAType $payDocBuy
     * @return static
     */
    public function setPayDocBuy(\common\models\sbbolxml\request\CurrBuyTransType\PayDocBuyAType $payDocBuy)
    {
        $this->payDocBuy = $payDocBuy;
        return $this;
    }

    /**
     * Gets as commis
     *
     * Комиссионное вознаграждение (ComAcc ИЛИ ComOrder)
     *
     * @return \common\models\sbbolxml\request\CommisionType
     */
    public function getCommis()
    {
        return $this->commis;
    }

    /**
     * Sets a new commis
     *
     * Комиссионное вознаграждение (ComAcc ИЛИ ComOrder)
     *
     * @param \common\models\sbbolxml\request\CommisionType $commis
     * @return static
     */
    public function setCommis(\common\models\sbbolxml\request\CommisionType $commis)
    {
        $this->commis = $commis;
        return $this;
    }


}

