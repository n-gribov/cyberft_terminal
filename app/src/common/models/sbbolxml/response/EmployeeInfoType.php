<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing EmployeeInfoType
 *
 *
 * XSD Type: EmployeeInfo
 */
class EmployeeInfoType
{

    /**
     * Уникальный номер ФЛ (референс номер)
     *
     * @property string $noRef
     */
    private $noRef = null;

    /**
     * Фамилия физического лица
     *
     * @property string $sName
     */
    private $sName = null;

    /**
     * Имя физического лица
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Отчество физического лица (у некоторых иностранцев нет отчества)
     *
     * @property string $middleName
     */
    private $middleName = null;

    /**
     * Дата рождения сотрудника
     *
     * @property \DateTime $dateOfBirth
     */
    private $dateOfBirth = null;

    /**
     * Счет физ. лица
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * Признак выпуска карты 1 – карта выпущена, 0 – карта не выпущена
     *
     * @property boolean $issCard
     */
    private $issCard = null;

    /**
     * Документ, удостоверяющий личность
     *
     * @property \common\models\sbbolxml\response\IdentityDocType $identityDoc
     */
    private $identityDoc = null;

    /**
     * @property integer $salaryContract
     */
    private $salaryContract = null;

    /**
     * Тип реестра в котором данная запись найдена с СББОЛ: 1 - Реестр на открытие 2 - Реестр по уволившимся
     *
     * @property integer $regType
     */
    private $regType = null;

    /**
     * Gets as noRef
     *
     * Уникальный номер ФЛ (референс номер)
     *
     * @return string
     */
    public function getNoRef()
    {
        return $this->noRef;
    }

    /**
     * Sets a new noRef
     *
     * Уникальный номер ФЛ (референс номер)
     *
     * @param string $noRef
     * @return static
     */
    public function setNoRef($noRef)
    {
        $this->noRef = $noRef;
        return $this;
    }

    /**
     * Gets as sName
     *
     * Фамилия физического лица
     *
     * @return string
     */
    public function getSName()
    {
        return $this->sName;
    }

    /**
     * Sets a new sName
     *
     * Фамилия физического лица
     *
     * @param string $sName
     * @return static
     */
    public function setSName($sName)
    {
        $this->sName = $sName;
        return $this;
    }

    /**
     * Gets as name
     *
     * Имя физического лица
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Имя физического лица
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as middleName
     *
     * Отчество физического лица (у некоторых иностранцев нет отчества)
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Sets a new middleName
     *
     * Отчество физического лица (у некоторых иностранцев нет отчества)
     *
     * @param string $middleName
     * @return static
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
        return $this;
    }

    /**
     * Gets as dateOfBirth
     *
     * Дата рождения сотрудника
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Sets a new dateOfBirth
     *
     * Дата рождения сотрудника
     *
     * @param \DateTime $dateOfBirth
     * @return static
     */
    public function setDateOfBirth(\DateTime $dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    /**
     * Gets as accNum
     *
     * Счет физ. лица
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * Счет физ. лица
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }

    /**
     * Gets as issCard
     *
     * Признак выпуска карты 1 – карта выпущена, 0 – карта не выпущена
     *
     * @return boolean
     */
    public function getIssCard()
    {
        return $this->issCard;
    }

    /**
     * Sets a new issCard
     *
     * Признак выпуска карты 1 – карта выпущена, 0 – карта не выпущена
     *
     * @param boolean $issCard
     * @return static
     */
    public function setIssCard($issCard)
    {
        $this->issCard = $issCard;
        return $this;
    }

    /**
     * Gets as identityDoc
     *
     * Документ, удостоверяющий личность
     *
     * @return \common\models\sbbolxml\response\IdentityDocType
     */
    public function getIdentityDoc()
    {
        return $this->identityDoc;
    }

    /**
     * Sets a new identityDoc
     *
     * Документ, удостоверяющий личность
     *
     * @param \common\models\sbbolxml\response\IdentityDocType $identityDoc
     * @return static
     */
    public function setIdentityDoc(\common\models\sbbolxml\response\IdentityDocType $identityDoc)
    {
        $this->identityDoc = $identityDoc;
        return $this;
    }

    /**
     * Gets as salaryContract
     *
     * @return integer
     */
    public function getSalaryContract()
    {
        return $this->salaryContract;
    }

    /**
     * Sets a new salaryContract
     *
     * @param integer $salaryContract
     * @return static
     */
    public function setSalaryContract($salaryContract)
    {
        $this->salaryContract = $salaryContract;
        return $this;
    }

    /**
     * Gets as regType
     *
     * Тип реестра в котором данная запись найдена с СББОЛ: 1 - Реестр на открытие 2 - Реестр по уволившимся
     *
     * @return integer
     */
    public function getRegType()
    {
        return $this->regType;
    }

    /**
     * Sets a new regType
     *
     * Тип реестра в котором данная запись найдена с СББОЛ: 1 - Реестр на открытие 2 - Реестр по уволившимся
     *
     * @param integer $regType
     * @return static
     */
    public function setRegType($regType)
    {
        $this->regType = $regType;
        return $this;
    }


}

