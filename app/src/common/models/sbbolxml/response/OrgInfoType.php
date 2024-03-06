<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing OrgInfoType
 *
 *
 * XSD Type: OrgInfo
 */
class OrgInfoType
{

    /**
     * Данные по организации и счетам
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\OrgDataAType $orgData
     */
    private $orgData = null;

    /**
     * Подразделения банка, в которых обслуживается организация
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType[] $branches
     */
    private $branches = null;

    /**
     * Средства подписи для данной организации
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType[] $signDevices
     */
    private $signDevices = null;

    /**
     * Пакеты услуг
     *
     * @property \common\models\sbbolxml\response\ServicePackageType[] $servicePackages
     */
    private $servicePackages = null;

    /**
     * Контракты и подключенные услуги организации
     *
     * @property \common\models\sbbolxml\response\ContractType[] $contracts
     */
    private $contracts = null;

    /**
     * Уполномоченные лица клиента, обладающие правом подписи в организации
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType[] $authPersons
     */
    private $authPersons = null;

    /**
     * Перечень зарплатных договоров для данной организации
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType[] $salaryContracts
     */
    private $salaryContracts = null;

    /**
     * Кредитные договора для данной организации
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\CreditContractsAType\CreditContractAType[] $creditContracts
     */
    private $creditContracts = null;

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as orgData
     *
     * Данные по организации и счетам
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\OrgDataAType
     */
    public function getOrgData()
    {
        return $this->orgData;
    }

    /**
     * Sets a new orgData
     *
     * Данные по организации и счетам
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\OrgDataAType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\response\OrgInfoType\OrgDataAType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }

    /**
     * Adds as branch
     *
     * Подразделения банка, в которых обслуживается организация
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType $branch
     */
    public function addToBranches(\common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType $branch)
    {
        $this->branches[] = $branch;
        return $this;
    }

    /**
     * isset branches
     *
     * Подразделения банка, в которых обслуживается организация
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBranches($index)
    {
        return isset($this->branches[$index]);
    }

    /**
     * unset branches
     *
     * Подразделения банка, в которых обслуживается организация
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBranches($index)
    {
        unset($this->branches[$index]);
    }

    /**
     * Gets as branches
     *
     * Подразделения банка, в которых обслуживается организация
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType[]
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * Sets a new branches
     *
     * Подразделения банка, в которых обслуживается организация
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\BranchesAType\BranchAType[] $branches
     * @return static
     */
    public function setBranches(array $branches)
    {
        $this->branches = $branches;
        return $this;
    }

    /**
     * Adds as signDevice
     *
     * Средства подписи для данной организации
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType $signDevice
     */
    public function addToSignDevices(\common\models\sbbolxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType $signDevice)
    {
        $this->signDevices[] = $signDevice;
        return $this;
    }

    /**
     * isset signDevices
     *
     * Средства подписи для данной организации
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSignDevices($index)
    {
        return isset($this->signDevices[$index]);
    }

    /**
     * unset signDevices
     *
     * Средства подписи для данной организации
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSignDevices($index)
    {
        unset($this->signDevices[$index]);
    }

    /**
     * Gets as signDevices
     *
     * Средства подписи для данной организации
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType[]
     */
    public function getSignDevices()
    {
        return $this->signDevices;
    }

    /**
     * Sets a new signDevices
     *
     * Средства подписи для данной организации
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType[] $signDevices
     * @return static
     */
    public function setSignDevices(array $signDevices)
    {
        $this->signDevices = $signDevices;
        return $this;
    }

    /**
     * Adds as servicePackage
     *
     * Пакеты услуг
     *
     * @return static
     * @param \common\models\sbbolxml\response\ServicePackageType $servicePackage
     */
    public function addToServicePackages(\common\models\sbbolxml\response\ServicePackageType $servicePackage)
    {
        $this->servicePackages[] = $servicePackage;
        return $this;
    }

    /**
     * isset servicePackages
     *
     * Пакеты услуг
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetServicePackages($index)
    {
        return isset($this->servicePackages[$index]);
    }

    /**
     * unset servicePackages
     *
     * Пакеты услуг
     *
     * @param scalar $index
     * @return void
     */
    public function unsetServicePackages($index)
    {
        unset($this->servicePackages[$index]);
    }

    /**
     * Gets as servicePackages
     *
     * Пакеты услуг
     *
     * @return \common\models\sbbolxml\response\ServicePackageType[]
     */
    public function getServicePackages()
    {
        return $this->servicePackages;
    }

    /**
     * Sets a new servicePackages
     *
     * Пакеты услуг
     *
     * @param \common\models\sbbolxml\response\ServicePackageType[] $servicePackages
     * @return static
     */
    public function setServicePackages(array $servicePackages)
    {
        $this->servicePackages = $servicePackages;
        return $this;
    }

