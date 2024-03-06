<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing BeneficiarInfoDPRaifType
 *
 * Реквизиты иностранного контрагента
 * XSD Type: BeneficiarInfoDPRaif
 */
class BeneficiarInfoDPRaifType
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
     * @property \common\models\raiffeisenxml\request\CountryDPRaifType $country
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
     * @return \common\models\raiffeisenxml\request\CountryDPRaifType
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
     * @param \common\models\raiffeisenxml\request\CountryDPRaifType $country
     * @return static
     */
    public function setCountry(\common\models\raiffeisenxml\request\CountryDPRaifType $country)
    {
        $this->country = $country;
        return $this;
    }


}

