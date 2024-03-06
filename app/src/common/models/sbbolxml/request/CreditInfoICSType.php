<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CreditInfoICSType
 *
 * Информация о привлечении резидентом кредита (займа), предоставленного нерезидентами на
 *  синдицированной (консорциональной) основе
 * XSD Type: CreditInfoICS
 */
class CreditInfoICSType
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
     * @property \common\models\sbbolxml\request\CountryNameICSType $country
     */
    private $country = null;

    /**
     * Платеж в счет основного долга
     *
     * @property \common\models\sbbolxml\request\CreditInfoPrincipalICSType $principial
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
     * @return \common\models\sbbolxml\request\CountryNameICSType
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
     * @param \common\models\sbbolxml\request\CountryNameICSType $country
     * @return static
     */
    public function setCountry(\common\models\sbbolxml\request\CountryNameICSType $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Gets as principial
     *
     * Платеж в счет основного долга
     *
     * @return \common\models\sbbolxml\request\CreditInfoPrincipalICSType
     */
    public function getPrincipial()
    {
        return $this->principial;
    }

    /**
     * Sets a new principial
     *
     * Платеж в счет основного долга
     *
     * @param \common\models\sbbolxml\request\CreditInfoPrincipalICSType $principial
     * @return static
     */
    public function setPrincipial(\common\models\sbbolxml\request\CreditInfoPrincipalICSType $principial)
    {
        $this->principial = $principial;
        return $this;
    }


}

