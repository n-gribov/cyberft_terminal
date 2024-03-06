<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing OrganizationInfoType
 *
 *
 * XSD Type: OrganizationInfo
 */
class OrganizationInfoType
{

    /**
     * Данные по организации и счетам
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType $orgData
     */
    private $orgData = null;

    /**
     * Подразделения банка, в которых обслуживается организация
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType[] $branches
     */
    private $branches = null;

    /**
     * Пакеты услуг
     *
     * @property \common\models\sbbolxml\response\ServicePackageType[] $servicePackages
     */
    private $servicePackages = null;

    /**
     * Кредитные договора для данной организации
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\CreditContractsAType\CreditContractAType[] $creditContracts
     */
    private $creditContracts = null;

    /**
     * Перечень зарплатных договоров для данной организации
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType[] $salaryContracts
     */
    private $salaryContracts = null;

    /**
     * Криптопрофили для данной организации
     *  (чем подписывается документ)
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\SignDevicesAType\SignDeviceAType[] $signDevices
     */
    private $signDevices = null;

    /**
     * Учетные записи пользователей клиента
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType[] $authPersons
     */
    private $authPersons = null;

    /**
     * Избирательные счета
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\ElectionAccountsAType\ElectionAccountAType[] $electionAccounts
     */
    private $electionAccounts = null;

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as orgData
     *
     * Данные по организации и счетам
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType $orgData)
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType $branch
     */
    public function addToBranches(\common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType $branch)
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
     * @return \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType[]
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType[] $branches
     * @return static
     */
    public function setBranches(array $branches)
    {
        $this->branches = $branches;
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
     * Adds as creditContract
     *
     * Кредитные договора для данной организации
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\CreditContractsAType\CreditContractAType $creditContract
     */
    public function addToCreditContracts(\common\models\sbbolxml\response\OrganizationInfoType\CreditContractsAType\CreditContractAType $creditContract)
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
     * @return \common\models\sbbolxml\response\OrganizationInfoType\CreditContractsAType\CreditContractAType[]
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\CreditContractsAType\CreditContractAType[] $creditContracts
     * @return static
     */
    public function setCreditContracts(array $creditContracts)
    {
        $this->creditContracts = $creditContracts;
        return $this;
    }

    /**
     * Adds as salaryContract
     *
     * Перечень зарплатных договоров для данной организации
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType $salaryContract
     */
    public function addToSalaryContracts(\common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType $salaryContract)
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
     * @return \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType[]
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType[] $salaryContracts
     * @return static
     */
    public function setSalaryContracts(array $salaryContracts)
    {
        $this->salaryContracts = $salaryContracts;
        return $this;
    }

    /**
     * Adds as signDevice
     *
     * Криптопрофили для данной организации
     *  (чем подписывается документ)
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SignDevicesAType\SignDeviceAType $signDevice
     */
    public function addToSignDevices(\common\models\sbbolxml\response\OrganizationInfoType\SignDevicesAType\SignDeviceAType $signDevice)
    {
        $this->signDevices[] = $signDevice;
        return $this;
    }

    /**
     * isset signDevices
     *
     * Криптопрофили для данной организации
     *  (чем подписывается документ)
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
     * Криптопрофили для данной организации
     *  (чем подписывается документ)
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
     * Криптопрофили для данной организации
     *  (чем подписывается документ)
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\SignDevicesAType\SignDeviceAType[]
     */
    public function getSignDevices()
    {
        return $this->signDevices;
    }

    /**
     * Sets a new signDevices
     *
     * Криптопрофили для данной организации
     *  (чем подписывается документ)
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SignDevicesAType\SignDeviceAType[] $signDevices
     * @return static
     */
    public function setSignDevices(array $signDevices)
    {
        $this->signDevices = $signDevices;
        return $this;
    }

    /**
     * Adds as authPerson
     *
     * Учетные записи пользователей клиента
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType $authPerson
     */
    public function addToAuthPersons(\common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType $authPerson)
    {
        $this->authPersons[] = $authPerson;
        return $this;
    }

    /**
     * isset authPersons
     *
     * Учетные записи пользователей клиента
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
     * Учетные записи пользователей клиента
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
     * Учетные записи пользователей клиента
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType[]
     */
    public function getAuthPersons()
    {
        return $this->authPersons;
    }

    /**
     * Sets a new authPersons
     *
     * Учетные записи пользователей клиента
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType[] $authPersons
     * @return static
     */
    public function setAuthPersons(array $authPersons)
    {
        $this->authPersons = $authPersons;
        return $this;
    }

    /**
     * Adds as electionAccount
     *
     * Избирательные счета
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\ElectionAccountsAType\ElectionAccountAType $electionAccount
     */
    public function addToElectionAccounts(\common\models\sbbolxml\response\OrganizationInfoType\ElectionAccountsAType\ElectionAccountAType $electionAccount)
    {
        $this->electionAccounts[] = $electionAccount;
        return $this;
    }

    /**
     * isset electionAccounts
     *
     * Избирательные счета
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetElectionAccounts($index)
    {
        return isset($this->electionAccounts[$index]);
    }

    /**
     * unset electionAccounts
     *
     * Избирательные счета
     *
     * @param scalar $index
     * @return void
     */
    public function unsetElectionAccounts($index)
    {
        unset($this->electionAccounts[$index]);
    }

    /**
     * Gets as electionAccounts
     *
     * Избирательные счета
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\ElectionAccountsAType\ElectionAccountAType[]
     */
    public function getElectionAccounts()
    {
        return $this->electionAccounts;
    }

    /**
     * Sets a new electionAccounts
     *
     * Избирательные счета
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\ElectionAccountsAType\ElectionAccountAType[] $electionAccounts
     * @return static
     */
    public function setElectionAccounts(array $electionAccounts)
    {
        $this->electionAccounts = $electionAccounts;
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

