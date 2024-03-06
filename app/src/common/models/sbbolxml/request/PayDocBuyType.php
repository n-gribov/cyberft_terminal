<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PayDocBuyType
 *
 *
 * XSD Type: PayDocBuy
 */
class PayDocBuyType
{

    /**
     * Реквизиты счета списания продаваемой валюты
     *
     * @property \common\models\sbbolxml\request\DocAccountType $docAccount
     */
    private $docAccount = null;

    /**
     * Реквизиты счёта перечисления средств на покупку
     *
     * @property \common\models\sbbolxml\request\AccountRubType $recAccount
     */
    private $recAccount = null;

    /**
     * Gets as docAccount
     *
     * Реквизиты счета списания продаваемой валюты
     *
     * @return \common\models\sbbolxml\request\DocAccountType
     */
    public function getDocAccount()
    {
        return $this->docAccount;
    }

    /**
     * Sets a new docAccount
     *
     * Реквизиты счета списания продаваемой валюты
     *
     * @param \common\models\sbbolxml\request\DocAccountType $docAccount
     * @return static
     */
    public function setDocAccount(\common\models\sbbolxml\request\DocAccountType $docAccount)
    {
        $this->docAccount = $docAccount;
        return $this;
    }

    /**
     * Gets as recAccount
     *
     * Реквизиты счёта перечисления средств на покупку
     *
     * @return \common\models\sbbolxml\request\AccountRubType
     */
    public function getRecAccount()
    {
        return $this->recAccount;
    }

    /**
     * Sets a new recAccount
     *
     * Реквизиты счёта перечисления средств на покупку
     *
     * @param \common\models\sbbolxml\request\AccountRubType $recAccount
     * @return static
     */
    public function setRecAccount(\common\models\sbbolxml\request\AccountRubType $recAccount)
    {
        $this->recAccount = $recAccount;
        return $this;
    }


}

