<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing PersonalInfoType
 *
 *
 * XSD Type: PersonalInfo
 */
class PersonalInfoType
{

    /**
     * @property string $hash
     */
    private $hash = null;

    /**
     * Gets as hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Sets a new hash
     *
     * @param string $hash
     * @return static
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }


}

