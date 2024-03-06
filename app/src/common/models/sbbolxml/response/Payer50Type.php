<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing Payer50Type
 *
 *
 * XSD Type: Payer_50
 */
class Payer50Type
{

    /**
     * Номер счёта и БИК перевододателя
     *  Customer Account
     *
     * @property \common\models\sbbolxml\response\AccNumBicType $accDoc
     */
    private $accDoc = null;

    /**
     * Международное наименование плательщика
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Адрес плательщика
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Город (местонахождение) плательщика
     *
     * @property string $place
     */
    private $place = null;

    /**
     * Страна перевододателя
     *
     * @property \common\models\sbbolxml\response\CountryType $country
     */
    private $country = null;

    /**
     * Если потребуется передача наименования и местонахождения перевододателя как 4х35
     *  По умолчанию - не используется
     *
     * @property string $text
     */
    private $text = null;

    /**
     * Gets as accDoc
     *
     * Номер счёта и БИК перевододателя
     *  Customer Account
     *
     * @return \common\models\sbbolxml\response\AccNumBicType
     */
    public function getAccDoc()
    {
        return $this->accDoc;
    }

    /**
     * Sets a new accDoc
     *
     * Номер счёта и БИК перевододателя
     *  Customer Account
     *
     * @param \common\models\sbbolxml\response\AccNumBicType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\sbbolxml\response\AccNumBicType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as name
     *
     * Международное наименование плательщика
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
     * Международное наименование плательщика
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
     * Адрес плательщика
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
     * Адрес плательщика
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
     * Город (местонахождение) плательщика
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
     * Город (местонахождение) плательщика
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
     * Страна перевододателя
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
     * Страна перевододателя
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
     * Gets as text
     *
     * Если потребуется передача наименования и местонахождения перевододателя как 4х35
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

