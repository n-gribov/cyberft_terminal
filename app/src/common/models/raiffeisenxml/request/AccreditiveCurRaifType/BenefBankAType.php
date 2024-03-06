<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType;

/**
 * Class representing BenefBankAType
 */
class BenefBankAType
{

    /**
     * swift-код
     *
     * @property string $swift
     */
    private $swift = null;

    /**
     * Наименование
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Адрес
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Город
     *
     * @property string $city
     */
    private $city = null;

    /**
     * Страна
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\BenefBankAType\CountryAType $country
     */
    private $country = null;

    /**
     * Gets as swift
     *
     * swift-код
     *
     * @return string
     */
    public function getSwift()
    {
        return $this->swift;
    }

    /**
     * Sets a new swift
     *
     * swift-код
     *
     * @param string $swift
     * @return static
     */
    public function setSwift($swift)
    {
        $this->swift = $swift;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование
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
     * Наименование
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
     * Адрес
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
     * Адрес
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
     * Gets as country
     *
     * Страна
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\BenefBankAType\CountryAType
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * Страна
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\BenefBankAType\CountryAType $country
     * @return static
     */
    public function setCountry(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\BenefBankAType\CountryAType $country)
    {
        $this->country = $country;
        return $this;
    }


}

