<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing OrgDataCCType
 *
 *
 * XSD Type: OrgDataCC
 */
class OrgDataCCType
{

    /**
     * ИНН клиента (10,12)
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Наименование организации клиента, для ВК
     *
     * @property string $orgName
     */
    private $orgName = null;

    /**
     * Клиентский номер
     *
     * @property string $cnum
     */
    private $cnum = null;

    /**
     * Gets as inn
     *
     * ИНН клиента (10,12)
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
     * ИНН клиента (10,12)
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
     * Наименование организации клиента, для ВК
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
     * Наименование организации клиента, для ВК
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

