<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType;

/**
 * Class representing ContactsAType
 */
class ContactsAType
{

    /**
     * Телефон
     *
     * @property string $tel
     */
    private $tel = null;

    /**
     * Факс
     *
     * @property string $fax
     */
    private $fax = null;

    /**
     * Режим работы
     *
     * @property string $workTime
     */
    private $workTime = null;

    /**
     * Руководитель подразделения
     *
     * @property string $directorName
     */
    private $directorName = null;

    /**
     * Телефон(ы) уполномоченного лица
     *
     * @property string $directorTel
     */
    private $directorTel = null;

    /**
     * @property string $directorFax
     */
    private $directorFax = null;

    /**
     * @property string $directorEmail
     */
    private $directorEmail = null;

    /**
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as tel
     *
     * Телефон
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Sets a new tel
     *
     * Телефон
     *
     * @param string $tel
     * @return static
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
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
     * Gets as workTime
     *
     * Режим работы
     *
     * @return string
     */
    public function getWorkTime()
    {
        return $this->workTime;
    }

    /**
     * Sets a new workTime
     *
     * Режим работы
     *
     * @param string $workTime
     * @return static
     */
    public function setWorkTime($workTime)
    {
        $this->workTime = $workTime;
        return $this;
    }

    /**
     * Gets as directorName
     *
     * Руководитель подразделения
     *
     * @return string
     */
    public function getDirectorName()
    {
        return $this->directorName;
    }

    /**
     * Sets a new directorName
     *
     * Руководитель подразделения
     *
     * @param string $directorName
     * @return static
     */
    public function setDirectorName($directorName)
    {
        $this->directorName = $directorName;
        return $this;
    }

    /**
     * Gets as directorTel
     *
     * Телефон(ы) уполномоченного лица
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
     * Телефон(ы) уполномоченного лица
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
     * Gets as directorFax
     *
     * @return string
     */
    public function getDirectorFax()
    {
        return $this->directorFax;
    }

    /**
     * Sets a new directorFax
     *
     * @param string $directorFax
     * @return static
     */
    public function setDirectorFax($directorFax)
    {
        $this->directorFax = $directorFax;
        return $this;
    }

    /**
     * Gets as directorEmail
     *
     * @return string
     */
    public function getDirectorEmail()
    {
        return $this->directorEmail;
    }

    /**
     * Sets a new directorEmail
     *
     * @param string $directorEmail
     * @return static
     */
    public function setDirectorEmail($directorEmail)
    {
        $this->directorEmail = $directorEmail;
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

