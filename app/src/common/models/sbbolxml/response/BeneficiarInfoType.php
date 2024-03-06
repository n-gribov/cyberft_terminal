<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing BeneficiarInfoType
 *
 * Реквизиты нерезидента
 * XSD Type: BeneficiarInfo
 */
class BeneficiarInfoType
{

    /**
     * Имя иностранного контрагента
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Страна иностранного контрагента
     *
     * @property \common\models\sbbolxml\response\CountryNameType $country
     */
    private $country = null;

    /**
     * Gets as name
     *
     * Имя иностранного контрагента
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
     * Имя иностранного контрагента
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
     * Gets as country
     *
     * Страна иностранного контрагента
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
     * Страна иностранного контрагента
     *
     * @param \common\models\sbbolxml\response\CountryNameType $country
     * @return static
     */
    public function setCountry(\common\models\sbbolxml\response\CountryNameType $country)
    {
        $this->country = $country;
        return $this;
    }


}

