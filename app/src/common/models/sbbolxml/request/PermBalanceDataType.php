<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PermBalanceDataType
 *
 * Открытие НСО (заявление)
 * XSD Type: PermBalanceData
 */
class PermBalanceDataType extends DocBaseType
{

    /**
     * Реквизиты документа
     *
     * @property \common\models\sbbolxml\request\DocDataPermBalanceType $docData
     */
    private $docData = null;

    /**
     * Данные по продукту
     *
     * @property \common\models\sbbolxml\request\PermBalanceType $permBalance
     */
    private $permBalance = null;

    /**
     * Расчетный счет организации, Счет поддержания НСО
     *
     * @property \common\models\sbbolxml\request\AccountRubType $docAccount
     */
    private $docAccount = null;

    /**
     * Cчет возврата вклада и %%
     *
     * @property \common\models\sbbolxml\request\AccountReturnType $reqAccount
     */
    private $reqAccount = null;

    /**
     * Gets as docData
     *
     * Реквизиты документа
     *
     * @return \common\models\sbbolxml\request\DocDataPermBalanceType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Реквизиты документа
     *
     * @param \common\models\sbbolxml\request\DocDataPermBalanceType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataPermBalanceType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as permBalance
     *
     * Данные по продукту
     *
     * @return \common\models\sbbolxml\request\PermBalanceType
     */
    public function getPermBalance()
    {
        return $this->permBalance;
    }

    /**
     * Sets a new permBalance
     *
     * Данные по продукту
     *
     * @param \common\models\sbbolxml\request\PermBalanceType $permBalance
     * @return static
     */
    public function setPermBalance(\common\models\sbbolxml\request\PermBalanceType $permBalance)
    {
        $this->permBalance = $permBalance;
        return $this;
    }

    /**
     * Gets as docAccount
     *
     * Расчетный счет организации, Счет поддержания НСО
     *
     * @return \common\models\sbbolxml\request\AccountRubType
     */
    public function getDocAccount()
    {
        return $this->docAccount;
    }

    /**
     * Sets a new docAccount
     *
     * Расчетный счет организации, Счет поддержания НСО
     *
     * @param \common\models\sbbolxml\request\AccountRubType $docAccount
     * @return static
     */
    public function setDocAccount(\common\models\sbbolxml\request\AccountRubType $docAccount)
    {
        $this->docAccount = $docAccount;
        return $this;
    }

    /**
     * Gets as reqAccount
     *
     * Cчет возврата вклада и %%
     *
     * @return \common\models\sbbolxml\request\AccountReturnType
     */
    public function getReqAccount()
    {
        return $this->reqAccount;
    }

    /**
     * Sets a new reqAccount
     *
     * Cчет возврата вклада и %%
     *
     * @param \common\models\sbbolxml\request\AccountReturnType $reqAccount
     * @return static
     */
    public function setReqAccount(\common\models\sbbolxml\request\AccountReturnType $reqAccount)
    {
        $this->reqAccount = $reqAccount;
        return $this;
    }


}

