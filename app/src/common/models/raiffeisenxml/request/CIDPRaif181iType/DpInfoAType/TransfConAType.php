<?php

namespace common\models\raiffeisenxml\request\CIDPRaif181iType\DpInfoAType;

/**
 * Class representing TransfConAType
 */
class TransfConAType
{

    /**
     * Банк, в который переводится контракт(кредитный договор)
     *
     * @property string $chngBank
     */
    private $chngBank = null;

    /**
     * Номер соглашения об уступке прав требования/переводе долга
     *
     * @property string $agrNum
     */
    private $agrNum = null;

    /**
     * Дата соглашения об уступке прав требования/переводе долга
     *
     * @property \DateTime $agrDate
     */
    private $agrDate = null;

    /**
     * Наименование
     *
     * @property string $orgName
     */
    private $orgName = null;

    /**
     * ОГРН
     *
     * @property string $ogrn
     */
    private $ogrn = null;

    /**
     * ИНН
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * КПП
     *
     * @property string $kpp
     */
    private $kpp = null;

    /**
     * Адрес
     *
     * @property string $orgAddress
     */
    private $orgAddress = null;

    /**
     * Gets as chngBank
     *
     * Банк, в который переводится контракт(кредитный договор)
     *
     * @return string
     */
    public function getChngBank()
    {
        return $this->chngBank;
    }

    /**
     * Sets a new chngBank
     *
     * Банк, в который переводится контракт(кредитный договор)
     *
     * @param string $chngBank
     * @return static
     */
    public function setChngBank($chngBank)
    {
        $this->chngBank = $chngBank;
        return $this;
    }

    /**
     * Gets as agrNum
     *
     * Номер соглашения об уступке прав требования/переводе долга
     *
     * @return string
     */
    public function getAgrNum()
    {
        return $this->agrNum;
    }

    /**
     * Sets a new agrNum
     *
     * Номер соглашения об уступке прав требования/переводе долга
     *
     * @param string $agrNum
     * @return static
     */
    public function setAgrNum($agrNum)
    {
        $this->agrNum = $agrNum;
        return $this;
    }

    /**
     * Gets as agrDate
     *
     * Дата соглашения об уступке прав требования/переводе долга
     *
     * @return \DateTime
     */
    public function getAgrDate()
    {
        return $this->agrDate;
    }

    /**
     * Sets a new agrDate
     *
     * Дата соглашения об уступке прав требования/переводе долга
     *
     * @param \DateTime $agrDate
     * @return static
     */
    public function setAgrDate(\DateTime $agrDate)
    {
        $this->agrDate = $agrDate;
        return $this;
    }

    /**
     * Gets as orgName
     *
     * Наименование
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
     * Наименование
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
     * Gets as ogrn
     *
     * ОГРН
     *
     * @return string
     */
    public function getOgrn()
    {
        return $this->ogrn;
    }

    /**
     * Sets a new ogrn
     *
     * ОГРН
     *
     * @param string $ogrn
     * @return static
     */
    public function setOgrn($ogrn)
    {
        $this->ogrn = $ogrn;
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
     * Gets as kpp
     *
     * КПП
     *
     * @return string
     */
    public function getKpp()
    {
        return $this->kpp;
    }

    /**
     * Sets a new kpp
     *
     * КПП
     *
     * @param string $kpp
     * @return static
     */
    public function setKpp($kpp)
    {
        $this->kpp = $kpp;
        return $this;
    }

    /**
     * Gets as orgAddress
     *
     * Адрес
     *
     * @return string
     */
    public function getOrgAddress()
    {
        return $this->orgAddress;
    }

    /**
     * Sets a new orgAddress
     *
     * Адрес
     *
     * @param string $orgAddress
     * @return static
     */
    public function setOrgAddress($orgAddress)
    {
        $this->orgAddress = $orgAddress;
        return $this;
    }


}

