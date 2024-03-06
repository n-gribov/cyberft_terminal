<?php

namespace common\models\sbbolxml\response\OffersType\NewOfferAType\OfferAttributesAType;

/**
 * Class representing OfferAttributeAType
 */
class OfferAttributeAType
{

    /**
     * Наименование атрибута
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Значение атрибута
     *
     * @property string $value
     */
    private $value = null;

    /**
     * Gets as name
     *
     * Наименование атрибута
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
     * Наименование атрибута
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
     * Gets as value
     *
     * Значение атрибута
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets a new value
     *
     * Значение атрибута
     *
     * @param string $value
     * @return static
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


}

