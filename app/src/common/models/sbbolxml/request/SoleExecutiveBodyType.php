<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing SoleExecutiveBodyType
 *
 * Единоличный исполнительный орган
 * XSD Type: SoleExecutiveBody
 */
class SoleExecutiveBodyType
{

    /**
     * Персональный состав (ФИО и должность) для единоличного исполнительного органа
     *
     * @property string $orgManagementMembers
     */
    private $orgManagementMembers = null;

    /**
     * Gets as orgManagementMembers
     *
     * Персональный состав (ФИО и должность) для единоличного исполнительного органа
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
     * Персональный состав (ФИО и должность) для единоличного исполнительного органа
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

