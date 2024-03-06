<?php

namespace common\models\sbbolxml\request\AdmCashierType\CashierInfoAType;

/**
 * Class representing RegAddrAType
 */
class RegAddrAType
{

    /**
     * Страна
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property string $regAddrCountryName
     */
    private $regAddrCountryName = null;

    /**
     * Код страны буквенный
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property string $regAddrCountryLetterCode
     */
    private $regAddrCountryLetterCode = null;

    /**
     * Код страны цифровой
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property string $regAddrCountryCode
     */
    private $regAddrCountryCode = null;

    /**
     * Индекс
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property string $regAddrZip
     */
    private $regAddrZip = null;

    /**
     * Регион
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property string $regAddrSub
     */
    private $regAddrSub = null;

    /**
     * Район
     *  Версия 1
     *
     * @property string $regAddrArea
     */
    private $regAddrArea = null;

    /**
     * Город
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ, ЕСЛИ НЕ ЗАПОЛНЕН @regAddrSettl
     *  Версия 1
     *
     * @property string $regAddrCity
     */
    private $regAddrCity = null;

    /**
     * Населенный пункт
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ, ЕСЛИ НЕ ЗАПОЛНЕН @regAddrCity
     *  Версия 1
     *
     * @property string $regAddrSettl
     */
    private $regAddrSettl = null;

    /**
     * Улица
     *  Версия 1
     *
     * @property string $regAddrStr
     */
    private $regAddrStr = null;

    /**
     * Номер дома
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
     *
     * @property string $regAddrHousenum
     */
    private $regAddrHousenum = null;

    /**
     * Номер корпуса
     *  Версия 1
     *
     * @property string $regAddrCorp
     */
    private $regAddrCorp = null;

    /**
     * Номер квартиры
     *  Версия 1
     *
     * @property string $regAddrFlat
     */
    private $regAddrFlat = null;

    /**
     * Полный адрес регистрации
     *  Заполняется как « Страна, Индекс, Регион, Район, Город, Населенный пункт, Улица, Номер дома, Номер корпуса, Номер квартиры»
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 11
     *
     * @property string $regAddrFull
     */
    private $regAddrFull = null;

    /**
     * Gets as regAddrCountryName
     *
     * Страна
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  Версия 1
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
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ, ЕСЛИ НЕ ЗАПОЛНЕН @regAddrSettl
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ, ЕСЛИ НЕ ЗАПОЛНЕН @regAddrSettl
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ, ЕСЛИ НЕ ЗАПОЛНЕН @regAddrCity
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ, ЕСЛИ НЕ ЗАПОЛНЕН @regAddrCity
     *  Версия 1
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
     *  Версия 1
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
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 1
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
     *  Версия 1
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
     *  Версия 1
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
     *  Версия 1
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
     *  Версия 1
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
     *  Заполняется как « Страна, Индекс, Регион, Район, Город, Населенный пункт, Улица, Номер дома, Номер корпуса, Номер квартиры»
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 11
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
     *  Заполняется как « Страна, Индекс, Регион, Район, Город, Населенный пункт, Улица, Номер дома, Номер корпуса, Номер квартиры»
     *  ОБЯЗАТЕЛЬНО ДЛЯ ЗАПОЛНЕНИЯ
     *  Версия 11
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

