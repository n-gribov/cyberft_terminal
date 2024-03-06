<?php

namespace common\models\sbbolxml\request\RegOfFiredEmployeesType\OrgDataAType;

/**
 * Class representing SalaryContractAType
 */
class SalaryContractAType
{

    /**
     * Идентификатор договора
     *
     * @property string $contrID
     */
    private $contrID = null;

    /**
     * Номер контракта
     *
     * @property string $number
     */
    private $number = null;

    /**
     * Дата контракта
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Наименование подразделения банка ("держатель контракта")
     *
     * @property string $branchName
     */
    private $branchName = null;

    /**
     * Номер филиала, указанный в справочнике з.договоров СББОЛ
     *
     * @property string $branchNum
     */
    private $branchNum = null;

    /**
     * Gets as contrID
     *
     * Идентификатор договора
     *
     * @return string
     */
    public function getContrID()
    {
        return $this->contrID;
    }

    /**
     * Sets a new contrID
     *
     * Идентификатор договора
     *
     * @param string $contrID
     * @return static
     */
    public function setContrID($contrID)
    {
        $this->contrID = $contrID;
        return $this;
    }

    /**
     * Gets as number
     *
     * Номер контракта
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Sets a new number
     *
     * Номер контракта
     *
     * @param string $number
     * @return static
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата контракта
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Дата контракта
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as branchName
     *
     * Наименование подразделения банка ("держатель контракта")
     *
     * @return string
     */
    public function getBranchName()
    {
        return $this->branchName;
    }

    /**
     * Sets a new branchName
     *
     * Наименование подразделения банка ("держатель контракта")
     *
     * @param string $branchName
     * @return static
     */
    public function setBranchName($branchName)
    {
        $this->branchName = $branchName;
        return $this;
    }

    /**
     * Gets as branchNum
     *
     * Номер филиала, указанный в справочнике з.договоров СББОЛ
     *
     * @return string
     */
    public function getBranchNum()
    {
        return $this->branchNum;
    }

    /**
     * Sets a new branchNum
     *
     * Номер филиала, указанный в справочнике з.договоров СББОЛ
     *
     * @param string $branchNum
     * @return static
     */
    public function setBranchNum($branchNum)
    {
        $this->branchNum = $branchNum;
        return $this;
    }


}

