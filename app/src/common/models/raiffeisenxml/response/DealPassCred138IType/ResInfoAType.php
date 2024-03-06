<?php

namespace common\models\raiffeisenxml\response\DealPassCred138IType;

/**
 * Class representing ResInfoAType
 */
class ResInfoAType
{

    /**
     * 1.1
     *  Наименование организации-резидента (вместе с наимен-ем формы собственности - то, что
     *  выводится на ПФ)
     *
     * @property string $name
     */
    private $name = null;

    /**
     * 1.2
     *
     * @property \common\models\raiffeisenxml\response\DealPassCred138IType\ResInfoAType\AddressAType $address
     */
    private $address = null;

    /**
     * 1.3 ОГРН организации-резидента
     *
     * @property string $oGRN
     */
    private $oGRN = null;

    /**
     * 1.5
     *  ИНН или КПП
     *
     * @property \common\models\raiffeisenxml\response\DealPassCred138IType\ResInfoAType\InnKppAType $innKpp
     */
    private $innKpp = null;

    /**
     * Gets as name
     *
     * 1.1
     *  Наименование организации-резидента (вместе с наимен-ем формы собственности - то, что
     *  выводится на ПФ)
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
     * 1.1
     *  Наименование организации-резидента (вместе с наимен-ем формы собственности - то, что
     *  выводится на ПФ)
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
     * 1.2
     *
     * @return \common\models\raiffeisenxml\response\DealPassCred138IType\ResInfoAType\AddressAType
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets a new address
     *
     * 1.2
     *
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\ResInfoAType\AddressAType $address
     * @return static
     */
    public function setAddress(\common\models\raiffeisenxml\response\DealPassCred138IType\ResInfoAType\AddressAType $address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Gets as oGRN
     *
     * 1.3 ОГРН организации-резидента
     *
     * @return string
     */
    public function getOGRN()
    {
        return $this->oGRN;
    }

    /**
     * Sets a new oGRN
     *
     * 1.3 ОГРН организации-резидента
     *
     * @param string $oGRN
     * @return static
     */
    public function setOGRN($oGRN)
    {
        $this->oGRN = $oGRN;
        return $this;
    }

    /**
     * Gets as innKpp
     *
     * 1.5
     *  ИНН или КПП
     *
     * @return \common\models\raiffeisenxml\response\DealPassCred138IType\ResInfoAType\InnKppAType
     */
    public function getInnKpp()
    {
        return $this->innKpp;
    }

    /**
     * Sets a new innKpp
     *
     * 1.5
     *  ИНН или КПП
     *
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\ResInfoAType\InnKppAType $innKpp
     * @return static
     */
    public function setInnKpp(\common\models\raiffeisenxml\response\DealPassCred138IType\ResInfoAType\InnKppAType $innKpp)
    {
        $this->innKpp = $innKpp;
        return $this;
    }


}

