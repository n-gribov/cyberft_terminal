<?php

namespace common\models\sbbolxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType;

/**
 * Class representing ContactsAType
 */
class ContactsAType
{

    /**
     * ФИО руководителя
     *
     * @property string $directorFio
     */
    private $directorFio = null;

    /**
     * Телефон руководителя
     *
     * @property string $directorTel
     */
    private $directorTel = null;

    /**
     * ФИО главного бухгалтера
     *
     * @property string $accountantFio
     */
    private $accountantFio = null;

    /**
     * Телефон главного бухгалтера
     *
     * @property string $accountantTel
     */
    private $accountantTel = null;

    /**
     * Факс
     *
     * @property string $fax
     */
    private $fax = null;

    /**
     * Адрес электронной почты
     *
     * @property string $emeil
     */
    private $emeil = null;

    /**
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as directorFio
     *
     * ФИО руководителя
     *
     * @return string
     */
    public function getDirectorFio()
    {
        return $this->directorFio;
    }

    /**
     * Sets a new directorFio
     *
     * ФИО руководителя
     *
     * @param string $directorFio
     * @return static
     */
    public function setDirectorFio($directorFio)
    {
        $this->directorFio = $directorFio;
        return $this;
    }

    /**
     * Gets as directorTel
     *
     * Телефон руководителя
     *
     * @return string
     */
    public function getDirectorTel()
    {
        return $this->directorTel;
    }

    /**
     * Sets a new directorTel
     *
     * Телефон руководителя
     *
     * @param string $directorTel
     * @return static
     */
    public function setDirectorTel($directorTel)
    {
        $this->directorTel = $directorTel;
        return $this;
    }

    /**
     * Gets as accountantFio
     *
     * ФИО главного бухгалтера
     *
     * @return string
     */
    public function getAccountantFio()
    {
        return $this->accountantFio;
    }

    /**
     * Sets a new accountantFio
     *
     * ФИО главного бухгалтера
     *
     * @param string $accountantFio
     * @return static
     */
    public function setAccountantFio($accountantFio)
    {
        $this->accountantFio = $accountantFio;
        return $this;
    }

    /**
     * Gets as accountantTel
     *
     * Телефон главного бухгалтера
     *
     * @return string
     */
    public function getAccountantTel()
    {
        return $this->accountantTel;
    }

    /**
     * Sets a new accountantTel
     *
     * Телефон главного бухгалтера
     *
     * @param string $accountantTel
     * @return static
     */
    public function setAccountantTel($accountantTel)
    {
        $this->accountantTel = $accountantTel;
        return $this;
    }

    /**
     * Gets as fax
     *
     * Факс
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Sets a new fax
     *
     * Факс
     *
     * @param string $fax
     * @return static
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
        return $this;
    }

    /**
     * Gets as emeil
     *
     * Адрес электронной почты
     *
     * @return string
     */
    public function getEmeil()
    {
        return $this->emeil;
    }

    /**
     * Sets a new emeil
     *
     * Адрес электронной почты
     *
     * @param string $emeil
     * @return static
     */
    public function setEmeil($emeil)
    {
        $this->emeil = $emeil;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}

