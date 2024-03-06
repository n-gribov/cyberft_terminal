<?php

namespace common\models\raiffeisenxml\request\DealPassCon138IType;

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
     * @property \common\models\raiffeisenxml\request\DealPassCon138IType\ResInfoAType\AddressAType $address
     */
    private $address = null;

    /**
     * 1.3 ОГРН организации-резидента
     *
     * @property string $oGRN
     */
    private $oGRN = null;

    /**
     * 1.4 Дата внесения записи в государственный реестр
     *
     * @property \DateTime $regDate
     */
    private $regDate = null;

    /**
     * КПП
     *
     * @property \common\models\raiffeisenxml\request\DealPassCon138IType\ResInfoAType\KppAType $kpp
     */
    private $kpp = null;

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
     * @return \common\models\raiffeisenxml\request\DealPassCon138IType\ResInfoAType\AddressAType
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
     * @param \common\models\raiffeisenxml\request\DealPassCon138IType\ResInfoAType\AddressAType $address
     * @return static
     */
    public function setAddress(\common\models\raiffeisenxml\request\DealPassCon138IType\ResInfoAType\AddressAType $address)
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
     * Gets as regDate
     *
     * 1.4 Дата внесения записи в государственный реестр
     *
     * @return \DateTime
     */
    public function getRegDate()
    {
        return $this->regDate;
    }

    /**
     * Sets a new regDate
     *
     * 1.4 Дата внесения записи в государственный реестр
     *
     * @param \DateTime $regDate
     * @return static
     */
    public function setRegDate(\DateTime $regDate)
    {
        $this->regDate = $regDate;
        return $this;
    }

    /**
     * Gets as kpp
     *
     * КПП
     *
     * @return \common\models\raiffeisenxml\request\DealPassCon138IType\ResInfoAType\KppAType
     */
    public function getKpp()
    {
        return $this->kpp;
    }

    /**
     * Sets a new kpp
     *
     * КПП
     *
     * @param \common\models\raiffeisenxml\request\DealPassCon138IType\ResInfoAType\KppAType $kpp
     * @return static
     */
    public function setKpp(\common\models\raiffeisenxml\request\DealPassCon138IType\ResInfoAType\KppAType $kpp)
    {
        $this->kpp = $kpp;
        return $this;
    }


}

