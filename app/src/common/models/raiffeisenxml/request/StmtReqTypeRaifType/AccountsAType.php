<?php

namespace common\models\raiffeisenxml\request\StmtReqTypeRaifType;

/**
 * Class representing AccountsAType
 */
class AccountsAType
{

    /**
     * Счёт, по которому запрашивается выписка
     *
     * @property \common\models\raiffeisenxml\request\AccountType[] $account
     */
    private $account = [
        
    ];

    /**
     * Adds as account
     *
     * Счёт, по которому запрашивается выписка
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\AccountType $account
     */
    public function addToAccount(\common\models\raiffeisenxml\request\AccountType $account)
    {
        $this->account[] = $account;
        return $this;
    }

    /**
     * isset account
     *
     * Счёт, по которому запрашивается выписка
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAccount($index)
    {
        return isset($this->account[$index]);
    }

    /**
     * unset account
     *
     * Счёт, по которому запрашивается выписка
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAccount($index)
    {
        unset($this->account[$index]);
    }

    /**
     * Gets as account
     *
     * Счёт, по которому запрашивается выписка
     *
     * @return \common\models\raiffeisenxml\request\AccountType[]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Счёт, по которому запрашивается выписка
     *
     * @param \common\models\raiffeisenxml\request\AccountType[] $account
     * @return static
     */
    public function setAccount(array $account)
    {
        $this->account = $account;
        return $this;
    }


}

