<?php

namespace common\models\raiffeisenxml\response\ComLettDataType;

/**
 * Class representing OrgDataAType
 */
class OrgDataAType
{

    /**
     * @property string $extID
     */
    private $extID = null;

    /**
     * @property string $inn
     */
    private $inn = null;

    /**
     * @property string $kpp
     */
    private $kpp = null;

    /**
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as extID
     *
     * @return string
     */
    public function getExtID()
    {
        return $this->extID;
    }

    /**
     * Sets a new extID
     *
     * @param string $extID
     * @return static
     */
    public function setExtID($extID)
    {
        $this->extID = $extID;
        return $this;
    }

    /**
     * Gets as inn
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
     * @param string $inn
     * @return static
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * Gets as kpp
     *
     * @return string
     */
    public function getKpp()
    {
        return $this->kpp;
    }

    /**
     * Sets a new kpp
     *
     * @param string $kpp
     * @return static
     */
    public function setKpp($kpp)
    {
        $this->kpp = $kpp;
        return $this;
    }

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

