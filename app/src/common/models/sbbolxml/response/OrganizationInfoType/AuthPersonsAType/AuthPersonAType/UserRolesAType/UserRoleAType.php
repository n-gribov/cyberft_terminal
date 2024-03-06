<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\UserRolesAType;

/**
 * Class representing UserRoleAType
 */
class UserRoleAType
{

    /**
     * @property string $userRoleName
     */
    private $userRoleName = null;

    /**
     * Gets as userRoleName
     *
     * @return string
     */
    public function getUserRoleName()
    {
        return $this->userRoleName;
    }

    /**
     * Sets a new userRoleName
     *
     * @param string $userRoleName
     * @return static
     */
    public function setUserRoleName($userRoleName)
    {
        $this->userRoleName = $userRoleName;
        return $this;
    }


}

