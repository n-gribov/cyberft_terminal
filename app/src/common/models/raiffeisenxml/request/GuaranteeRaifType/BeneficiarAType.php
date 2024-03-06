<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType;

/**
 * Class representing BeneficiarAType
 */
class BeneficiarAType
{

    /**
     * ОГРН
     *
     * @property string $ogrn
     */
    private $ogrn = null;

    /**
     * ИНН
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * ОКПО
     *
     * @property string $okpo
     */
    private $okpo = null;

    /**
     * Тип. Возможные значения: "Нерезидент", "Резидент РФ".
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Наименование бенефициара
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
     * Gets as ogrn
     *
     * ОГРН
     *
     * @return string
     */
    public function getOgrn()
    {
        return $this->ogrn;
    }

    /**
     * Sets a new ogrn
     *
     * ОГРН
     *
     * @param string $ogrn
     * @return static
     */
    public function setOgrn($ogrn)
    {
        $this->ogrn = $ogrn;
        return $this;
    }

    /**
     * Gets as inn
     *
     * ИНН
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
     * ИНН
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
     * Gets as okpo
     *
     * ОКПО
     *
     * @return string
     */
    public function getOkpo()
    {
        return $this->okpo;
    }

    /**
     * Sets a new okpo
     *
     * ОКПО
     *
     * @param string $okpo
     * @return static
     */
    public function setOkpo($okpo)
    {
        $this->okpo = $okpo;
        return $this;
    }

    /**
     * Gets as type
     *
     * Тип. Возможные значения: "Нерезидент", "Резидент РФ".
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Тип. Возможные значения: "Нерезидент", "Резидент РФ".
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование бенефициара
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
     * Наименование бенефициара
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

