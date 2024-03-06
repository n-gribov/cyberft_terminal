<?php

namespace common\models\sbbolxml\request\IncrementStatementsType;

/**
 * Class representing AccountsAType
 */
class AccountsAType
{

    /**
     * Счета, по которым нужно получить выписки
     *
     * @property \common\models\sbbolxml\request\AccWithDateType[] $account
     */
    private $account = array(
        
    );

    /**
     * Adds as account
     *
     * Счета, по которым нужно получить выписки
     *
     * @return static
     * @param \common\models\sbbolxml\request\AccWithDateType $account
     */
    public function addToAccount(\common\models\sbbolxml\request\AccWithDateType $account)
    {
        $this->account[] = $account;
        return $this;
    }

    /**
     * isset account
     *
     * Счета, по которым нужно получить выписки
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
     * Счета, по которым нужно получить выписки
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
     * Счета, по которым нужно получить выписки
     *
     * @return \common\models\sbbolxml\request\AccWithDateType[]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Счета, по которым нужно получить выписки
     *
     * @param \common\models\sbbolxml\request\AccWithDateType[] $account
     * @return static
     */
    public function setAccount(array $account)
    {
        $this->account = $account;
        return $this;
    }


}

