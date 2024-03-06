<?php

namespace common\models\sbbolxml\response;

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
     * Клиринговый код банка-посредника
     *
     * @property \common\models\sbbolxml\response\CliringCodeType $cliringCode
     */
    private $cliringCode = null;

    /**
     * Наименование банка-посредника
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Наименование филиала банка-посредника
     *
     * @property string $branchName
     */
    private $branchName = null;

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
     * @property \common\models\sbbolxml\response\CountryType $country
     */
    private $country = null;

    /**
     * Штат
     *
     * @property \common\models\sbbolxml\response\StateType $state
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
     * Gets as cliringCode
     *
     * Клиринговый код банка-посредника
     *
     * @return \common\models\sbbolxml\response\CliringCodeType
     */
    public function getCliringCode()
    {
        return $this->cliringCode;
    }

    /**
     * Sets a new cliringCode
     *
     * Клиринговый код банка-посредника
     *
     * @param \common\models\sbbolxml\response\CliringCodeType $cliringCode
     * @return static
     */
    public function setCliringCode(\common\models\sbbolxml\response\CliringCodeType $cliringCode)
    {
        $this->cliringCode = $cliringCode;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование банка-посредника
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
     * Наименование банка-посредника
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
     * Наименование филиала банка-посредника
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
     * Наименование филиала банка-посредника
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
     * @return \common\models\sbbolxml\response\CountryType
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
     * Штат
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
     * Штат
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


}

