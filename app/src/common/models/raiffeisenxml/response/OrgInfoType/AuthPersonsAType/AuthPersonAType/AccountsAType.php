<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType;

/**
 * Class representing AccountsAType
 */
class AccountsAType
{

    /**
     * Информация о счете. Передается информация только о
     *
     *  подключенных счетах данному уполномоченному лицу
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AccountsAType\AccountAType[] $account
     */
    private $account = [
        
    ];

    /**
     * Adds as account
     *
     * Информация о счете. Передается информация только о
     *
     *  подключенных счетах данному уполномоченному лицу
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AccountsAType\AccountAType $account
     */
    public function addToAccount(\common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AccountsAType\AccountAType $account)
    {
        $this->account[] = $account;
        return $this;
    }

    /**
     * isset account
     *
     * Информация о счете. Передается информация только о
     *
     *  подключенных счетах данному уполномоченному лицу
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
     * Информация о счете. Передается информация только о
     *
     *  подключенных счетах данному уполномоченному лицу
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
     * Информация о счете. Передается информация только о
     *
     *  подключенных счетах данному уполномоченному лицу
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AccountsAType\AccountAType[]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Информация о счете. Передается информация только о
     *
     *  подключенных счетах данному уполномоченному лицу
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AccountsAType\AccountAType[] $account
     * @return static
     */
    public function setAccount(array $account)
    {
        $this->account = $account;
        return $this;
    }


}

