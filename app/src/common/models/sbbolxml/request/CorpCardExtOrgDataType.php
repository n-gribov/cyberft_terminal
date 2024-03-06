<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CorpCardExtOrgDataType
 *
 * Реквизиты организации клиента
 * XSD Type: CorpCardExtOrgData
 */
class CorpCardExtOrgDataType
{

    /**
     * Наименование организации клиента
     *
     * @property string $orgName
     */
    private $orgName = null;

    /**
     * Gets as orgName
     *
     * Наименование организации клиента
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
     * Наименование организации клиента
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

