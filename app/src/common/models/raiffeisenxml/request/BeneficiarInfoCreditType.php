<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing BeneficiarInfoCreditType
 *
 * Реквизиты иностранного контрагента
 * XSD Type: BeneficiarInfoCredit
 */
class BeneficiarInfoCreditType
{

    /**
     * Имя иностранного контрагента
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Код страны банка нерезедента
     *
     * @property string $country
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
     * Код страны банка нерезедента
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * Код страны банка нерезедента
     *
     * @param string $country
     * @return static
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }


}

