<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CollectiveBodyType
 *
 * Коллегиальный орган
 * XSD Type: CollectiveBody
 */
class CollectiveBodyType
{

    /**
     * Персональный состав (ФИО и должность) для коллегиального органа
     *
     * @property string $orgManagementMembers
     */
    private $orgManagementMembers = null;

    /**
     * Gets as orgManagementMembers
     *
     * Персональный состав (ФИО и должность) для коллегиального органа
     *
     * @return string
     */
    public function getOrgManagementMembers()
    {
        return $this->orgManagementMembers;
    }

    /**
     * Sets a new orgManagementMembers
     *
     * Персональный состав (ФИО и должность) для коллегиального органа
     *
     * @param string $orgManagementMembers
     * @return static
     */
    public function setOrgManagementMembers($orgManagementMembers)
    {
        $this->orgManagementMembers = $orgManagementMembers;
        return $this;
    }


}

