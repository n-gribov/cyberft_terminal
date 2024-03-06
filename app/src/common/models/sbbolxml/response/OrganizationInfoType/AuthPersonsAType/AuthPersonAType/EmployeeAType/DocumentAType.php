<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType;

/**
 * Class representing DocumentAType
 */
class DocumentAType
{

    /**
     * Код типа документа
     *
     * @property string $typeCode
     */
    private $typeCode = null;

    /**
     * Наименование типа документа
     *
     * @property string $typeName
     */
    private $typeName = null;

    /**
     * Серия
     *
     * @property string $series
     */
    private $series = null;

    /**
     * Номер
     *
     * @property string $number
     */
    private $number = null;

    /**
     * Дата выдачи
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Место выдачи
     *
     * @property string $branch
     */
    private $branch = null;

    /**
     * Код подразделения
     *
     * @property string $branchCode
     */
    private $branchCode = null;

    /**
     * Дата окончания срока действия документа
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Gets as typeCode
     *
     * Код типа документа
     *
     * @return string
     */
    public function getTypeCode()
    {
        return $this->typeCode;
    }

    /**
     * Sets a new typeCode
     *
     * Код типа документа
     *
     * @param string $typeCode
     * @return static
     */
    public function setTypeCode($typeCode)
    {
        $this->typeCode = $typeCode;
        return $this;
    }

    /**
     * Gets as typeName
     *
     * Наименование типа документа
     *
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * Sets a new typeName
     *
     * Наименование типа документа
     *
     * @param string $typeName
     * @return static
     */
    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;
        return $this;
    }

    /**
     * Gets as series
     *
     * Серия
     *
     * @return string
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Sets a new series
     *
     * Серия
     *
     * @param string $series
     * @return static
     */
    public function setSeries($series)
    {
        $this->series = $series;
        return $this;
    }

    /**
     * Gets as number
     *
     * Номер
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
     * Номер
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
     * Дата выдачи
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
     * Дата выдачи
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
     * Gets as branch
     *
     * Место выдачи
     *
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Sets a new branch
     *
     * Место выдачи
     *
     * @param string $branch
     * @return static
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
        return $this;
    }

    /**
     * Gets as branchCode
     *
     * Код подразделения
     *
     * @return string
     */
    public function getBranchCode()
    {
        return $this->branchCode;
    }

    /**
     * Sets a new branchCode
     *
     * Код подразделения
     *
     * @param string $branchCode
     * @return static
     */
    public function setBranchCode($branchCode)
    {
        $this->branchCode = $branchCode;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата окончания срока действия документа
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets a new endDate
     *
     * Дата окончания срока действия документа
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }


}

