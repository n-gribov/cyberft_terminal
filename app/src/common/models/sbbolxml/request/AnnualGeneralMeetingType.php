<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AnnualGeneralMeetingType
 *
 * Общее собрание акционеров
 * XSD Type: AnnualGeneralMeeting
 */
class AnnualGeneralMeetingType
{

    /**
     * Персональный состав (ФИО и должность) для общего собрания акционеров
     *
     * @property string $orgManagementMembers
     */
    private $orgManagementMembers = null;

    /**
     * Gets as orgManagementMembers
     *
     * Персональный состав (ФИО и должность) для общего собрания акционеров
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
     * Персональный состав (ФИО и должность) для общего собрания акционеров
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

