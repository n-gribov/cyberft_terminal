<?php

namespace common\models\sbbolxml\response\OrgInfoType\OrgDataAType\CorrespondentsAType;

/**
 * Class representing CorrespondentAType
 */
class CorrespondentAType
{

    /**
     * Наименование корреспондента
     *
     * @property string $corrName
     */
    private $corrName = null;

    /**
     * ИНН/КИО корреспондента
     *
     * @property string $iNN
     */
    private $iNN = null;

    /**
     * КПП корреспондента
     *
     * @property string $kPP
     */
    private $kPP = null;

    /**
     * Номер счета корреспондента
     *
     * @property string $account
     */
    private $account = null;

    /**
     * БИК банка корреспондента
     *
     * @property string $bIC
     */
    private $bIC = null;

    /**
     * Назвачение платежа
     *
     * @property string $purpose
     */
    private $purpose = null;

    /**
     * Комментарий
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as corrName
     *
     * Наименование корреспондента
     *
     * @return string
     */
    public function getCorrName()
    {
        return $this->corrName;
    }

    /**
     * Sets a new corrName
     *
     * Наименование корреспондента
     *
     * @param string $corrName
     * @return static
     */
    public function setCorrName($corrName)
    {
        $this->corrName = $corrName;
        return $this;
    }

    /**
     * Gets as iNN
     *
     * ИНН/КИО корреспондента
     *
     * @return string
     */
    public function getINN()
    {
        return $this->iNN;
    }

    /**
     * Sets a new iNN
     *
     * ИНН/КИО корреспондента
     *
     * @param string $iNN
     * @return static
     */
    public function setINN($iNN)
    {
        $this->iNN = $iNN;
        return $this;
    }

    /**
     * Gets as kPP
     *
     * КПП корреспондента
     *
     * @return string
     */
    public function getKPP()
    {
        return $this->kPP;
    }

    /**
     * Sets a new kPP
     *
     * КПП корреспондента
     *
     * @param string $kPP
     * @return static
     */
    public function setKPP($kPP)
    {
        $this->kPP = $kPP;
        return $this;
    }

    /**
     * Gets as account
     *
     * Номер счета корреспондента
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
     * Номер счета корреспондента
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
     * Gets as bIC
     *
     * БИК банка корреспондента
     *
     * @return string
     */
    public function getBIC()
    {
        return $this->bIC;
    }

    /**
     * Sets a new bIC
     *
     * БИК банка корреспондента
     *
     * @param string $bIC
     * @return static
     */
    public function setBIC($bIC)
    {
        $this->bIC = $bIC;
        return $this;
    }

    /**
     * Gets as purpose
     *
     * Назвачение платежа
     *
     * @return string
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * Sets a new purpose
     *
     * Назвачение платежа
     *
     * @param string $purpose
     * @return static
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Комментарий
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
     * Комментарий
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

