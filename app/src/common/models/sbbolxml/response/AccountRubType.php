<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing AccountRubType
 *
 * Реквизиты счёта
 * XSD Type: AccountRubType
 */
class AccountRubType
{

    /**
     * Идентификатор счета
     *
     * @property string $accountId
     */
    private $accountId = null;

    /**
     * Тип счета
     *
     * @property string $accountType
     */
    private $accountType = null;

    /**
     * Номер счёта зачисления
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * Реквизиты банка зачисления
     *
     * @property \common\models\sbbolxml\response\AccountRubType\BankAType $bank
     */
    private $bank = null;

    /**
     * Прочие данные по счетам
     *
     * @property \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType $otherAccountData
     */
    private $otherAccountData = null;

    /**
     * Gets as accountId
     *
     * Идентификатор счета
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Sets a new accountId
     *
     * Идентификатор счета
     *
     * @param string $accountId
     * @return static
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
        return $this;
    }

    /**
     * Gets as accountType
     *
     * Тип счета
     *
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * Sets a new accountType
     *
     * Тип счета
     *
     * @param string $accountType
     * @return static
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
        return $this;
    }

    /**
     * Gets as accNum
     *
     * Номер счёта зачисления
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * Номер счёта зачисления
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }

    /**
     * Gets as bank
     *
     * Реквизиты банка зачисления
     *
     * @return \common\models\sbbolxml\response\AccountRubType\BankAType
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Реквизиты банка зачисления
     *
     * @param \common\models\sbbolxml\response\AccountRubType\BankAType $bank
     * @return static
     */
    public function setBank(\common\models\sbbolxml\response\AccountRubType\BankAType $bank)
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * Gets as otherAccountData
     *
     * Прочие данные по счетам
     *
     * @return \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType
     */
    public function getOtherAccountData()
    {
        return $this->otherAccountData;
    }

    /**
     * Sets a new otherAccountData
     *
     * Прочие данные по счетам
     *
     * @param \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType $otherAccountData
     * @return static
     */
    public function setOtherAccountData(\common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType $otherAccountData)
    {
        $this->otherAccountData = $otherAccountData;
        return $this;
    }


}

