<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType;

/**
 * Class representing PrincipalAType
 */
class PrincipalAType
{

    /**
     * ОГРН
     *
     * @property string $ogrn
     */
    private $ogrn = null;

    /**
     * Адрес
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Счет
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\PrincipalAType\AccAType $acc
     */
    private $acc = null;

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

    /**
     * Gets as acc
     *
     * Счет
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\PrincipalAType\AccAType
     */
    public function getAcc()
    {
        return $this->acc;
    }

    /**
     * Sets a new acc
     *
     * Счет
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\PrincipalAType\AccAType $acc
     * @return static
     */
    public function setAcc(\common\models\raiffeisenxml\request\GuaranteeRaifType\PrincipalAType\AccAType $acc)
    {
        $this->acc = $acc;
        return $this;
    }


}

