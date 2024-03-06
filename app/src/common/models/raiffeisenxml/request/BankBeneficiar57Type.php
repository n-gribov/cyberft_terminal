<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing BankBeneficiar57Type
 *
 *
 * XSD Type: BankBeneficiar_57
 */
class BankBeneficiar57Type
{

    /**
     * SWIFT-код банка бенефициара
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
     * Корреспондентский счёт банка бенефициара
     *
     * @property string $corrAcc
     */
    private $corrAcc = null;

    /**
     * Наименование банка бенефициара + филиал, если значение заполняется из справочника
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Заполняется, если значение филиала отличается от справочного
     *
     * @property string $branchName
     */
    private $branchName = null;

    /**
     * Адрес банка бенефициара
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Местонахождение банка бенефициара
     *
     * @property string $place
     */
    private $place = null;

    /**
     * Код страны банка бенефициара
     *
     * @property \common\models\raiffeisenxml\request\CountryType $country
     */
    private $country = null;

    /**
     * Gets as bIC
     *
     * SWIFT-код банка бенефициара
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
     * SWIFT-код банка бенефициара
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
     * Gets as corrAcc
     *
     * Корреспондентский счёт банка бенефициара
     *
     * @return string
     */
    public function getCorrAcc()
    {
        return $this->corrAcc;
    }

    /**
     * Sets a new corrAcc
     *
     * Корреспондентский счёт банка бенефициара
     *
     * @param string $corrAcc
     * @return static
     */
    public function setCorrAcc($corrAcc)
    {
        $this->corrAcc = $corrAcc;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование банка бенефициара + филиал, если значение заполняется из справочника
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
     * Наименование банка бенефициара + филиал, если значение заполняется из справочника
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
     * Gets as branchName
     *
     * Заполняется, если значение филиала отличается от справочного
     *
     * @return string
     */
    public function getBranchName()
    {
        return $this->branchName;
    }

    /**
     * Sets a new branchName
     *
     * Заполняется, если значение филиала отличается от справочного
     *
     * @param string $branchName
     * @return static
     */
    public function setBranchName($branchName)
    {
        $this->branchName = $branchName;
        return $this;
    }

    /**
     * Gets as address
     *
     * Адрес банка бенефициара
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
     * Адрес банка бенефициара
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
     * Местонахождение банка бенефициара
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
     * Местонахождение банка бенефициара
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
     * Код страны банка бенефициара
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
     * Код страны банка бенефициара
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

