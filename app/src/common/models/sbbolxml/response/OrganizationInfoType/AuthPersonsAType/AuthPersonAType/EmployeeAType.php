<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType;

/**
 * Class representing EmployeeAType
 */
class EmployeeAType
{

    /**
     * Дата рождения
     *
     * @property \DateTime $dateOfBirth
     */
    private $dateOfBirth = null;

    /**
     * Место рождения
     *
     * @property string $birthPlace
     */
    private $birthPlace = null;

    /**
     * Пол
     *
     * @property string $sex
     */
    private $sex = null;

    /**
     * ИНН
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * СНИЛС
     *
     * @property string $snils
     */
    private $snils = null;

    /**
     * Должность
     *
     * @property string $position
     */
    private $position = null;

    /**
     * Наименование подразделения
     *
     * @property string $branchName
     */
    private $branchName = null;

    /**
     * Идентифицирован
     *
     * @property boolean $isIdentified
     */
    private $isIdentified = null;

    /**
     * Гражданство
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType\CitizenshipAType $citizenship
     */
    private $citizenship = null;

    /**
     * Документ сотрудника
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType\DocumentAType $document
     */
    private $document = null;

    /**
     * Gets as dateOfBirth
     *
     * Дата рождения
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
     * Дата рождения
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
     * Gets as birthPlace
     *
     * Место рождения
     *
     * @return string
     */
    public function getBirthPlace()
    {
        return $this->birthPlace;
    }

    /**
     * Sets a new birthPlace
     *
     * Место рождения
     *
     * @param string $birthPlace
     * @return static
     */
    public function setBirthPlace($birthPlace)
    {
        $this->birthPlace = $birthPlace;
        return $this;
    }

    /**
     * Gets as sex
     *
     * Пол
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Sets a new sex
     *
     * Пол
     *
     * @param string $sex
     * @return static
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * Gets as inn
     *
     * ИНН
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Sets a new inn
     *
     * ИНН
     *
     * @param string $inn
     * @return static
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * Gets as snils
     *
     * СНИЛС
     *
     * @return string
     */
    public function getSnils()
    {
        return $this->snils;
    }

    /**
     * Sets a new snils
     *
     * СНИЛС
     *
     * @param string $snils
     * @return static
     */
    public function setSnils($snils)
    {
        $this->snils = $snils;
        return $this;
    }

    /**
     * Gets as position
     *
     * Должность
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
     * Должность
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
     * Gets as branchName
     *
     * Наименование подразделения
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
     * Наименование подразделения
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
     * Gets as isIdentified
     *
     * Идентифицирован
     *
     * @return boolean
     */
    public function getIsIdentified()
    {
        return $this->isIdentified;
    }

    /**
     * Sets a new isIdentified
     *
     * Идентифицирован
     *
     * @param boolean $isIdentified
     * @return static
     */
    public function setIsIdentified($isIdentified)
    {
        $this->isIdentified = $isIdentified;
        return $this;
    }

    /**
     * Gets as citizenship
     *
     * Гражданство
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType\CitizenshipAType
     */
    public function getCitizenship()
    {
        return $this->citizenship;
    }

    /**
     * Sets a new citizenship
     *
     * Гражданство
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType\CitizenshipAType $citizenship
     * @return static
     */
    public function setCitizenship(\common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType\CitizenshipAType $citizenship)
    {
        $this->citizenship = $citizenship;
        return $this;
    }

    /**
     * Gets as document
     *
     * Документ сотрудника
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType\DocumentAType
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Sets a new document
     *
     * Документ сотрудника
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType\DocumentAType $document
     * @return static
     */
    public function setDocument(\common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType\DocumentAType $document)
    {
        $this->document = $document;
        return $this;
    }


}