    /**
     * Adds as contract
     *
     * Контракты и подключенные услуги организации
     *
     * @return static
     * @param \common\models\sbbolxml\response\ContractType $contract
     */
    public function addToContracts(\common\models\sbbolxml\response\ContractType $contract)
    {
        $this->contracts[] = $contract;
        return $this;
    }

    /**
     * isset contracts
     *
     * Контракты и подключенные услуги организации
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetContracts($index)
    {
        return isset($this->contracts[$index]);
    }

    /**
     * unset contracts
     *
     * Контракты и подключенные услуги организации
     *
     * @param scalar $index
     * @return void
     */
    public function unsetContracts($index)
    {
        unset($this->contracts[$index]);
    }

    /**
     * Gets as contracts
     *
     * Контракты и подключенные услуги организации
     *
     * @return \common\models\sbbolxml\response\ContractType[]
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * Sets a new contracts
     *
     * Контракты и подключенные услуги организации
     *
     * @param \common\models\sbbolxml\response\ContractType[] $contracts
     * @return static
     */
    public function setContracts(array $contracts)
    {
        $this->contracts = $contracts;
        return $this;
    }

    /**
     * Adds as authPerson
     *
     * Уполномоченные лица клиента, обладающие правом подписи в организации
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType $authPerson
     */
    public function addToAuthPersons(\common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType $authPerson)
    {
        $this->authPersons[] = $authPerson;
        return $this;
    }

    /**
     * isset authPersons
     *
     * Уполномоченные лица клиента, обладающие правом подписи в организации
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAuthPersons($index)
    {
        return isset($this->authPersons[$index]);
    }

    /**
     * unset authPersons
     *
     * Уполномоченные лица клиента, обладающие правом подписи в организации
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAuthPersons($index)
    {
        unset($this->authPersons[$index]);
    }

    /**
     * Gets as authPersons
     *
     * Уполномоченные лица клиента, обладающие правом подписи в организации
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType[]
     */
    public function getAuthPersons()
    {
        return $this->authPersons;
    }

    /**
     * Sets a new authPersons
     *
     * Уполномоченные лица клиента, обладающие правом подписи в организации
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType[] $authPersons
     * @return static
     */
    public function setAuthPersons(array $authPersons)
    {
        $this->authPersons = $authPersons;
        return $this;
    }

    /**
     * Adds as salaryContract
     *
     * Перечень зарплатных договоров для данной организации
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType $salaryContract
     */
    public function addToSalaryContracts(\common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType $salaryContract)
    {
        $this->salaryContracts[] = $salaryContract;
        return $this;
    }

    /**
     * isset salaryContracts
     *
     * Перечень зарплатных договоров для данной организации
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSalaryContracts($index)
    {
        return isset($this->salaryContracts[$index]);
    }

    /**
     * unset salaryContracts
     *
     * Перечень зарплатных договоров для данной организации
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSalaryContracts($index)
    {
        unset($this->salaryContracts[$index]);
    }

    /**
     * Gets as salaryContracts
     *
     * Перечень зарплатных договоров для данной организации
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType[]
     */
    public function getSalaryContracts()
    {
        return $this->salaryContracts;
    }

    /**
     * Sets a new salaryContracts
     *
     * Перечень зарплатных договоров для данной организации
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\SalaryContractsAType\SalaryContractAType[] $salaryContracts
     * @return static
     */
    public function setSalaryContracts(array $salaryContracts)
    {
        $this->salaryContracts = $salaryContracts;
        return $this;
    }

    /**
     * Adds as creditContract
     *
     * Кредитные договора для данной организации
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\CreditContractsAType\CreditContractAType $creditContract
     */
    public function addToCreditContracts(\common\models\sbbolxml\response\OrgInfoType\CreditContractsAType\CreditContractAType $creditContract)
    {
        $this->creditContracts[] = $creditContract;
        return $this;
    }

    /**
     * isset creditContracts
     *
     * Кредитные договора для данной организации
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCreditContracts($index)
    {
        return isset($this->creditContracts[$index]);
    }

    /**
     * unset creditContracts
     *
     * Кредитные договора для данной организации
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCreditContracts($index)
    {
        unset($this->creditContracts[$index]);
    }

    /**
     * Gets as creditContracts
     *
     * Кредитные договора для данной организации
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\CreditContractsAType\CreditContractAType[]
     */
    public function getCreditContracts()
    {
        return $this->creditContracts;
    }

    /**
     * Sets a new creditContracts
     *
     * Кредитные договора для данной организации
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\CreditContractsAType\CreditContractAType[] $creditContracts
     * @return static
     */
    public function setCreditContracts(array $creditContracts)
    {
        $this->creditContracts = $creditContracts;
        return $this;
    }

    /**
     * Gets as sign
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

