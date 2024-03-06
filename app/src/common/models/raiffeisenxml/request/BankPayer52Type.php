<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing BankPayer52Type
 *
 *
 * XSD Type: BankPayer_52
 */
class BankPayer52Type
{

    /**
     * SWIFT-код банка плательщика
     *  Bank Identification Code
     *
     * @property string $bIC
     */
    private $bIC = null;

    /**
     * Клиринговый код банка плательщика
     *  National Code
     *  например, FW123456789 или FW
     *
     * @property string $nCode
     */
    private $nCode = null;

    /**
     * Наименование банка плательщика
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Наименование филиала банка плательщика
     *
     * @property string $branchName
     */
    private $branchName = null;

    /**
     * Адрес банка плательщика
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Местонахождение банка плательщика
     *
     * @property string $place
     */
    private $place = null;

    /**
     * Код страны банка плательщика
     *
     * @property \common\models\raiffeisenxml\request\CountryType $country
     */
    private $country = null;

    /**
     * Если потребуется передача наименования и местонахождения как 4х35
     *  Name and Address
     *  По умолчанию - не используется
     *
     * @property string $text
     */
    private $text = null;

    /**
     * Gets as bIC
     *
     * SWIFT-код банка плательщика
     *  Bank Identification Code
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
     * SWIFT-код банка плательщика
     *  Bank Identification Code
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
     * Клиринговый код банка плательщика
     *  National Code
     *  например, FW123456789 или FW
     *
     * @return string
     */
    public function getNCode()
    {
        return $this->nCode;
    }

    /**
     * Sets a new nCode
     *
     * Клиринговый код банка плательщика
     *  National Code
     *  например, FW123456789 или FW
     *
     * @param string $nCode
     * @return static
     */
    public function setNCode($nCode)
    {
        $this->nCode = $nCode;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование банка плательщика
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
     * Наименование банка плательщика
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
     * Наименование филиала банка плательщика
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
     * Наименование филиала банка плательщика
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
     * Адрес банка плательщика
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
     * Адрес банка плательщика
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
     * Местонахождение банка плательщика
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
     * Местонахождение банка плательщика
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
     * Код страны банка плательщика
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
     * Код страны банка плательщика
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
     * Если потребуется передача наименования и местонахождения как 4х35
     *  Name and Address
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
     * Если потребуется передача наименования и местонахождения как 4х35
     *  Name and Address
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

