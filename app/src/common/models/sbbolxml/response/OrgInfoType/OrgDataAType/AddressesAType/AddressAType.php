<?php

namespace common\models\sbbolxml\response\OrgInfoType\OrgDataAType\AddressesAType;

/**
 * Class representing AddressAType
 */
class AddressAType
{

    /**
     * Код наименования типа адреса
     *
     * @property string $addressTypeCode
     */
    private $addressTypeCode = null;

    /**
     * Наименование типа адреса (например, Юридический адрес)
     *
     * @property string $addressType
     */
    private $addressType = null;

    /**
     * Трехбуквенный код страны (ISO 3166-1 alpha-3)
     *
     * @property string $country
     */
    private $country = null;

    /**
     * Наименование страны
     *
     * @property string $countryName
     */
    private $countryName = null;

    /**
     * Краткое наименование страны
     *
     * @property string $countryShortName
     */
    private $countryShortName = null;

    /**
     * Индекс
     *
     * @property string $zip
     */
    private $zip = null;

    /**
     * Субъект/Регион
     *  (полное наименование (так
     *  храниться в справочнике адресов
     *  организаций в СББОЛ)
     *
     * @property string $sub
     */
    private $sub = null;

    /**
     * Район
     *
     * @property string $area
     */
    private $area = null;

    /**
     * Город
     *
     * @property string $city
     */
    private $city = null;

    /**
     * Тип. нас. пункта
     *  (например, РП)
     *
     * @property string $settlType
     */
    private $settlType = null;

    /**
     * Наименование нас.
     *  пункта
     *
     * @property string $settlName
     */
    private $settlName = null;

    /**
     * Улица
     *
     * @property string $str
     */
    private $str = null;

    /**
     * Номер дома
     *
     * @property string $hNumber
     */
    private $hNumber = null;

    /**
     * Корпус
     *
     * @property string $corp
     */
    private $corp = null;

    /**
     * Офис
     *
     * @property string $office
     */
    private $office = null;

    /**
     * Ящик
     *
     * @property string $mailBox
     */
    private $mailBox = null;

    /**
     * Полный адрес
     *
     * @property string $fullAddress
     */
    private $fullAddress = null;

    /**
     * Примечание к
     *  адресу
     *
     * @property string $addInfoAddress
     */
    private $addInfoAddress = null;

    /**
     * Gets as addressTypeCode
     *
     * Код наименования типа адреса
     *
     * @return string
     */
    public function getAddressTypeCode()
    {
        return $this->addressTypeCode;
    }

    /**
     * Sets a new addressTypeCode
     *
     * Код наименования типа адреса
     *
     * @param string $addressTypeCode
     * @return static
     */
    public function setAddressTypeCode($addressTypeCode)
    {
        $this->addressTypeCode = $addressTypeCode;
        return $this;
    }

    /**
     * Gets as addressType
     *
     * Наименование типа адреса (например, Юридический адрес)
     *
     * @return string
     */
    public function getAddressType()
    {
        return $this->addressType;
    }

    /**
     * Sets a new addressType
     *
     * Наименование типа адреса (например, Юридический адрес)
     *
     * @param string $addressType
     * @return static
     */
    public function setAddressType($addressType)
    {
        $this->addressType = $addressType;
        return $this;
    }

    /**
     * Gets as country
     *
     * Трехбуквенный код страны (ISO 3166-1 alpha-3)
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * Трехбуквенный код страны (ISO 3166-1 alpha-3)
     *
     * @param string $country
     * @return static
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Gets as countryName
     *
     * Наименование страны
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Sets a new countryName
     *
     * Наименование страны
     *
     * @param string $countryName
     * @return static
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
        return $this;
    }

    /**
     * Gets as countryShortName
     *
     * Краткое наименование страны
     *
     * @return string
     */
    public function getCountryShortName()
    {
        return $this->countryShortName;
    }

    /**
     * Sets a new countryShortName
     *
     * Краткое наименование страны
     *
     * @param string $countryShortName
     * @return static
     */
    public function setCountryShortName($countryShortName)
    {
        $this->countryShortName = $countryShortName;
        return $this;
    }

    /**
     * Gets as zip
     *
     * Индекс
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Sets a new zip
     *
     * Индекс
     *
     * @param string $zip
     * @return static
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * Gets as sub
     *
     * Субъект/Регион
     *  (полное наименование (так
     *  храниться в справочнике адресов
     *  организаций в СББОЛ)
     *
     * @return string
     */
    public function getSub()
    {
        return $this->sub;
    }

