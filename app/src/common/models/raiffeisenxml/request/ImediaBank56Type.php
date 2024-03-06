<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing ImediaBank56Type
 *
 *
 * XSD Type: ImediaBank_56
 */
class ImediaBank56Type
{

    /**
     * SWIFT-код банка-посредника
     *
     * @property string $bIC
     */
    private $bIC = null;

    /**
     * Клиринговый код
     *
     * @property \common\models\raiffeisenxml\request\NCodeType $nCode
     */
    private $nCode = null;

    /**
     * Наименование банка-посредника + филиал, если значение заполняется из справочника
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Адрес банка-посредника
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Местонахождение банка-посредника
     *
     * @property string $place
     */
    private $place = null;

    /**
     * Код страны банка-посредника
     *
     * @property \common\models\raiffeisenxml\request\CountryType $country
     */
    private $country = null;

    /**
     * Gets as bIC
     *
     * SWIFT-код банка-посредника
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
     * SWIFT-код банка-посредника
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
     * Gets as nCode
     *
     * Клиринговый код
     *
     * @return \common\models\raiffeisenxml\request\NCodeType
     */
    public function getNCode()
    {
        return $this->nCode;
    }

    /**
     * Sets a new nCode
     *
     * Клиринговый код
     *
     * @param \common\models\raiffeisenxml\request\NCodeType $nCode
     * @return static
     */
    public function setNCode(\common\models\raiffeisenxml\request\NCodeType $nCode)
    {
        $this->nCode = $nCode;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование банка-посредника + филиал, если значение заполняется из справочника
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
     * Наименование банка-посредника + филиал, если значение заполняется из справочника
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
     * Gets as address
     *
     * Адрес банка-посредника
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets a new address
     *
     * Адрес банка-посредника
     *
     * @param string $address
     * @return static
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Gets as place
     *
     * Местонахождение банка-посредника
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Sets a new place
     *
     * Местонахождение банка-посредника
     *
     * @param string $place
     * @return static
     */
    public function setPlace($place)
    {
        $this->place = $place;
        return $this;
    }

    /**
     * Gets as country
     *
     * Код страны банка-посредника
     *
     * @return \common\models\raiffeisenxml\request\CountryType
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * Код страны банка-посредника
     *
     * @param \common\models\raiffeisenxml\request\CountryType $country
     * @return static
     */
    public function setCountry(\common\models\raiffeisenxml\request\CountryType $country)
    {
        $this->country = $country;
        return $this;
    }


}

