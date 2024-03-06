<?php

namespace common\models\sbbolxml\response\AdmCashierType\CashierInfoAType;

/**
 * Class representing RegAddrAType
 */
class RegAddrAType
{

    /**
     * Страна
     *
     * @property string $regAddrCountryName
     */
    private $regAddrCountryName = null;

    /**
     * Код страны буквенный
     *
     * @property string $regAddrCountryLetterCode
     */
    private $regAddrCountryLetterCode = null;

    /**
     * Код страны цифровой
     *
     * @property string $regAddrCountryCode
     */
    private $regAddrCountryCode = null;

    /**
     * Индекс
     *
     * @property string $regAddrZip
     */
    private $regAddrZip = null;

    /**
     * Регион
     *
     * @property string $regAddrSub
     */
    private $regAddrSub = null;

    /**
     * Район
     *
     * @property string $regAddrArea
     */
    private $regAddrArea = null;

    /**
     * Город
     *
     * @property string $regAddrCity
     */
    private $regAddrCity = null;

    /**
     * Населенный пункт
     *
     * @property string $regAddrSettl
     */
    private $regAddrSettl = null;

    /**
     * Улица
     *
     * @property string $regAddrStr
     */
    private $regAddrStr = null;

    /**
     * Номер дома
     *
     * @property string $regAddrHousenum
     */
    private $regAddrHousenum = null;

    /**
     * Номер корпуса
     *
     * @property string $regAddrCorp
     */
    private $regAddrCorp = null;

    /**
     * Номер квартиры
     *
     * @property string $regAddrFlat
     */
    private $regAddrFlat = null;

    /**
     * Полный адрес регистрации
     *
     * @property string $regAddrFull
     */
    private $regAddrFull = null;

    /**
     * Gets as regAddrCountryName
     *
     * Страна
     *
     * @return string
     */
    public function getRegAddrCountryName()
    {
        return $this->regAddrCountryName;
    }

    /**
     * Sets a new regAddrCountryName
     *
     * Страна
     *
     * @param string $regAddrCountryName
     * @return static
     */
    public function setRegAddrCountryName($regAddrCountryName)
    {
        $this->regAddrCountryName = $regAddrCountryName;
        return $this;
    }

    /**
     * Gets as regAddrCountryLetterCode
     *
     * Код страны буквенный
     *
     * @return string
     */
    public function getRegAddrCountryLetterCode()
    {
        return $this->regAddrCountryLetterCode;
    }

    /**
     * Sets a new regAddrCountryLetterCode
     *
     * Код страны буквенный
     *
     * @param string $regAddrCountryLetterCode
     * @return static
     */
    public function setRegAddrCountryLetterCode($regAddrCountryLetterCode)
    {
        $this->regAddrCountryLetterCode = $regAddrCountryLetterCode;
        return $this;
    }

    /**
     * Gets as regAddrCountryCode
     *
     * Код страны цифровой
     *
     * @return string
     */
    public function getRegAddrCountryCode()
    {
        return $this->regAddrCountryCode;
    }

    /**
     * Sets a new regAddrCountryCode
     *
     * Код страны цифровой
     *
     * @param string $regAddrCountryCode
     * @return static
     */
    public function setRegAddrCountryCode($regAddrCountryCode)
    {
        $this->regAddrCountryCode = $regAddrCountryCode;
        return $this;
    }

    /**
     * Gets as regAddrZip
     *
     * Индекс
     *
     * @return string
     */
    public function getRegAddrZip()
    {
        return $this->regAddrZip;
    }

    /**
     * Sets a new regAddrZip
     *
     * Индекс
     *
     * @param string $regAddrZip
     * @return static
     */
    public function setRegAddrZip($regAddrZip)
    {
        $this->regAddrZip = $regAddrZip;
        return $this;
    }

    /**
     * Gets as regAddrSub
     *
     * Регион
     *
     * @return string
     */
    public function getRegAddrSub()
    {
        return $this->regAddrSub;
    }

