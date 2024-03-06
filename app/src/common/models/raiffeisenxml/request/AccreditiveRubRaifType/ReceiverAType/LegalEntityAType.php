<?php

namespace common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType;

/**
 * Class representing LegalEntityAType
 */
class LegalEntityAType
{

    /**
     * ИНН/КИО
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Наименование юридического лица
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Адрес
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Gets as inn
     *
     * ИНН/КИО
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Sets a new inn
     *
     * ИНН/КИО
     *
     * @param string $inn
     * @return static
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование юридического лица
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
     * Наименование юридического лица
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
     * Gets as address
     *
     * Адрес
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
     * Адрес
     *
     * @param string $address
     * @return static
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }


}

