<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing OrgDataCCBoxType
 *
 *
 * XSD Type: OrgDataCCBox
 */
class OrgDataCCBoxType
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
     * ОКПО
     *
     * @property string $okpo
     */
    private $okpo = null;

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
     * Gets as okpo
     *
     * ОКПО
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
     * ОКПО
     *
     * @param string $okpo
     * @return static
     */
    public function setOkpo($okpo)
    {
        $this->okpo = $okpo;
        return $this;
    }


}

