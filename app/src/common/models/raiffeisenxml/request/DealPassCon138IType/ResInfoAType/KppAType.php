<?php

namespace common\models\raiffeisenxml\request\DealPassCon138IType\ResInfoAType;

/**
 * Class representing KppAType
 */
class KppAType
{

    /**
     * КПП организации-резидента
     *
     * @property string $kpp
     */
    private $kpp = null;

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

