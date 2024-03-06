<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing BankPayer52RaifType
 *
 *
 * XSD Type: BankPayer_52Raif
 */
class BankPayer52RaifType
{

    /**
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as name
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
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


}

