<?php

namespace common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType;

/**
 * Class representing AccountsAType
 */
class AccountsAType
{

    /**
     * Все доступные счета.
     *  0-признак НЕ установлен;
     *  1-признак установлен.
     *
     * @property boolean $all
     */
    private $all = null;

    /**
     * Отдельный счет
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType[] $account
     */
    private $account = array(
        
    );

    /**
     * Gets as all
     *
     * Все доступные счета.
     *  0-признак НЕ установлен;
     *  1-признак установлен.
     *
     * @return boolean
     */
    public function getAll()
    {
        return $this->all;
    }

    /**
     * Sets a new all
     *
     * Все доступные счета.
     *  0-признак НЕ установлен;
     *  1-признак установлен.
     *
     * @param boolean $all
     * @return static
     */
    public function setAll($all)
    {
        $this->all = $all;
        return $this;
    }

    /**
     * Adds as account
     *
     * Отдельный счет
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType $account
     */
    public function addToAccount(\common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType $account)
    {
        $this->account[] = $account;
        return $this;
    }

    /**
     * isset account
     *
     * Отдельный счет
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAccount($index)
    {
        return isset($this->account[$index]);
    }

    /**
     * unset account
     *
     * Отдельный счет
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAccount($index)
    {
        unset($this->account[$index]);
    }

    /**
     * Gets as account
     *
     * Отдельный счет
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType[]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Отдельный счет
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType[] $account
     * @return static
     */
    public function setAccount(array $account)
    {
        $this->account = $account;
        return $this;
    }


}

