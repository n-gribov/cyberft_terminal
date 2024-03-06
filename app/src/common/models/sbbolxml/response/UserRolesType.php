<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing UserRolesType
 *
 * Роли по пользователям
 * XSD Type: UserRoles
 */
class UserRolesType
{

    /**
     * @property \common\models\sbbolxml\response\UserRolesType\UsersAType[] $users
     */
    private $users = array(
        
    );

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Adds as users
     *
     * @return static
     * @param \common\models\sbbolxml\response\UserRolesType\UsersAType $users
     */
    public function addToUsers(\common\models\sbbolxml\response\UserRolesType\UsersAType $users)
    {
        $this->users[] = $users;
        return $this;
    }

    /**
     * isset users
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetUsers($index)
    {
        return isset($this->users[$index]);
    }

    /**
     * unset users
     *
     * @param scalar $index
     * @return void
     */
    public function unsetUsers($index)
    {
        unset($this->users[$index]);
    }

    /**
     * Gets as users
     *
     * @return \common\models\sbbolxml\response\UserRolesType\UsersAType[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Sets a new users
     *
     * @param \common\models\sbbolxml\response\UserRolesType\UsersAType[] $users
     * @return static
     */
    public function setUsers(array $users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * Gets as sign
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

