<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType;

/**
 * Class representing UserRolesAType
 */
class UserRolesAType
{

    /**
     * @property \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\UserRolesAType\UserRoleAType[] $userRole
     */
    private $userRole = array(
        
    );

    /**
     * Adds as userRole
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\UserRolesAType\UserRoleAType $userRole
     */
    public function addToUserRole(\common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\UserRolesAType\UserRoleAType $userRole)
    {
        $this->userRole[] = $userRole;
        return $this;
    }

    /**
     * isset userRole
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetUserRole($index)
    {
        return isset($this->userRole[$index]);
    }

    /**
     * unset userRole
     *
     * @param scalar $index
     * @return void
     */
    public function unsetUserRole($index)
    {
        unset($this->userRole[$index]);
    }

    /**
     * Gets as userRole
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\UserRolesAType\UserRoleAType[]
     */
    public function getUserRole()
    {
        return $this->userRole;
    }

    /**
     * Sets a new userRole
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\UserRolesAType\UserRoleAType[] $userRole
     * @return static
     */
    public function setUserRole(array $userRole)
    {
        $this->userRole = $userRole;
        return $this;
    }


}

