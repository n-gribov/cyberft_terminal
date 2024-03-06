<?php

namespace common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType;

/**
 * Class representing FiredEmployeesInfoAType
 */
class FiredEmployeesInfoAType
{

    /**
     * Номер п/п (обязательно д.б. возвращен в тикете)
     *
     * @property string $numSt
     */
    private $numSt = null;

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
     * Отчество физического лица (у некоторых иностранцев нет
     *  отчества)
     *
     * @property string $middleName
     */
    private $middleName = null;

    /**
     * Счет физ. лица
     *
     * @property string $account
     */
    private $account = null;

    /**
     * Документ, удостоверяющий личность
     *
     * @property \common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType\IdentityDocAType $identityDoc
     */
    private $identityDoc = null;

    /**
     * Дата увольнения
     *
     * @property \DateTime $dateOfDismissal
     */
    private $dateOfDismissal = null;

    /**
     * Gets as numSt
     *
     * Номер п/п (обязательно д.б. возвращен в тикете)
     *
     * @return string
     */
    public function getNumSt()
    {
        return $this->numSt;
    }

    /**
     * Sets a new numSt
     *
     * Номер п/п (обязательно д.б. возвращен в тикете)
     *
     * @param string $numSt
     * @return static
     */
    public function setNumSt($numSt)
    {
        $this->numSt = $numSt;
        return $this;
    }

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
     * Отчество физического лица (у некоторых иностранцев нет
     *  отчества)
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
     * Отчество физического лица (у некоторых иностранцев нет
     *  отчества)
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
     * Gets as account
     *
     * Счет физ. лица
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Счет физ. лица
     *
     * @param string $account
     * @return static
     */
    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Gets as identityDoc
     *
     * Документ, удостоверяющий личность
     *
     * @return \common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType\IdentityDocAType
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
     * @param \common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType\IdentityDocAType $identityDoc
     * @return static
     */
    public function setIdentityDoc(\common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType\IdentityDocAType $identityDoc)
    {
        $this->identityDoc = $identityDoc;
        return $this;
    }

    /**
     * Gets as dateOfDismissal
     *
     * Дата увольнения
     *
     * @return \DateTime
     */
    public function getDateOfDismissal()
    {
        return $this->dateOfDismissal;
    }

    /**
     * Sets a new dateOfDismissal
     *
     * Дата увольнения
     *
     * @param \DateTime $dateOfDismissal
     * @return static
     */
    public function setDateOfDismissal(\DateTime $dateOfDismissal)
    {
        $this->dateOfDismissal = $dateOfDismissal;
        return $this;
    }


}

