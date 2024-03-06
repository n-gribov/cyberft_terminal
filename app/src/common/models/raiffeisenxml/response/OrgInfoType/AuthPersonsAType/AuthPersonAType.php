<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType;

/**
 * Class representing AuthPersonAType
 */
class AuthPersonAType
{

    /**
     * Идентификатор Уполномоченного лица клиента
     *
     * @property string $authPersonId
     */
    private $authPersonId = null;

    /**
     * Физическое лицо, ФИО
     *
     * @property string $fIO
     */
    private $fIO = null;

    /**
     * id Организации
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Должность Уполномоченного лица клиента
     *
     * @property string $position
     */
    private $position = null;

    /**
     * ID контракта
     *
     * @property string $contractId
     */
    private $contractId = null;

    /**
     * Перчень счетов, доступных данному уполномоченному лицу
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AccountsAType\AccountAType[] $accounts
     */
    private $accounts = null;

    /**
     * Средства подписи, доступные данному уполномоченному лицу
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType\SignDeviceAType[] $signDevices
     */
    private $signDevices = null;

    /**
     * Полномочия подписи
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType[] $authSigns
     */
    private $authSigns = null;

    /**
     * Услуги, предоставляемые уполномоченному лицу. Передаем подключенные пакеты услуг
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\ServicePackagesAType\ServicePackageAType[] $servicePackages
     */
    private $servicePackages = null;

    /**
     * Gets as authPersonId
     *
     * Идентификатор Уполномоченного лица клиента
     *
     * @return string
     */
    public function getAuthPersonId()
    {
        return $this->authPersonId;
    }

    /**
     * Sets a new authPersonId
     *
     * Идентификатор Уполномоченного лица клиента
     *
     * @param string $authPersonId
     * @return static
     */
    public function setAuthPersonId($authPersonId)
    {
        $this->authPersonId = $authPersonId;
        return $this;
    }

    /**
     * Gets as fIO
     *
     * Физическое лицо, ФИО
     *
     * @return string
     */
    public function getFIO()
    {
        return $this->fIO;
    }

    /**
     * Sets a new fIO
     *
     * Физическое лицо, ФИО
     *
     * @param string $fIO
     * @return static
     */
    public function setFIO($fIO)
    {
        $this->fIO = $fIO;
        return $this;
    }

    /**
     * Gets as orgId
     *
     * id Организации
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
     * id Организации
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
     * Gets as position
     *
     * Должность Уполномоченного лица клиента
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets a new position
     *
     * Должность Уполномоченного лица клиента
     *
     * @param string $position
     * @return static
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Gets as contractId
     *
     * ID контракта
     *
     * @return string
     */
    public function getContractId()
    {
        return $this->contractId;
    }

    /**
     * Sets a new contractId
     *
     * ID контракта
     *
     * @param string $contractId
     * @return static
     */
    public function setContractId($contractId)
    {
        $this->contractId = $contractId;
        return $this;
    }

    /**
     * Adds as account
     *
     * Перчень счетов, доступных данному уполномоченному лицу
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AccountsAType\AccountAType $account
     */
    public function addToAccounts(\common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AccountsAType\AccountAType $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
     *
     * Перчень счетов, доступных данному уполномоченному лицу
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
     * Перчень счетов, доступных данному уполномоченному лицу
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
     * Перчень счетов, доступных данному уполномоченному лицу
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AccountsAType\AccountAType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * Перчень счетов, доступных данному уполномоченному лицу
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AccountsAType\AccountAType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * Adds as signDevice
     *
     * Средства подписи, доступные данному уполномоченному лицу
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType\SignDeviceAType $signDevice
     */
    public function addToSignDevices(\common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType\SignDeviceAType $signDevice)
    {
        $this->signDevices[] = $signDevice;
        return $this;
    }

    /**
     * isset signDevices
     *
     * Средства подписи, доступные данному уполномоченному лицу
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSignDevices($index)
    {
        return isset($this->signDevices[$index]);
    }

    /**
     * unset signDevices
     *
     * Средства подписи, доступные данному уполномоченному лицу
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSignDevices($index)
    {
        unset($this->signDevices[$index]);
    }

    /**
     * Gets as signDevices
     *
     * Средства подписи, доступные данному уполномоченному лицу
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType\SignDeviceAType[]
     */
    public function getSignDevices()
    {
        return $this->signDevices;
    }

    /**
     * Sets a new signDevices
     *
     * Средства подписи, доступные данному уполномоченному лицу
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType\SignDeviceAType[] $signDevices
     * @return static
     */
    public function setSignDevices(array $signDevices)
    {
        $this->signDevices = $signDevices;
        return $this;
    }

    /**
     * Adds as authSign
     *
     * Полномочия подписи
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType $authSign
     */
    public function addToAuthSigns(\common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType $authSign)
    {
        $this->authSigns[] = $authSign;
        return $this;
    }

    /**
     * isset authSigns
     *
     * Полномочия подписи
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAuthSigns($index)
    {
        return isset($this->authSigns[$index]);
    }

    /**
     * unset authSigns
     *
     * Полномочия подписи
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAuthSigns($index)
    {
        unset($this->authSigns[$index]);
    }

    /**
     * Gets as authSigns
     *
     * Полномочия подписи
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType[]
     */
    public function getAuthSigns()
    {
        return $this->authSigns;
    }

    /**
     * Sets a new authSigns
     *
     * Полномочия подписи
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType[] $authSigns
     * @return static
     */
    public function setAuthSigns(array $authSigns)
    {
        $this->authSigns = $authSigns;
        return $this;
    }

    /**
     * Adds as servicePackage
     *
     * Услуги, предоставляемые уполномоченному лицу. Передаем подключенные пакеты услуг
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\ServicePackagesAType\ServicePackageAType $servicePackage
     */
    public function addToServicePackages(\common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\ServicePackagesAType\ServicePackageAType $servicePackage)
    {
        $this->servicePackages[] = $servicePackage;
        return $this;
    }

    /**
     * isset servicePackages
     *
     * Услуги, предоставляемые уполномоченному лицу. Передаем подключенные пакеты услуг
     *
     * @param int|string $index
     * @return bool
     */
    public function issetServicePackages($index)
    {
        return isset($this->servicePackages[$index]);
    }

    /**
     * unset servicePackages
     *
     * Услуги, предоставляемые уполномоченному лицу. Передаем подключенные пакеты услуг
     *
     * @param int|string $index
     * @return void
     */
    public function unsetServicePackages($index)
    {
        unset($this->servicePackages[$index]);
    }

    /**
     * Gets as servicePackages
     *
     * Услуги, предоставляемые уполномоченному лицу. Передаем подключенные пакеты услуг
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\ServicePackagesAType\ServicePackageAType[]
     */
    public function getServicePackages()
    {
        return $this->servicePackages;
    }

    /**
     * Sets a new servicePackages
     *
     * Услуги, предоставляемые уполномоченному лицу. Передаем подключенные пакеты услуг
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\ServicePackagesAType\ServicePackageAType[] $servicePackages
     * @return static
     */
    public function setServicePackages(array $servicePackages)
    {
        $this->servicePackages = $servicePackages;
        return $this;
    }


}

