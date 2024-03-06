<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing Beneficiar59Type
 *
 *
 * XSD Type: Beneficiar_59
 */
class Beneficiar59Type
{

    /**
     * Счет бенефицира
     *
     * @property string $accBeneficiar
     */
    private $accBeneficiar = null;

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
     * @property \common\models\raiffeisenxml\request\CountryType $country
     */
    private $country = null;

    /**
     * Если потребуется передача наименования и местонахождения перевододателя как 4х35
     *  Customer name
     *  По умолчанию - не используется
     *
     * @property string $text
     */
    private $text = null;

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
     * @return \common\models\raiffeisenxml\request\CountryType
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
     * @param \common\models\raiffeisenxml\request\CountryType $country
     * @return static
     */
    public function setCountry(\common\models\raiffeisenxml\request\CountryType $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Gets as text
     *
     * Если потребуется передача наименования и местонахождения перевододателя как 4х35
     *  Customer name
     *  По умолчанию - не используется
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets a new text
     *
     * Если потребуется передача наименования и местонахождения перевододателя как 4х35
     *  Customer name
     *  По умолчанию - не используется
     *
     * @param string $text
     * @return static
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }


}

