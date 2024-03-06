<?php

namespace common\models\sbbolxml\request\OverdraftRequestType;

/**
 * Class representing AccountsAType
 */
class AccountsAType
{

    /**
     * Номер счёта и БИК. По данному счёту АБС предоставит остатки и др.
     *  данные
     *
     * @property \common\models\sbbolxml\request\AccountType[] $account
     */
    private $account = array(
        
    );

    /**
     * Adds as account
     *
     * Номер счёта и БИК. По данному счёту АБС предоставит остатки и др.
     *  данные
     *
     * @return static
     * @param \common\models\sbbolxml\request\AccountType $account
     */
    public function addToAccount(\common\models\sbbolxml\request\AccountType $account)
    {
        $this->account[] = $account;
        return $this;
    }

    /**
     * isset account
     *
     * Номер счёта и БИК. По данному счёту АБС предоставит остатки и др.
     *  данные
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
     * Номер счёта и БИК. По данному счёту АБС предоставит остатки и др.
     *  данные
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
     * Номер счёта и БИК. По данному счёту АБС предоставит остатки и др.
     *  данные
     *
     * @return \common\models\sbbolxml\request\AccountType[]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Номер счёта и БИК. По данному счёту АБС предоставит остатки и др.
     *  данные
     *
     * @param \common\models\sbbolxml\request\AccountType[] $account
     * @return static
     */
    public function setAccount(array $account)
    {
        $this->account = $account;
        return $this;
    }


}

