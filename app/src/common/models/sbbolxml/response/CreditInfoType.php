<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CreditInfoType
 *
 * Информация о привлечении резидентом кредита (займа), предоставленного нерезидентами на синдицированной (консорциональной) основе
 * XSD Type: CreditInfo
 */
class CreditInfoType
{

    /**
     * Наименование нерезидента
     *
     * @property string $nonResident
     */
    private $nonResident = null;

    /**
     * Страна нерезидента
     *
     * @property \common\models\sbbolxml\response\CountryNameType $country
     */
    private $country = null;

    /**
     * В счет основного долга
     *
     * @property \common\models\sbbolxml\response\PrincipalType $principial
     */
    private $principial = null;

    /**
     * Gets as nonResident
     *
     * Наименование нерезидента
     *
     * @return string
     */
    public function getNonResident()
    {
        return $this->nonResident;
    }

    /**
     * Sets a new nonResident
     *
     * Наименование нерезидента
     *
     * @param string $nonResident
     * @return static
     */
    public function setNonResident($nonResident)
    {
        $this->nonResident = $nonResident;
        return $this;
    }

    /**
     * Gets as country
     *
     * Страна нерезидента
     *
     * @return \common\models\sbbolxml\response\CountryNameType
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * Страна нерезидента
     *
     * @param \common\models\sbbolxml\response\CountryNameType $country
     * @return static
     */
    public function setCountry(\common\models\sbbolxml\response\CountryNameType $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Gets as principial
     *
     * В счет основного долга
     *
     * @return \common\models\sbbolxml\response\PrincipalType
     */
    public function getPrincipial()
    {
        return $this->principial;
    }

    /**
     * Sets a new principial
     *
     * В счет основного долга
     *
     * @param \common\models\sbbolxml\response\PrincipalType $principial
     * @return static
     */
    public function setPrincipial(\common\models\sbbolxml\response\PrincipalType $principial)
    {
        $this->principial = $principial;
        return $this;
    }


}

