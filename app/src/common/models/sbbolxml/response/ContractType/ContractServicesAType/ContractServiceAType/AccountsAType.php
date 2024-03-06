<?php

namespace common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType;

/**
 * Class representing AccountsAType
 */
class AccountsAType
{

    /**
     * Информация о счете. Передаать информацию
     *  ТОЛЬКО по подключенным счетам
     *
     * @property \common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType[] $account
     */
    private $account = array(
        
    );

    /**
     * Adds as account
     *
     * Информация о счете. Передаать информацию
     *  ТОЛЬКО по подключенным счетам
     *
     * @return static
     * @param \common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType $account
     */
    public function addToAccount(\common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType $account)
    {
        $this->account[] = $account;
        return $this;
    }

    /**
     * isset account
     *
     * Информация о счете. Передаать информацию
     *  ТОЛЬКО по подключенным счетам
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
     * Информация о счете. Передаать информацию
     *  ТОЛЬКО по подключенным счетам
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
     * Информация о счете. Передаать информацию
     *  ТОЛЬКО по подключенным счетам
     *
     * @return \common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType[]
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Информация о счете. Передаать информацию
     *  ТОЛЬКО по подключенным счетам
     *
     * @param \common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType[] $account
     * @return static
     */
    public function setAccount(array $account)
    {
        $this->account = $account;
        return $this;
    }


}

