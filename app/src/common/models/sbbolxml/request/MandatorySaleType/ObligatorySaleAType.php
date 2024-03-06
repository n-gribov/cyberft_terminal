<?php

namespace common\models\sbbolxml\request\MandatorySaleType;

/**
 * Class representing ObligatorySaleAType
 */
class ObligatorySaleAType
{

    /**
     * Процент для обязательной продажи валюты
     *
     * @property float $percent
     */
    private $percent = null;

    /**
     * Сумма обязательной продажи в валюте документа
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Условия обязательной продажи валюты
     *
     * @property \common\models\sbbolxml\request\TermDealType $termSale
     */
    private $termSale = null;

    /**
     * Реквизиты счёта зачисления рублей от обязательной продажи валюты
     *
     * @property \common\models\sbbolxml\request\OurRubAccountType $account
     */
    private $account = null;

    /**
     * Gets as percent
     *
     * Процент для обязательной продажи валюты
     *
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Sets a new percent
     *
     * Процент для обязательной продажи валюты
     *
     * @param float $percent
     * @return static
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма обязательной продажи в валюте документа
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
     * Сумма обязательной продажи в валюте документа
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
     * Gets as termSale
     *
     * Условия обязательной продажи валюты
     *
     * @return \common\models\sbbolxml\request\TermDealType
     */
    public function getTermSale()
    {
        return $this->termSale;
    }

    /**
     * Sets a new termSale
     *
     * Условия обязательной продажи валюты
     *
     * @param \common\models\sbbolxml\request\TermDealType $termSale
     * @return static
     */
    public function setTermSale(\common\models\sbbolxml\request\TermDealType $termSale)
    {
        $this->termSale = $termSale;
        return $this;
    }

    /**
     * Gets as account
     *
     * Реквизиты счёта зачисления рублей от обязательной продажи валюты
     *
     * @return \common\models\sbbolxml\request\OurRubAccountType
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Реквизиты счёта зачисления рублей от обязательной продажи валюты
     *
     * @param \common\models\sbbolxml\request\OurRubAccountType $account
     * @return static
     */
    public function setAccount(\common\models\sbbolxml\request\OurRubAccountType $account)
    {
        $this->account = $account;
        return $this;
    }


}

