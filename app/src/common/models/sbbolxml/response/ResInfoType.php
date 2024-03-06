<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ResInfoType
 *
 * Сведения о резиденте
 * XSD Type: ResInfo
 */
class ResInfoType
{

    /**
     * Наименование организации-резидента (вместе с наимен-ем формы собственности - то, что выводится на ПФ)
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Адрес
     *
     * @property \common\models\sbbolxml\response\AddressType $address
     */
    private $address = null;

    /**
     * ОГРН организации-резидента
     *
     * @property string $oGRN
     */
    private $oGRN = null;

    /**
     * Дата внесения в государственный реестр
     *
     * @property \DateTime $dateOGRN
     */
    private $dateOGRN = null;

    /**
     * Передача ИНН/КПП
     *
     * @property \common\models\sbbolxml\response\InnKppType $innKpp
     */
    private $innKpp = null;

    /**
     * Gets as name
     *
     * Наименование организации-резидента (вместе с наимен-ем формы собственности - то, что выводится на ПФ)
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
     * Наименование организации-резидента (вместе с наимен-ем формы собственности - то, что выводится на ПФ)
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
     * Адрес
     *
     * @return \common\models\sbbolxml\response\AddressType
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
     * @param \common\models\sbbolxml\response\AddressType $address
     * @return static
     */
    public function setAddress(\common\models\sbbolxml\response\AddressType $address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Gets as oGRN
     *
     * ОГРН организации-резидента
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
     * ОГРН организации-резидента
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
     * Gets as dateOGRN
     *
     * Дата внесения в государственный реестр
     *
     * @return \DateTime
     */
    public function getDateOGRN()
    {
        return $this->dateOGRN;
    }

    /**
     * Sets a new dateOGRN
     *
     * Дата внесения в государственный реестр
     *
     * @param \DateTime $dateOGRN
     * @return static
     */
    public function setDateOGRN(\DateTime $dateOGRN)
    {
        $this->dateOGRN = $dateOGRN;
        return $this;
    }

    /**
     * Gets as innKpp
     *
     * Передача ИНН/КПП
     *
     * @return \common\models\sbbolxml\response\InnKppType
     */
    public function getInnKpp()
    {
        return $this->innKpp;
    }

    /**
     * Sets a new innKpp
     *
     * Передача ИНН/КПП
     *
     * @param \common\models\sbbolxml\response\InnKppType $innKpp
     * @return static
     */
    public function setInnKpp(\common\models\sbbolxml\response\InnKppType $innKpp)
    {
        $this->innKpp = $innKpp;
        return $this;
    }


}

