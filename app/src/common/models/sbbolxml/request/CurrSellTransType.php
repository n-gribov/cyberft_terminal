<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CurrSellTransType
 *
 *
 * XSD Type: CurrSellTrans
 */
class CurrSellTransType
{

    /**
     * Сумма продаваемой валюты
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sumSell
     */
    private $sumSell = null;

    /**
     * Условия продажи
     *
     * @property \common\models\sbbolxml\request\TermDealType $termSell
     */
    private $termSell = null;

    /**
     * Реквизиты счёта списания валюты
     *
     * @property \common\models\sbbolxml\request\AccountRubType $accountSell
     */
    private $accountSell = null;

    /**
     * Реквизиты счёта зачисления рублей
     *
     * @property \common\models\sbbolxml\request\OurRubAccountType $accountCredit
     */
    private $accountCredit = null;

    /**
     * Комиссионное вознаграждение (ComAcc ИЛИ ComOrder)
     *
     * @property \common\models\sbbolxml\request\CommisionType $commis
     */
    private $commis = null;

    /**
     * Gets as sumSell
     *
     * Сумма продаваемой валюты
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getSumSell()
    {
        return $this->sumSell;
    }

    /**
     * Sets a new sumSell
     *
     * Сумма продаваемой валюты
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $sumSell
     * @return static
     */
    public function setSumSell(\common\models\sbbolxml\request\CurrAmountType $sumSell)
    {
        $this->sumSell = $sumSell;
        return $this;
    }

    /**
     * Gets as termSell
     *
     * Условия продажи
     *
     * @return \common\models\sbbolxml\request\TermDealType
     */
    public function getTermSell()
    {
        return $this->termSell;
    }

    /**
     * Sets a new termSell
     *
     * Условия продажи
     *
     * @param \common\models\sbbolxml\request\TermDealType $termSell
     * @return static
     */
    public function setTermSell(\common\models\sbbolxml\request\TermDealType $termSell)
    {
        $this->termSell = $termSell;
        return $this;
    }

    /**
     * Gets as accountSell
     *
     * Реквизиты счёта списания валюты
     *
     * @return \common\models\sbbolxml\request\AccountRubType
     */
    public function getAccountSell()
    {
        return $this->accountSell;
    }

    /**
     * Sets a new accountSell
     *
     * Реквизиты счёта списания валюты
     *
     * @param \common\models\sbbolxml\request\AccountRubType $accountSell
     * @return static
     */
    public function setAccountSell(\common\models\sbbolxml\request\AccountRubType $accountSell)
    {
        $this->accountSell = $accountSell;
        return $this;
    }

    /**
     * Gets as accountCredit
     *
     * Реквизиты счёта зачисления рублей
     *
     * @return \common\models\sbbolxml\request\OurRubAccountType
     */
    public function getAccountCredit()
    {
        return $this->accountCredit;
    }

    /**
     * Sets a new accountCredit
     *
     * Реквизиты счёта зачисления рублей
     *
     * @param \common\models\sbbolxml\request\OurRubAccountType $accountCredit
     * @return static
     */
    public function setAccountCredit(\common\models\sbbolxml\request\OurRubAccountType $accountCredit)
    {
        $this->accountCredit = $accountCredit;
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