    /**
     * Sets a new sub
     *
     * Субъект/Регион
     *  (полное наименование (так
     *  храниться в справочнике адресов
     *  организаций в СББОЛ)
     *
     * @param string $sub
     * @return static
     */
    public function setSub($sub)
    {
        $this->sub = $sub;
        return $this;
    }

    /**
     * Gets as area
     *
     * Район
     *
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Sets a new area
     *
     * Район
     *
     * @param string $area
     * @return static
     */
    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }

    /**
     * Gets as city
     *
     * Город
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets a new city
     *
     * Город
     *
     * @param string $city
     * @return static
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Gets as settlType
     *
     * Тип. нас. пункта
     *  (например, РП)
     *
     * @return string
     */
    public function getSettlType()
    {
        return $this->settlType;
    }

    /**
     * Sets a new settlType
     *
     * Тип. нас. пункта
     *  (например, РП)
     *
     * @param string $settlType
     * @return static
     */
    public function setSettlType($settlType)
    {
        $this->settlType = $settlType;
        return $this;
    }

    /**
     * Gets as settlName
     *
     * Наименование нас.
     *  пункта
     *
     * @return string
     */
    public function getSettlName()
    {
        return $this->settlName;
    }

    /**
     * Sets a new settlName
     *
     * Наименование нас.
     *  пункта
     *
     * @param string $settlName
     * @return static
     */
    public function setSettlName($settlName)
    {
        $this->settlName = $settlName;
        return $this;
    }

    /**
     * Gets as str
     *
     * Улица
     *
     * @return string
     */
    public function getStr()
    {
        return $this->str;
    }

    /**
     * Sets a new str
     *
     * Улица
     *
     * @param string $str
     * @return static
     */
    public function setStr($str)
    {
        $this->str = $str;
        return $this;
    }

    /**
     * Gets as hNumber
     *
     * Номер дома
     *
     * @return string
     */
    public function getHNumber()
    {
        return $this->hNumber;
    }

    /**
     * Sets a new hNumber
     *
     * Номер дома
     *
     * @param string $hNumber
     * @return static
     */
    public function setHNumber($hNumber)
    {
        $this->hNumber = $hNumber;
        return $this;
    }

    /**
     * Gets as corp
     *
     * Корпус
     *
     * @return string
     */
    public function getCorp()
    {
        return $this->corp;
    }

    /**
     * Sets a new corp
     *
     * Корпус
     *
     * @param string $corp
     * @return static
     */
    public function setCorp($corp)
    {
        $this->corp = $corp;
        return $this;
    }

    /**
     * Gets as office
     *
     * Офис
     *
     * @return string
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * Sets a new office
     *
     * Офис
     *
     * @param string $office
     * @return static
     */
    public function setOffice($office)
    {
        $this->office = $office;
        return $this;
    }

    /**
     * Gets as mailBox
     *
     * Ящик
     *
     * @return string
     */
    public function getMailBox()
    {
        return $this->mailBox;
    }

    /**
     * Sets a new mailBox
     *
     * Ящик
     *
     * @param string $mailBox
     * @return static
     */
    public function setMailBox($mailBox)
    {
        $this->mailBox = $mailBox;
        return $this;
    }

    /**
     * Gets as fullAddress
     *
     * Полный адрес
     *
     * @return string
     */
    public function getFullAddress()
    {
        return $this->fullAddress;
    }

    /**
     * Sets a new fullAddress
     *
     * Полный адрес
     *
     * @param string $fullAddress
     * @return static
     */
    public function setFullAddress($fullAddress)
    {
        $this->fullAddress = $fullAddress;
        return $this;
    }

    /**
     * Gets as addInfoAddress
     *
     * Примечание к
     *  адресу
     *
     * @return string
     */
    public function getAddInfoAddress()
    {
        return $this->addInfoAddress;
    }

    /**
     * Sets a new addInfoAddress
     *
     * Примечание к
     *  адресу
     *
     * @param string $addInfoAddress
     * @return static
     */
    public function setAddInfoAddress($addInfoAddress)
    {
        $this->addInfoAddress = $addInfoAddress;
        return $this;
    }


}

