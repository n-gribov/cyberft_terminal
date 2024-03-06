<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing OrgDataICSRestructType
 *
 *
 * XSD Type: OrgDataICSRestruct
 */
class OrgDataICSRestructType
{

    /**
     * ИНН клиента (10,12)
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


}

