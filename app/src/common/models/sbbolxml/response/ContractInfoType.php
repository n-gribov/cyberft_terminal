<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ContractInfoType
 *
 *
 * XSD Type: ContractInfo
 */
class ContractInfoType
{

    /**
     * Идентификатор контракта
     *
     * @property string $contractId
     */
    private $contractId = null;

    /**
     * Наименование организации в СББОЛ
     *
     * @property string $orgName
     */
    private $orgName = null;

    /**
     * Идентификатор подразделения банка, с которым заключен контракт у Организации
     *
     * @property string $branchId
     */
    private $branchId = null;

    /**
     * Номер контракта
     *
     * @property string $contractNum
     */
    private $contractNum = null;

    /**
     * Дата контракта
     *
     * @property \DateTime $contractDate
     */
    private $contractDate = null;

    /**
     * Информация о приостановлении обслуживания в системе ДБО
     *
     * @property \common\models\sbbolxml\response\ContractInfoType\BlockInfoAType $blockInfo
     */
    private $blockInfo = null;

    /**
     * Gets as contractId
     *
     * Идентификатор контракта
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
     * Идентификатор контракта
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
     * Gets as orgName
     *
     * Наименование организации в СББОЛ
     *
     * @return string
     */
    public function getOrgName()
    {
        return $this->orgName;
    }

    /**
     * Sets a new orgName
     *
     * Наименование организации в СББОЛ
     *
     * @param string $orgName
     * @return static
     */
    public function setOrgName($orgName)
    {
        $this->orgName = $orgName;
        return $this;
    }

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
     * Gets as contractNum
     *
     * Номер контракта
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
     * Номер контракта
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
     * Дата контракта
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
     * Дата контракта
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
     * Gets as blockInfo
     *
     * Информация о приостановлении обслуживания в системе ДБО
     *
     * @return \common\models\sbbolxml\response\ContractInfoType\BlockInfoAType
     */
    public function getBlockInfo()
    {
        return $this->blockInfo;
    }

    /**
     * Sets a new blockInfo
     *
     * Информация о приостановлении обслуживания в системе ДБО
     *
     * @param \common\models\sbbolxml\response\ContractInfoType\BlockInfoAType $blockInfo
     * @return static
     */
    public function setBlockInfo(\common\models\sbbolxml\response\ContractInfoType\BlockInfoAType $blockInfo)
    {
        $this->blockInfo = $blockInfo;
        return $this;
    }


}

