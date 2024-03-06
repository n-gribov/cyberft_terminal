<?php

namespace common\models\sbbolxml\response\UserRolesType;

/**
 * Class representing UsersAType
 */
class UsersAType
{

    /**
     * @property string $login
     */
    private $login = null;

    /**
     * @property string[] $userRole
     */
    private $userRole = null;

    /**
     * Gets as login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Sets a new login
     *
     * @param string $login
     * @return static
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * Adds as userRoleName
     *
     * @return static
     * @param string $userRoleName
     */
    public function addToUserRole($userRoleName)
    {
        $this->userRole[] = $userRoleName;
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
     * @return string[]
     */
    public function getUserRole()
    {
        return $this->userRole;
    }

    /**
     * Sets a new userRole
     *
     * @param string[] $userRole
     * @return static
     */
    public function setUserRole(array $userRole)
    {
        $this->userRole = $userRole;
        return $this;
    }


}

