<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DzoOrgDataType
 *
 *
 * XSD Type: DzoOrgData
 */
class DzoOrgDataType
{

    /**
     * Наименование организации
     *
     * @property string $orgName
     */
    private $orgName = null;

    /**
     * ИНН
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * КПП
     *
     * @property string $kpp
     */
    private $kpp = null;

    /**
     * Почтовый или юридический адрес организации Клиента
     *
     * @property string $orgAddress
     */
    private $orgAddress = null;

    /**
     * Gets as orgName
     *
     * Наименование организации
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
     * Наименование организации
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
     * КПП
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
     * КПП
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
     * Gets as orgAddress
     *
     * Почтовый или юридический адрес организации Клиента
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
     * Почтовый или юридический адрес организации Клиента
     *
     * @param string $orgAddress
     * @return static
     */
    public function setOrgAddress($orgAddress)
    {
        $this->orgAddress = $orgAddress;
        return $this;
    }


}

