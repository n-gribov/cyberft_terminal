<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType;

/**
 * Class representing CitizenshipAType
 */
class CitizenshipAType
{

    /**
     * Название страны
     *
     * @property string $countryName
     */
    private $countryName = null;

    /**
     * Цифровой код страны
     *
     * @property string $countryNumCode
     */
    private $countryNumCode = null;

    /**
     * Трехбуквенный символьный код страны
     *
     * @property string $countryCode
     */
    private $countryCode = null;

    /**
     * Gets as countryName
     *
     * Название страны
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Sets a new countryName
     *
     * Название страны
     *
     * @param string $countryName
     * @return static
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
        return $this;
    }

    /**
     * Gets as countryNumCode
     *
     * Цифровой код страны
     *
     * @return string
     */
    public function getCountryNumCode()
    {
        return $this->countryNumCode;
    }

    /**
     * Sets a new countryNumCode
     *
     * Цифровой код страны
     *
     * @param string $countryNumCode
     * @return static
     */
    public function setCountryNumCode($countryNumCode)
    {
        $this->countryNumCode = $countryNumCode;
        return $this;
    }

    /**
     * Gets as countryCode
     *
     * Трехбуквенный символьный код страны
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Sets a new countryCode
     *
     * Трехбуквенный символьный код страны
     *
     * @param string $countryCode
     * @return static
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }


}

