<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing Beneficiar59RaifNoNameType
 *
 *
 * XSD Type: Beneficiar_59RaifNoName
 */
class Beneficiar59RaifNoNameType
{

    /**
     * Счет бенефицира
     *
     * @property string $accBeneficiar
     */
    private $accBeneficiar = null;

    /**
     * @property string $sWIFT
     */
    private $sWIFT = null;

    /**
     * Наименование бенефицира
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Адрес бенефицира
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Город (месторасположение) бенефицира
     *
     * @property string $place
     */
    private $place = null;

    /**
     * Страна бенефицира
     *
     * @property \common\models\raiffeisenxml\request\CountryNoNameType $country
     */
    private $country = null;

    /**
     * Gets as accBeneficiar
     *
     * Счет бенефицира
     *
     * @return string
     */
    public function getAccBeneficiar()
    {
        return $this->accBeneficiar;
    }

    /**
     * Sets a new accBeneficiar
     *
     * Счет бенефицира
     *
     * @param string $accBeneficiar
     * @return static
     */
    public function setAccBeneficiar($accBeneficiar)
    {
        $this->accBeneficiar = $accBeneficiar;
        return $this;
    }

    /**
     * Gets as sWIFT
     *
     * @return string
     */
    public function getSWIFT()
    {
        return $this->sWIFT;
    }

    /**
     * Sets a new sWIFT
     *
     * @param string $sWIFT
     * @return static
     */
    public function setSWIFT($sWIFT)
    {
        $this->sWIFT = $sWIFT;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование бенефицира
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
     * Наименование бенефицира
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
     * Адрес бенефицира
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
     * Адрес бенефицира
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
     * Город (месторасположение) бенефицира
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
     * Город (месторасположение) бенефицира
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
     * Страна бенефицира
     *
     * @return \common\models\raiffeisenxml\request\CountryNoNameType
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * Страна бенефицира
     *
     * @param \common\models\raiffeisenxml\request\CountryNoNameType $country
     * @return static
     */
    public function setCountry(\common\models\raiffeisenxml\request\CountryNoNameType $country)
    {
        $this->country = $country;
        return $this;
    }


}

