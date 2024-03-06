<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ResInfoType
 *
 * Сведения о резиденте
 * XSD Type: ResInfo
 */
class ResInfoType
{

    /**
     * Наименование организации-резидента
     *  (вместе с наименованием формы собственности - то, что выводится на ПФ)
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Адрес организации-резидента
     *
     * @property \common\models\sbbolxml\request\FullAddressType $address
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
     * ИНН/КПП (Одно или другое, т.о. ИНН необязателен для заполнения)
     *
     * @property \common\models\sbbolxml\request\InnKppType $innKpp
     */
    private $innKpp = null;

    /**
     * Gets as name
     *
     * Наименование организации-резидента
     *  (вместе с наименованием формы собственности - то, что выводится на ПФ)
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
     * Наименование организации-резидента
     *  (вместе с наименованием формы собственности - то, что выводится на ПФ)
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
     * Адрес организации-резидента
     *
     * @return \common\models\sbbolxml\request\FullAddressType
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets a new address
     *
     * Адрес организации-резидента
     *
     * @param \common\models\sbbolxml\request\FullAddressType $address
     * @return static
     */
    public function setAddress(\common\models\sbbolxml\request\FullAddressType $address)
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
     * ИНН/КПП (Одно или другое, т.о. ИНН необязателен для заполнения)
     *
     * @return \common\models\sbbolxml\request\InnKppType
     */
    public function getInnKpp()
    {
        return $this->innKpp;
    }

    /**
     * Sets a new innKpp
     *
     * ИНН/КПП (Одно или другое, т.о. ИНН необязателен для заполнения)
     *
     * @param \common\models\sbbolxml\request\InnKppType $innKpp
     * @return static
     */
    public function setInnKpp(\common\models\sbbolxml\request\InnKppType $innKpp)
    {
        $this->innKpp = $innKpp;
        return $this;
    }


}

