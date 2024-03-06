<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AddressRegOfIssType
 *
 *
 * XSD Type: AddressRegOfIss
 */
class AddressRegOfIssType
{

    /**
     * Тип адреса
     *  Возможны значения:
     *  01 – юридический адрес (по умолчанию),
     *  02 – международный адрес,
     *  03 – фактический адрес (по умолчанию для случая, если юр. адрес уже есть в системе),
     *  04 – почтовый адрес,
     *  05 – юридический адрес (ЕГРЮЛ/ЕГРИП),
     *  Список может быть расширен
     *  ДЛЯ МЗП:
     *  01- Место рождения,
     *  02- Адрес регистрации,
     *  03- Адрес проживания.
     *
     * @property string $addressType
     */
    private $addressType = null;

    /**
     * ISO-код страны или код для обозначения особого положения (например лицо без
     *  гражданства)
     *
     * @property string $country
     */
    private $country = null;

    /**
     * Если при импорте из прямого файла не удалось распознать страну, передаем
     *  данные, которые были заполнены пользователем
     *
     * @property \common\models\sbbolxml\request\AddressRegOfIssType\CountryAddAType $countryAdd
     */
    private $countryAdd = null;

    /**
     * Индекс
     *
     * @property string $zip
     */
    private $zip = null;

    /**
     * Субъект/Регион (полное наименование (так храниться в справочнике адресов
     *  организаций в СББОЛ)
     *
     * @property string $sub
     */
    private $sub = null;

    /**
     * Из прямого файла 1С
     *
     * @property \common\models\sbbolxml\request\AddressRegOfIssType\SubAddAType $subAdd
     */
    private $subAdd = null;

    /**
     * Район
     *
     * @property string $area
     */
    private $area = null;

    /**
     * Из прямого файла 1С
     *
     * @property \common\models\sbbolxml\request\AddressRegOfIssType\AreaAddAType $areaAdd
     */
    private $areaAdd = null;

    /**
     * Город
     *
     * @property string $city
     */
    private $city = null;

    /**
     * Из прямого файла 1С
     *
     * @property \common\models\sbbolxml\request\AddressRegOfIssType\CityAddAType $cityAdd
     */
    private $cityAdd = null;

    /**
     * @property \common\models\sbbolxml\request\SettlType $settl
     */
    private $settl = null;

    /**
     * Из прямого файла 1С
     *
     * @property \common\models\sbbolxml\request\AddressRegOfIssType\SettlAddAType $settlAdd
     */
    private $settlAdd = null;

    /**
     * Улица (проспект, переулок и т.д.)
     *
     * @property string $str
     */
    private $str = null;

    /**
     * Из прямого файла 1С
     *
     * @property \common\models\sbbolxml\request\AddressRegOfIssType\StrAddAType $strAdd
     */
    private $strAdd = null;

    /**
     * Номер дома организации клиента
     *
     * @property string $hNumber
     */
    private $hNumber = null;

    /**
     * Корпус\строение дома организации клиента
     *
     * @property string $corp
     */
    private $corp = null;

    /**
     * Номер офиса
     *
     * @property string $office
     */
    private $office = null;

    /**
     * @property string $mailBox
     */
    private $mailBox = null;

    /**
     * @property string $fullAddress
     */
    private $fullAddress = null;

    /**
     * @property string $addInfoAddress
     */
    private $addInfoAddress = null;

    /**
     * Gets as addressType
     *
     * Тип адреса
     *  Возможны значения:
     *  01 – юридический адрес (по умолчанию),
     *  02 – международный адрес,
     *  03 – фактический адрес (по умолчанию для случая, если юр. адрес уже есть в системе),
     *  04 – почтовый адрес,
     *  05 – юридический адрес (ЕГРЮЛ/ЕГРИП),
     *  Список может быть расширен
     *  ДЛЯ МЗП:
     *  01- Место рождения,
     *  02- Адрес регистрации,
     *  03- Адрес проживания.
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
     * Тип адреса
     *  Возможны значения:
     *  01 – юридический адрес (по умолчанию),
     *  02 – международный адрес,
     *  03 – фактический адрес (по умолчанию для случая, если юр. адрес уже есть в системе),
     *  04 – почтовый адрес,
     *  05 – юридический адрес (ЕГРЮЛ/ЕГРИП),
     *  Список может быть расширен
     *  ДЛЯ МЗП:
     *  01- Место рождения,
     *  02- Адрес регистрации,
     *  03- Адрес проживания.
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
     * ISO-код страны или код для обозначения особого положения (например лицо без
     *  гражданства)
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
     * ISO-код страны или код для обозначения особого положения (например лицо без
     *  гражданства)
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
     * Gets as countryAdd
     *
     * Если при импорте из прямого файла не удалось распознать страну, передаем
     *  данные, которые были заполнены пользователем
     *
     * @return \common\models\sbbolxml\request\AddressRegOfIssType\CountryAddAType
     */
    public function getCountryAdd()
    {
        return $this->countryAdd;
    }

