<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\CardTypesAType;

/**
 * Class representing CardTypeAType
 */
class CardTypeAType
{

    /**
     * Идентификатор записи
     *
     * @property string $id
     */
    private $id = null;

    /**
     * Код вида карты
     *
     * @property string $typeCode
     */
    private $typeCode = null;

    /**
     * Наименование типа карты
     *
     * @property string $typeName
     */
    private $typeName = null;

    /**
     * Код бонусной программы
     *
     * @property string $bonusProgramCode
     */
    private $bonusProgramCode = null;

    /**
     * Название категории населения
     *
     * @property string $peopleGroupName
     */
    private $peopleGroupName = null;

    /**
     * Код категории населения
     *
     * @property string $peopleGroupCode
     */
    private $peopleGroupCode = null;

    /**
     * Код вида вклада 1C
     *
     * @property string $depositTypeCode1C
     */
    private $depositTypeCode1C = null;

    /**
     * Код подвида вклада 1C
     *
     * @property string $depositSubTypeCode1C
     */
    private $depositSubTypeCode1C = null;

    /**
     * Дата окончания срока активности типа карты
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Gets as id
     *
     * Идентификатор записи
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets a new id
     *
     * Идентификатор записи
     *
     * @param string $id
     * @return static
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets as typeCode
     *
     * Код вида карты
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
     * Код вида карты
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
     * Наименование типа карты
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
     * Наименование типа карты
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
     * Gets as bonusProgramCode
     *
     * Код бонусной программы
     *
     * @return string
     */
    public function getBonusProgramCode()
    {
        return $this->bonusProgramCode;
    }

    /**
     * Sets a new bonusProgramCode
     *
     * Код бонусной программы
     *
     * @param string $bonusProgramCode
     * @return static
     */
    public function setBonusProgramCode($bonusProgramCode)
    {
        $this->bonusProgramCode = $bonusProgramCode;
        return $this;
    }

    /**
     * Gets as peopleGroupName
     *
     * Название категории населения
     *
     * @return string
     */
    public function getPeopleGroupName()
    {
        return $this->peopleGroupName;
    }

    /**
     * Sets a new peopleGroupName
     *
     * Название категории населения
     *
     * @param string $peopleGroupName
     * @return static
     */
    public function setPeopleGroupName($peopleGroupName)
    {
        $this->peopleGroupName = $peopleGroupName;
        return $this;
    }

    /**
     * Gets as peopleGroupCode
     *
     * Код категории населения
     *
     * @return string
     */
    public function getPeopleGroupCode()
    {
        return $this->peopleGroupCode;
    }

    /**
     * Sets a new peopleGroupCode
     *
     * Код категории населения
     *
     * @param string $peopleGroupCode
     * @return static
     */
    public function setPeopleGroupCode($peopleGroupCode)
    {
        $this->peopleGroupCode = $peopleGroupCode;
        return $this;
    }

    /**
     * Gets as depositTypeCode1C
     *
     * Код вида вклада 1C
     *
     * @return string
     */
    public function getDepositTypeCode1C()
    {
        return $this->depositTypeCode1C;
    }

    /**
     * Sets a new depositTypeCode1C
     *
     * Код вида вклада 1C
     *
     * @param string $depositTypeCode1C
     * @return static
     */
    public function setDepositTypeCode1C($depositTypeCode1C)
    {
        $this->depositTypeCode1C = $depositTypeCode1C;
        return $this;
    }

    /**
     * Gets as depositSubTypeCode1C
     *
     * Код подвида вклада 1C
     *
     * @return string
     */
    public function getDepositSubTypeCode1C()
    {
        return $this->depositSubTypeCode1C;
    }

    /**
     * Sets a new depositSubTypeCode1C
     *
     * Код подвида вклада 1C
     *
     * @param string $depositSubTypeCode1C
     * @return static
     */
    public function setDepositSubTypeCode1C($depositSubTypeCode1C)
    {
        $this->depositSubTypeCode1C = $depositSubTypeCode1C;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата окончания срока активности типа карты
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
     * Дата окончания срока активности типа карты
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

