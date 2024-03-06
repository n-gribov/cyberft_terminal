<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType;

/**
 * Class representing OrgBranchAType
 */
class OrgBranchAType
{

    /**
     * Идентификатор подразделения банка
     *
     * @property string $branchId
     */
    private $branchId = null;

    /**
     * Номер договора
     *
     * @property string $contractNum
     */
    private $contractNum = null;

    /**
     * Дата договора
     *
     * @property \DateTime $contractDate
     */
    private $contractDate = null;

    /**
     * Дата подключения
     *
     * @property \DateTime $connectionDate
     */
    private $connectionDate = null;

    /**
     * Информация о приостановлении обслуживания в
     *  системе ДБО
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\BlockInfoAType $blockInfo
     */
    private $blockInfo = null;

    /**
     * Услуги
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\ServicePacksAType\ServicePackAType[] $servicePacks
     */
    private $servicePacks = null;

    /**
     * Кредитные договора для данной организации
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\CreditContractsAType\CreditContractAType[] $creditContracts
     */
    private $creditContracts = null;

    /**
     * Перечень зарплатных договоров для данной
     *  организации
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\SalaryContractsAType\SalaryContractAType[] $salaryContracts
     */
    private $salaryContracts = null;

    /**
     * Признак "Получать выписки по ссудным счетам"
     *  1 - признак установлен
     *  0 - не установлен
     *
     * @property boolean $imprestAcc
     */
    private $imprestAcc = null;

    /**
     * Gets as branchId
     *
     * Идентификатор подразделения банка
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
     * Идентификатор подразделения банка
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
     * Gets as contractNum
     *
     * Номер договора
     *
     * @return string
     */
    public function getContractNum()
    {
        return $this->contractNum;
    }

    /**
     * Sets a new contractNum
     *
     * Номер договора
     *
     * @param string $contractNum
     * @return static
     */
    public function setContractNum($contractNum)
    {
        $this->contractNum = $contractNum;
        return $this;
    }

    /**
     * Gets as contractDate
     *
     * Дата договора
     *
     * @return \DateTime
     */
    public function getContractDate()
    {
        return $this->contractDate;
    }

    /**
     * Sets a new contractDate
     *
     * Дата договора
     *
     * @param \DateTime $contractDate
     * @return static
     */
    public function setContractDate(\DateTime $contractDate)
    {
        $this->contractDate = $contractDate;
        return $this;
    }

    /**
     * Gets as connectionDate
     *
     * Дата подключения
     *
     * @return \DateTime
     */
    public function getConnectionDate()
    {
        return $this->connectionDate;
    }

    /**
     * Sets a new connectionDate
     *
     * Дата подключения
     *
     * @param \DateTime $connectionDate
     * @return static
     */
    public function setConnectionDate(\DateTime $connectionDate)
    {
        $this->connectionDate = $connectionDate;
        return $this;
    }

    /**
     * Gets as blockInfo
     *
     * Информация о приостановлении обслуживания в
     *  системе ДБО
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\BlockInfoAType
     */
    public function getBlockInfo()
    {
        return $this->blockInfo;
    }

    /**
     * Sets a new blockInfo
     *
     * Информация о приостановлении обслуживания в
     *  системе ДБО
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\BlockInfoAType $blockInfo
     * @return static
     */
    public function setBlockInfo(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\BlockInfoAType $blockInfo)
    {
        $this->blockInfo = $blockInfo;
        return $this;
    }

    /**
     * Adds as servicePack
     *
     * Услуги
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\ServicePacksAType\ServicePackAType $servicePack
     */
    public function addToServicePacks(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\ServicePacksAType\ServicePackAType $servicePack)
    {
        $this->servicePacks[] = $servicePack;
        return $this;
    }

    /**
     * isset servicePacks
     *
     * Услуги
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetServicePacks($index)
    {
        return isset($this->servicePacks[$index]);
    }

    /**
     * unset servicePacks
     *
     * Услуги
     *
     * @param scalar $index
     * @return void
     */
    public function unsetServicePacks($index)
    {
        unset($this->servicePacks[$index]);
    }

    /**
     * Gets as servicePacks
     *
     * Услуги
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\ServicePacksAType\ServicePackAType[]
     */
    public function getServicePacks()
    {
        return $this->servicePacks;
    }

    /**
     * Sets a new servicePacks
     *
     * Услуги
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\ServicePacksAType\ServicePackAType[] $servicePacks
     * @return static
     */
    public function setServicePacks(array $servicePacks)
    {
        $this->servicePacks = $servicePacks;
        return $this;
    }

    /**
     * Adds as creditContract
     *
     * Кредитные договора для данной организации
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\CreditContractsAType\CreditContractAType $creditContract
     */
    public function addToCreditContracts(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\CreditContractsAType\CreditContractAType $creditContract)
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
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\CreditContractsAType\CreditContractAType[]
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\CreditContractsAType\CreditContractAType[] $creditContracts
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
     * Перечень зарплатных договоров для данной
     *  организации
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\SalaryContractsAType\SalaryContractAType $salaryContract
     */
    public function addToSalaryContracts(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\SalaryContractsAType\SalaryContractAType $salaryContract)
    {
        $this->salaryContracts[] = $salaryContract;
        return $this;
    }

    /**
     * isset salaryContracts
     *
     * Перечень зарплатных договоров для данной
     *  организации
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
     * Перечень зарплатных договоров для данной
     *  организации
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
     * Перечень зарплатных договоров для данной
     *  организации
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\SalaryContractsAType\SalaryContractAType[]
     */
    public function getSalaryContracts()
    {
        return $this->salaryContracts;
    }

    /**
     * Sets a new salaryContracts
     *
     * Перечень зарплатных договоров для данной
     *  организации
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\SalaryContractsAType\SalaryContractAType[] $salaryContracts
     * @return static
     */
    public function setSalaryContracts(array $salaryContracts)
    {
        $this->salaryContracts = $salaryContracts;
        return $this;
    }

    /**
     * Gets as imprestAcc
     *
     * Признак "Получать выписки по ссудным счетам"
     *  1 - признак установлен
     *  0 - не установлен
     *
     * @return boolean
     */
    public function getImprestAcc()
    {
        return $this->imprestAcc;
    }

    /**
     * Sets a new imprestAcc
     *
     * Признак "Получать выписки по ссудным счетам"
     *  1 - признак установлен
     *  0 - не установлен
     *
     * @param boolean $imprestAcc
     * @return static
     */
    public function setImprestAcc($imprestAcc)
    {
        $this->imprestAcc = $imprestAcc;
        return $this;
    }


}

