<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType\DocDataAType;

/**
 * Class representing OrgDataAType
 */
class OrgDataAType
{

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


}