    /**
     * Sets a new countryAdd
     *
     * Если при импорте из прямого файла не удалось распознать страну, передаем
     *  данные, которые были заполнены пользователем
     *
     * @param \common\models\sbbolxml\request\AddressRegOfIssType\CountryAddAType $countryAdd
     * @return static
     */
    public function setCountryAdd(\common\models\sbbolxml\request\AddressRegOfIssType\CountryAddAType $countryAdd)
    {
        $this->countryAdd = $countryAdd;
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
     * Субъект/Регион (полное наименование (так храниться в справочнике адресов
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
     * Субъект/Регион (полное наименование (так храниться в справочнике адресов
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
     * Gets as subAdd
     *
     * Из прямого файла 1С
     *
     * @return \common\models\sbbolxml\request\AddressRegOfIssType\SubAddAType
     */
    public function getSubAdd()
    {
        return $this->subAdd;
    }

    /**
     * Sets a new subAdd
     *
     * Из прямого файла 1С
     *
     * @param \common\models\sbbolxml\request\AddressRegOfIssType\SubAddAType $subAdd
     * @return static
     */
    public function setSubAdd(\common\models\sbbolxml\request\AddressRegOfIssType\SubAddAType $subAdd)
    {
        $this->subAdd = $subAdd;
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
     * Gets as areaAdd
     *
     * Из прямого файла 1С
     *
     * @return \common\models\sbbolxml\request\AddressRegOfIssType\AreaAddAType
     */
    public function getAreaAdd()
    {
        return $this->areaAdd;
    }

    /**
     * Sets a new areaAdd
     *
     * Из прямого файла 1С
     *
     * @param \common\models\sbbolxml\request\AddressRegOfIssType\AreaAddAType $areaAdd
     * @return static
     */
    public function setAreaAdd(\common\models\sbbolxml\request\AddressRegOfIssType\AreaAddAType $areaAdd)
    {
        $this->areaAdd = $areaAdd;
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
     * Gets as cityAdd
     *
     * Из прямого файла 1С
     *
     * @return \common\models\sbbolxml\request\AddressRegOfIssType\CityAddAType
     */
    public function getCityAdd()
    {
        return $this->cityAdd;
    }

    /**
     * Sets a new cityAdd
     *
     * Из прямого файла 1С
     *
     * @param \common\models\sbbolxml\request\AddressRegOfIssType\CityAddAType $cityAdd
     * @return static
     */
    public function setCityAdd(\common\models\sbbolxml\request\AddressRegOfIssType\CityAddAType $cityAdd)
    {
        $this->cityAdd = $cityAdd;
        return $this;
    }

    /**
     * Gets as settl
     *
     * @return \common\models\sbbolxml\request\SettlType
     */
    public function getSettl()
    {
        return $this->settl;
    }

    /**
     * Sets a new settl
     *
     * @param \common\models\sbbolxml\request\SettlType $settl
     * @return static
     */
    public function setSettl(\common\models\sbbolxml\request\SettlType $settl)
    {
        $this->settl = $settl;
        return $this;
    }

    /**
     * Gets as settlAdd
     *
     * Из прямого файла 1С
     *
     * @return \common\models\sbbolxml\request\AddressRegOfIssType\SettlAddAType
     */
    public function getSettlAdd()
    {
        return $this->settlAdd;
    }

    /**
     * Sets a new settlAdd
     *
     * Из прямого файла 1С
     *
     * @param \common\models\sbbolxml\request\AddressRegOfIssType\SettlAddAType $settlAdd
     * @return static
     */
    public function setSettlAdd(\common\models\sbbolxml\request\AddressRegOfIssType\SettlAddAType $settlAdd)
    {
        $this->settlAdd = $settlAdd;
        return $this;
    }

    /**
     * Gets as str
     *
     * Улица (проспект, переулок и т.д.)
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
     * Улица (проспект, переулок и т.д.)
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
     * Gets as strAdd
     *
     * Из прямого файла 1С
     *
     * @return \common\models\sbbolxml\request\AddressRegOfIssType\StrAddAType
     */
    public function getStrAdd()
    {
        return $this->strAdd;
    }

    /**
     * Sets a new strAdd
     *
     * Из прямого файла 1С
     *
     * @param \common\models\sbbolxml\request\AddressRegOfIssType\StrAddAType $strAdd
     * @return static
     */
    public function setStrAdd(\common\models\sbbolxml\request\AddressRegOfIssType\StrAddAType $strAdd)
    {
        $this->strAdd = $strAdd;
        return $this;
    }

    /**
     * Gets as hNumber
     *
     * Номер дома организации клиента
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
     * Номер дома организации клиента
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
     * Корпус\строение дома организации клиента
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
     * Корпус\строение дома организации клиента
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
     * Номер офиса
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
     * Номер офиса
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
     * @return string
     */
    public function getMailBox()
    {
        return $this->mailBox;
    }

    /**
     * Sets a new mailBox
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
     * @return string
     */
    public function getFullAddress()
    {
        return $this->fullAddress;
    }

    /**
     * Sets a new fullAddress
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
     * @return string
     */
    public function getAddInfoAddress()
    {
        return $this->addInfoAddress;
    }

    /**
     * Sets a new addInfoAddress
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

