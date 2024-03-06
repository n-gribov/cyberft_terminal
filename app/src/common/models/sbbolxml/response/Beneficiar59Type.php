<?php

namespace common\models\sbbolxml\response;

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
     * Iso-код валюты счета
     *
     * @property string $currIsoCode
     */
    private $currIsoCode = null;

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
     * @property \common\models\sbbolxml\response\CountryType $country
     */
    private $country = null;

    /**
     * @property \common\models\sbbolxml\response\StateType $state
     */
    private $state = null;

    /**
     * Если потребуется передача наименования и местонахождения перевододателя как 4х35
     *  Customer name
     *  По умолчанию - не используется
     *
     * @property string $text
     */
    private $text = null;

    /**
     * zip-код
     *
     * @property string $zip
     */
    private $zip = null;

    /**
     * ИНН (заполняется в случае внутрибанковского перевода)
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * @property string $beiCode
     */
    private $beiCode = null;

    /**
     * @property string $bankName
     */
    private $bankName = null;

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
     * Gets as currIsoCode
     *
     * Iso-код валюты счета
     *
     * @return string
     */
    public function getCurrIsoCode()
    {
        return $this->currIsoCode;
    }

    /**
     * Sets a new currIsoCode
     *
     * Iso-код валюты счета
     *
     * @param string $currIsoCode
     * @return static
     */
    public function setCurrIsoCode($currIsoCode)
    {
        $this->currIsoCode = $currIsoCode;
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
     * @return \common\models\sbbolxml\response\CountryType
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
     * @param \common\models\sbbolxml\response\CountryType $country
     * @return static
     */
    public function setCountry(\common\models\sbbolxml\response\CountryType $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Gets as state
     *
     * @return \common\models\sbbolxml\response\StateType
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets a new state
     *
     * @param \common\models\sbbolxml\response\StateType $state
     * @return static
     */
    public function setState(\common\models\sbbolxml\response\StateType $state)
    {
        $this->state = $state;
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

    /**
     * Gets as zip
     *
     * zip-код
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
     * zip-код
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
     * Gets as inn
     *
     * ИНН (заполняется в случае внутрибанковского перевода)
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
     * ИНН (заполняется в случае внутрибанковского перевода)
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
     * Gets as beiCode
     *
     * @return string
     */
    public function getBeiCode()
    {
        return $this->beiCode;
    }

    /**
     * Sets a new beiCode
     *
     * @param string $beiCode
     * @return static
     */
    public function setBeiCode($beiCode)
    {
        $this->beiCode = $beiCode;
        return $this;
    }

    /**
     * Gets as bankName
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }


}

