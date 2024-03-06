<?php

namespace common\models\sbbolxml\request;

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
     * @property \common\models\sbbolxml\request\CliringCodeType $cliringCode
     */
    private $cliringCode = null;

    /**
     * Корреспондентский счёт банка бенефициара
     *
     * @property string $corrAcc
     */
    private $corrAcc = null;

    /**
     * Наименование банка бенефициара
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Наименование филиала банка бенефициара
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
     * @property \common\models\sbbolxml\request\CountryType $country
     */
    private $country = null;

    /**
     * Штат
     *
     * @property \common\models\sbbolxml\request\StateType $state
     */
    private $state = null;

    /**
     * Zip-код
     *
     * @property string $zip
     */
    private $zip = null;

    /**
     * Если потребуется передача наименования и местонахождения как 4х35
     *  Name and Address
     *  По умолчанию - не используется
     *
     * @property string $text
     */
    private $text = null;

    /**
     * Филиал банка бенефициара
     *
     * @property string $filial
     */
    private $filial = null;

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
     * Gets as cliringCode
     *
     * @return \common\models\sbbolxml\request\CliringCodeType
     */
    public function getCliringCode()
    {
        return $this->cliringCode;
    }

    /**
     * Sets a new cliringCode
     *
     * @param \common\models\sbbolxml\request\CliringCodeType $cliringCode
     * @return static
     */
    public function setCliringCode(\common\models\sbbolxml\request\CliringCodeType $cliringCode)
    {
        $this->cliringCode = $cliringCode;
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
     * Наименование банка бенефициара
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
     * Наименование банка бенефициара
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
     * Наименование филиала банка бенефициара
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
     * Наименование филиала банка бенефициара
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
     * @return \common\models\sbbolxml\request\CountryType
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
     * @param \common\models\sbbolxml\request\CountryType $country
     * @return static
     */
    public function setCountry(\common\models\sbbolxml\request\CountryType $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Gets as state
     *
     * Штат
     *
     * @return \common\models\sbbolxml\request\StateType
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets a new state
     *
     * Штат
     *
     * @param \common\models\sbbolxml\request\StateType $state
     * @return static
     */
    public function setState(\common\models\sbbolxml\request\StateType $state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Gets as zip
     *
     * Zip-код
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
     * Zip-код
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

    /**
     * Gets as filial
     *
     * Филиал банка бенефициара
     *
     * @return string
     */
    public function getFilial()
    {
        return $this->filial;
    }

    /**
     * Sets a new filial
     *
     * Филиал банка бенефициара
     *
     * @param string $filial
     * @return static
     */
    public function setFilial($filial)
    {
        $this->filial = $filial;
        return $this;
    }


}

