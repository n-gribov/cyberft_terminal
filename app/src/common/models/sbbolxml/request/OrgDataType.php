<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing OrgDataType
 *
 *
 * XSD Type: OrgData
 */
class OrgDataType
{

    /**
     * КПП клиента
     *
     * @property string $kpp
     */
    private $kpp = null;

    /**
     * ОКАТО клиента
     *
     * @property string $okato
     */
    private $okato = null;

    /**
     * ИНН клиента
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Наименование организации клиента (сокращенное наименование - как в платежных руб.
     *  документах)
     *
     * @property string $orgName
     */
    private $orgName = null;

    /**
     * ОКПО клиента
     *
     * @property string $okpo
     */
    private $okpo = null;

    /**
     * ОГРН клиента
     *
     * @property string $orgOGRN
     */
    private $orgOGRN = null;

    /**
     * Cокращенное наименование организации в родительном падеже
     *
     * @property string $clientNameIntheGenitive
     */
    private $clientNameIntheGenitive = null;

    /**
     * Полное наименование
     *
     * @property string $fullName
     */
    private $fullName = null;

    /**
     * Наименование на иностранном языке (при наличии)
     *
     * @property string $internationalName
     */
    private $internationalName = null;

    /**
     * Наименование на иностранном языке (при наличии)
     *
     * @property string $opf
     */
    private $opf = null;

    /**
     * Дата регистрации
     *
     * @property \DateTime $datOGRN
     */
    private $datOGRN = null;

    /**
     * Юридический адрес организации Клиента
     *
     * @property string $orgAddress
     */
    private $orgAddress = null;

    /**
     * Резидентность организации по справочнику организаций
     *
     * @property string $resident
     */
    private $resident = null;

    /**
     * Gets as kpp
     *
     * КПП клиента
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
     * КПП клиента
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
     * Gets as okato
     *
     * ОКАТО клиента
     *
     * @return string
     */
    public function getOkato()
    {
        return $this->okato;
    }

    /**
     * Sets a new okato
     *
     * ОКАТО клиента
     *
     * @param string $okato
     * @return static
     */
    public function setOkato($okato)
    {
        $this->okato = $okato;
        return $this;
    }

    /**
     * Gets as inn
     *
     * ИНН клиента
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
     * ИНН клиента
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
     * Gets as orgName
     *
     * Наименование организации клиента (сокращенное наименование - как в платежных руб.
     *  документах)
     *
     * @return string
     */
    public function getOrgName()
    {
        return $this->orgName;
    }

    /**
     * Sets a new orgName
     *
     * Наименование организации клиента (сокращенное наименование - как в платежных руб.
     *  документах)
     *
     * @param string $orgName
     * @return static
     */
    public function setOrgName($orgName)
    {
        $this->orgName = $orgName;
        return $this;
    }

    /**
     * Gets as okpo
     *
     * ОКПО клиента
     *
     * @return string
     */
    public function getOkpo()
    {
        return $this->okpo;
    }

    /**
     * Sets a new okpo
     *
     * ОКПО клиента
     *
     * @param string $okpo
     * @return static
     */
    public function setOkpo($okpo)
    {
        $this->okpo = $okpo;
        return $this;
    }

    /**
     * Gets as orgOGRN
     *
     * ОГРН клиента
     *
     * @return string
     */
    public function getOrgOGRN()
    {
        return $this->orgOGRN;
    }

    /**
     * Sets a new orgOGRN
     *
     * ОГРН клиента
     *
     * @param string $orgOGRN
     * @return static
     */
    public function setOrgOGRN($orgOGRN)
    {
        $this->orgOGRN = $orgOGRN;
        return $this;
    }

    /**
     * Gets as clientNameIntheGenitive
     *
     * Cокращенное наименование организации в родительном падеже
     *
     * @return string
     */
    public function getClientNameIntheGenitive()
    {
        return $this->clientNameIntheGenitive;
    }

    /**
     * Sets a new clientNameIntheGenitive
     *
     * Cокращенное наименование организации в родительном падеже
     *
     * @param string $clientNameIntheGenitive
     * @return static
     */
    public function setClientNameIntheGenitive($clientNameIntheGenitive)
    {
        $this->clientNameIntheGenitive = $clientNameIntheGenitive;
        return $this;
    }

    /**
     * Gets as fullName
     *
     * Полное наименование
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Sets a new fullName
     *
     * Полное наименование
     *
     * @param string $fullName
     * @return static
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * Gets as internationalName
     *
     * Наименование на иностранном языке (при наличии)
     *
     * @return string
     */
    public function getInternationalName()
    {
        return $this->internationalName;
    }

    /**
     * Sets a new internationalName
     *
     * Наименование на иностранном языке (при наличии)
     *
     * @param string $internationalName
     * @return static
     */
    public function setInternationalName($internationalName)
    {
        $this->internationalName = $internationalName;
        return $this;
    }

    /**
     * Gets as opf
     *
     * Наименование на иностранном языке (при наличии)
     *
     * @return string
     */
    public function getOpf()
    {
        return $this->opf;
    }

    /**
     * Sets a new opf
     *
     * Наименование на иностранном языке (при наличии)
     *
     * @param string $opf
     * @return static
     */
    public function setOpf($opf)
    {
        $this->opf = $opf;
        return $this;
    }

    /**
     * Gets as datOGRN
     *
     * Дата регистрации
     *
     * @return \DateTime
     */
    public function getDatOGRN()
    {
        return $this->datOGRN;
    }

    /**
     * Sets a new datOGRN
     *
     * Дата регистрации
     *
     * @param \DateTime $datOGRN
     * @return static
     */
    public function setDatOGRN(\DateTime $datOGRN)
    {
        $this->datOGRN = $datOGRN;
        return $this;
    }

    /**
     * Gets as orgAddress
     *
     * Юридический адрес организации Клиента
     *
     * @return string
     */
    public function getOrgAddress()
    {
        return $this->orgAddress;
    }

    /**
     * Sets a new orgAddress
     *
     * Юридический адрес организации Клиента
     *
     * @param string $orgAddress
     * @return static
     */
    public function setOrgAddress($orgAddress)
    {
        $this->orgAddress = $orgAddress;
        return $this;
    }

    /**
     * Gets as resident
     *
     * Резидентность организации по справочнику организаций
     *
     * @return string
     */
    public function getResident()
    {
        return $this->resident;
    }

    /**
     * Sets a new resident
     *
     * Резидентность организации по справочнику организаций
     *
     * @param string $resident
     * @return static
     */
    public function setResident($resident)
    {
        $this->resident = $resident;
        return $this;
    }


}

