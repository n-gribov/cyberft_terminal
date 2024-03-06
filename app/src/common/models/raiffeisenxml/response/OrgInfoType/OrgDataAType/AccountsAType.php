<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType;

/**
 * Class representing AccountsAType
 */
class AccountsAType
{

    /**
     * @property \common\models\raiffeisenxml\response\AccountRubType[] $account
     */
    private $account = [
        
    ];

    /**
     * Adds as account
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\AccountRubType $account
     */
    public function addToAccount(\common\models\raiffeisenxml\response\AccountRubType $account)
    {
        $this->account[] = $account;
        return $this;
    }

    /**
     * isset account
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
     * @return \common\models\raiffeisenxml\response\AccountRubType[]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * @param \common\models\raiffeisenxml\response\AccountRubType[] $account
     * @return static
     */
    public function setAccount(array $account)
    {
        $this->account = $account;
        return $this;
    }


}

