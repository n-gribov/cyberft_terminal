<?php

namespace common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType;

/**
 * Class representing AccountsAType
 */
class AccountsAType
{

    /**
     * Информация о счете. Передаать информацию ТОЛЬКО по подключенным счетам
     *
     * @property \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType[] $account
     */
    private $account = [
        
    ];

    /**
     * Adds as account
     *
     * Информация о счете. Передаать информацию ТОЛЬКО по подключенным счетам
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType $account
     */
    public function addToAccount(\common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType $account)
    {
        $this->account[] = $account;
        return $this;
    }

    /**
     * isset account
     *
     * Информация о счете. Передаать информацию ТОЛЬКО по подключенным счетам
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
     * Информация о счете. Передаать информацию ТОЛЬКО по подключенным счетам
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
     * Информация о счете. Передаать информацию ТОЛЬКО по подключенным счетам
     *
     * @return \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType[]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Информация о счете. Передаать информацию ТОЛЬКО по подключенным счетам
     *
     * @param \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType[] $account
     * @return static
     */
    public function setAccount(array $account)
    {
        $this->account = $account;
        return $this;
    }


}

