<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing OverdraftRequestType
 *
 *
 * XSD Type: OverdraftRequest
 */
class OverdraftRequestType
{

    /**
     * @property \common\models\sbbolxml\request\AccountType[] $accounts
     */
    private $accounts = null;

    /**
     * Adds as account
     *
     * @return static
     * @param \common\models\sbbolxml\request\AccountType $account
     */
    public function addToAccounts(\common\models\sbbolxml\request\AccountType $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAccounts($index)
    {
        return isset($this->accounts[$index]);
    }

    /**
     * unset accounts
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAccounts($index)
    {
        unset($this->accounts[$index]);
    }

    /**
     * Gets as accounts
     *
     * @return \common\models\sbbolxml\request\AccountType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * @param \common\models\sbbolxml\request\AccountType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }


}

