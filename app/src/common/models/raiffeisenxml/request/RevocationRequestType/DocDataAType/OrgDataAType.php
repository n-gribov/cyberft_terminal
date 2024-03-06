<?php

namespace common\models\raiffeisenxml\request\RevocationRequestType\DocDataAType;

/**
 * Class representing OrgDataAType
 */
class OrgDataAType
{

    /**
     * ИНН клиента (10,12)
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Сокращенное наименование организации клиента
     *
     * @property string $orgName
     */
    private $orgName = null;

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
     * Сокращенное наименование организации клиента
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
     * Сокращенное наименование организации клиента
     *
     * @param string $orgName
     * @return static
     */
    public function setOrgName($orgName)
    {
        $this->orgName = $orgName;
        return $this;
    }


}

