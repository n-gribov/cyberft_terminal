<?php

namespace common\models\raiffeisenxml\request\ChanDPConRaifType;

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
     * @property \common\models\raiffeisenxml\request\ChanDPConRaifType\ResInfoAType\AddressAType $address
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
     * @property \DateTime $dataReg
     */
    private $dataReg = null;

    /**
     * ИНН,КПП
     *
     * @property \common\models\raiffeisenxml\request\ChanDPConRaifType\ResInfoAType\InnKppAType $innKpp
     */
    private $innKpp = null;

    /**
     * Клиентский номер
     *
     * @property string $cnum
     */
    private $cnum = null;

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
     * @return \common\models\raiffeisenxml\request\ChanDPConRaifType\ResInfoAType\AddressAType
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
     * @param \common\models\raiffeisenxml\request\ChanDPConRaifType\ResInfoAType\AddressAType $address
     * @return static
     */
    public function setAddress(\common\models\raiffeisenxml\request\ChanDPConRaifType\ResInfoAType\AddressAType $address)
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
     * Gets as dataReg
     *
     * 1.4 Дата внесения записи в государственный реестр
     *
     * @return \DateTime
     */
    public function getDataReg()
    {
        return $this->dataReg;
    }

    /**
     * Sets a new dataReg
     *
     * 1.4 Дата внесения записи в государственный реестр
     *
     * @param \DateTime $dataReg
     * @return static
     */
    public function setDataReg(\DateTime $dataReg)
    {
        $this->dataReg = $dataReg;
        return $this;
    }

    /**
     * Gets as innKpp
     *
     * ИНН,КПП
     *
     * @return \common\models\raiffeisenxml\request\ChanDPConRaifType\ResInfoAType\InnKppAType
     */
    public function getInnKpp()
    {
        return $this->innKpp;
    }

    /**
     * Sets a new innKpp
     *
     * ИНН,КПП
     *
     * @param \common\models\raiffeisenxml\request\ChanDPConRaifType\ResInfoAType\InnKppAType $innKpp
     * @return static
     */
    public function setInnKpp(\common\models\raiffeisenxml\request\ChanDPConRaifType\ResInfoAType\InnKppAType $innKpp)
    {
        $this->innKpp = $innKpp;
        return $this;
    }

    /**
     * Gets as cnum
     *
     * Клиентский номер
     *
     * @return string
     */
    public function getCnum()
    {
        return $this->cnum;
    }

    /**
     * Sets a new cnum
     *
     * Клиентский номер
     *
     * @param string $cnum
     * @return static
     */
    public function setCnum($cnum)
    {
        $this->cnum = $cnum;
        return $this;
    }


}

