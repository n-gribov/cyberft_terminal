<?php

namespace common\models\sbbolxml\response\UserRolesType\UsersAType;

/**
 * Class representing UserRoleAType
 */
class UserRoleAType
{

    /**
     * @property string[] $userRoleName
     */
    private $userRoleName = array(
        
    );

    /**
     * Adds as userRoleName
     *
     * @return static
     * @param string $userRoleName
     */
    public function addToUserRoleName($userRoleName)
    {
        $this->userRoleName[] = $userRoleName;
        return $this;
    }

    /**
     * isset userRoleName
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetUserRoleName($index)
    {
        return isset($this->userRoleName[$index]);
    }

    /**
     * unset userRoleName
     *
     * @param scalar $index
     * @return void
     */
    public function unsetUserRoleName($index)
    {
        unset($this->userRoleName[$index]);
    }

    /**
     * Gets as userRoleName
     *
     * @return string[]
     */
    public function getUserRoleName()
    {
        return $this->userRoleName;
    }

    /**
     * Sets a new userRoleName
     *
     * @param string[] $userRoleName
     * @return static
     */
    public function setUserRoleName(array $userRoleName)
    {
        $this->userRoleName = $userRoleName;
        return $this;
    }


}

