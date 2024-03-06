<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AppForDepositType
 *
 * Открытие депозита (заявление)
 * XSD Type: AppForDeposit
 */
class AppForDepositType extends DocBaseType
{

    /**
     * Реквизиты документа
     *
     * @property \common\models\sbbolxml\request\DocDataAppForDepositType $docData
     */
    private $docData = null;

    /**
     * Данные по депозиту
     *
     * @property \common\models\sbbolxml\request\DepositType $deposit
     */
    private $deposit = null;

    /**
     * Р/с списания
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
     * @return \common\models\sbbolxml\request\DocDataAppForDepositType
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
     * @param \common\models\sbbolxml\request\DocDataAppForDepositType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataAppForDepositType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as deposit
     *
     * Данные по депозиту
     *
     * @return \common\models\sbbolxml\request\DepositType
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * Sets a new deposit
     *
     * Данные по депозиту
     *
     * @param \common\models\sbbolxml\request\DepositType $deposit
     * @return static
     */
    public function setDeposit(\common\models\sbbolxml\request\DepositType $deposit)
    {
        $this->deposit = $deposit;
        return $this;
    }

    /**
     * Gets as docAccount
     *
     * Р/с списания
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
     * Р/с списания
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

