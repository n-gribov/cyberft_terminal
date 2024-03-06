<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DealPassBeneficiarType
 *
 * Реквизиты иностранного контрагента
 * XSD Type: DealPassBeneficiar
 */
class DealPassBeneficiarType
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
     * @property \common\models\sbbolxml\request\CountryNameType $country
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
     * @return \common\models\sbbolxml\request\CountryNameType
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
     * @param \common\models\sbbolxml\request\CountryNameType $country
     * @return static
     */
    public function setCountry(\common\models\sbbolxml\request\CountryNameType $country)
    {
        $this->country = $country;
        return $this;
    }


}

