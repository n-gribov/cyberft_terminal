<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing BeneficiarInfoType
 *
 * Реквизиты иностранного контрагента
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
     * @property \common\models\raiffeisenxml\request\CountryType $country
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
     * @return \common\models\raiffeisenxml\request\CountryType
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
     * @param \common\models\raiffeisenxml\request\CountryType $country
     * @return static
     */
    public function setCountry(\common\models\raiffeisenxml\request\CountryType $country)
    {
        $this->country = $country;
        return $this;
    }


}

