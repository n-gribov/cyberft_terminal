<?php

namespace common\models\raiffeisenxml\request\DealPassCred181IRaifType\ResInfoAType;

/**
 * Class representing InnKppAType
 */
class InnKppAType
{

    /**
     * ИНН
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * КПП организации-резидента
     *
     * @property string $kpp
     */
    private $kpp = null;

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
     * Gets as kpp
     *
     * КПП организации-резидента
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
     * КПП организации-резидента
     *
     * @param string $kpp
     * @return static
     */
    public function setKpp($kpp)
    {
        $this->kpp = $kpp;
        return $this;
    }


}

