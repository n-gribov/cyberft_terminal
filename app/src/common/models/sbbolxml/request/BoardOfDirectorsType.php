<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BoardOfDirectorsType
 *
 * Совет директоров
 * XSD Type: BoardOfDirectors
 */
class BoardOfDirectorsType
{

    /**
     * Персональный состав (ФИО и должность) для совета директоров
     *
     * @property string $orgManagementMembers
     */
    private $orgManagementMembers = null;

    /**
     * Gets as orgManagementMembers
     *
     * Персональный состав (ФИО и должность) для совета директоров
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
     * Персональный состав (ФИО и должность) для совета директоров
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