    /**
     * Sets a new regAddrSub
     *
     * Регион
     *
     * @param string $regAddrSub
     * @return static
     */
    public function setRegAddrSub($regAddrSub)
    {
        $this->regAddrSub = $regAddrSub;
        return $this;
    }

    /**
     * Gets as regAddrArea
     *
     * Район
     *
     * @return string
     */
    public function getRegAddrArea()
    {
        return $this->regAddrArea;
    }

    /**
     * Sets a new regAddrArea
     *
     * Район
     *
     * @param string $regAddrArea
     * @return static
     */
    public function setRegAddrArea($regAddrArea)
    {
        $this->regAddrArea = $regAddrArea;
        return $this;
    }

    /**
     * Gets as regAddrCity
     *
     * Город
     *
     * @return string
     */
    public function getRegAddrCity()
    {
        return $this->regAddrCity;
    }

    /**
     * Sets a new regAddrCity
     *
     * Город
     *
     * @param string $regAddrCity
     * @return static
     */
    public function setRegAddrCity($regAddrCity)
    {
        $this->regAddrCity = $regAddrCity;
        return $this;
    }

    /**
     * Gets as regAddrSettl
     *
     * Населенный пункт
     *
     * @return string
     */
    public function getRegAddrSettl()
    {
        return $this->regAddrSettl;
    }

    /**
     * Sets a new regAddrSettl
     *
     * Населенный пункт
     *
     * @param string $regAddrSettl
     * @return static
     */
    public function setRegAddrSettl($regAddrSettl)
    {
        $this->regAddrSettl = $regAddrSettl;
        return $this;
    }

    /**
     * Gets as regAddrStr
     *
     * Улица
     *
     * @return string
     */
    public function getRegAddrStr()
    {
        return $this->regAddrStr;
    }

    /**
     * Sets a new regAddrStr
     *
     * Улица
     *
     * @param string $regAddrStr
     * @return static
     */
    public function setRegAddrStr($regAddrStr)
    {
        $this->regAddrStr = $regAddrStr;
        return $this;
    }

    /**
     * Gets as regAddrHousenum
     *
     * Номер дома
     *
     * @return string
     */
    public function getRegAddrHousenum()
    {
        return $this->regAddrHousenum;
    }

    /**
     * Sets a new regAddrHousenum
     *
     * Номер дома
     *
     * @param string $regAddrHousenum
     * @return static
     */
    public function setRegAddrHousenum($regAddrHousenum)
    {
        $this->regAddrHousenum = $regAddrHousenum;
        return $this;
    }

    /**
     * Gets as regAddrCorp
     *
     * Номер корпуса
     *
     * @return string
     */
    public function getRegAddrCorp()
    {
        return $this->regAddrCorp;
    }

    /**
     * Sets a new regAddrCorp
     *
     * Номер корпуса
     *
     * @param string $regAddrCorp
     * @return static
     */
    public function setRegAddrCorp($regAddrCorp)
    {
        $this->regAddrCorp = $regAddrCorp;
        return $this;
    }

    /**
     * Gets as regAddrFlat
     *
     * Номер квартиры
     *
     * @return string
     */
    public function getRegAddrFlat()
    {
        return $this->regAddrFlat;
    }

    /**
     * Sets a new regAddrFlat
     *
     * Номер квартиры
     *
     * @param string $regAddrFlat
     * @return static
     */
    public function setRegAddrFlat($regAddrFlat)
    {
        $this->regAddrFlat = $regAddrFlat;
        return $this;
    }

    /**
     * Gets as regAddrFull
     *
     * Полный адрес регистрации
     *
     * @return string
     */
    public function getRegAddrFull()
    {
        return $this->regAddrFull;
    }

    /**
     * Sets a new regAddrFull
     *
     * Полный адрес регистрации
     *
     * @param string $regAddrFull
     * @return static
     */
    public function setRegAddrFull($regAddrFull)
    {
        $this->regAddrFull = $regAddrFull;
        return $this;
    }


}

