<?php

namespace common\models\raiffeisenxml\response\ContractType\ContractServicesAType;

/**
 * Class representing ContractServiceAType
 */
class ContractServiceAType
{

    /**
     * Идентификатор подразделения банка, с которым заключен контракт у Организации
     *
     * @property string $branchId
     */
    private $branchId = null;

    /**
     * Идентификатор организации в Correqts
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * 0 - Нет, 1 - Да
     *  Признак блокировки услуги
     *
     * @property bool $blocked
     */
    private $blocked = null;

    /**
     * 0 - Нет, 1 - Да
     *  Признак "На все счета", который обуславливает автоматическое подключение нового счета организации в рамках услуги
     *
     * @property bool $allAccounts
     */
    private $allAccounts = null;

    /**
     * Доступные счета по контракту
     *
     * @property \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType[] $accounts
     */
    private $accounts = null;

    /**
     * Услуги, предоставляемые уполномоченному лицу. Передаем только подключенные пакеты.
     *
     * @property \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType[] $servicePacks
     */
    private $servicePacks = null;

    /**
     * Gets as branchId
     *
     * Идентификатор подразделения банка, с которым заключен контракт у Организации
     *
     * @return string
     */
    public function getBranchId()
    {
        return $this->branchId;
    }

    /**
     * Sets a new branchId
     *
     * Идентификатор подразделения банка, с которым заключен контракт у Организации
     *
     * @param string $branchId
     * @return static
     */
    public function setBranchId($branchId)
    {
        $this->branchId = $branchId;
        return $this;
    }

    /**
     * Gets as orgId
     *
     * Идентификатор организации в Correqts
     *
     * @return string
     */
    public function getOrgId()
    {
        return $this->orgId;
    }

    /**
     * Sets a new orgId
     *
     * Идентификатор организации в Correqts
     *
     * @param string $orgId
     * @return static
     */
    public function setOrgId($orgId)
    {
        $this->orgId = $orgId;
        return $this;
    }

    /**
     * Gets as blocked
     *
     * 0 - Нет, 1 - Да
     *  Признак блокировки услуги
     *
     * @return bool
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Sets a new blocked
     *
     * 0 - Нет, 1 - Да
     *  Признак блокировки услуги
     *
     * @param bool $blocked
     * @return static
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
        return $this;
    }

    /**
     * Gets as allAccounts
     *
     * 0 - Нет, 1 - Да
     *  Признак "На все счета", который обуславливает автоматическое подключение нового счета организации в рамках услуги
     *
     * @return bool
     */
    public function getAllAccounts()
    {
        return $this->allAccounts;
    }

    /**
     * Sets a new allAccounts
     *
     * 0 - Нет, 1 - Да
     *  Признак "На все счета", который обуславливает автоматическое подключение нового счета организации в рамках услуги
     *
     * @param bool $allAccounts
     * @return static
     */
    public function setAllAccounts($allAccounts)
    {
        $this->allAccounts = $allAccounts;
        return $this;
    }

    /**
     * Adds as account
     *
     * Доступные счета по контракту
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType $account
     */
    public function addToAccounts(\common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
     *
     * Доступные счета по контракту
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAccounts($index)
    {
        return isset($this->accounts[$index]);
    }

    /**
     * unset accounts
     *
     * Доступные счета по контракту
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAccounts($index)
    {
        unset($this->accounts[$index]);
    }

    /**
     * Gets as accounts
     *
     * Доступные счета по контракту
     *
     * @return \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * Доступные счета по контракту
     *
     * @param \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\AccountsAType\AccountAType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * Adds as servicePack
     *
     * Услуги, предоставляемые уполномоченному лицу. Передаем только подключенные пакеты.
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType $servicePack
     */
    public function addToServicePacks(\common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType $servicePack)
    {
        $this->servicePacks[] = $servicePack;
        return $this;
    }

    /**
     * isset servicePacks
     *
     * Услуги, предоставляемые уполномоченному лицу. Передаем только подключенные пакеты.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetServicePacks($index)
    {
        return isset($this->servicePacks[$index]);
    }

    /**
     * unset servicePacks
     *
     * Услуги, предоставляемые уполномоченному лицу. Передаем только подключенные пакеты.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetServicePacks($index)
    {
        unset($this->servicePacks[$index]);
    }

    /**
     * Gets as servicePacks
     *
     * Услуги, предоставляемые уполномоченному лицу. Передаем только подключенные пакеты.
     *
     * @return \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType[]
     */
    public function getServicePacks()
    {
        return $this->servicePacks;
    }

    /**
     * Sets a new servicePacks
     *
     * Услуги, предоставляемые уполномоченному лицу. Передаем только подключенные пакеты.
     *
     * @param \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType[] $servicePacks
     * @return static
     */
    public function setServicePacks(array $servicePacks)
    {
        $this->servicePacks = $servicePacks;
        return $this;
    }


}

