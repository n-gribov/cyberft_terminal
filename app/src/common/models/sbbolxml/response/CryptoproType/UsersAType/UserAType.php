<?php

namespace common\models\sbbolxml\response\CryptoproType\UsersAType;

/**
 * Class representing UserAType
 */
class UserAType
{

    /**
     * Логин пользователя криптопрофиля
     *
     * @property string $login
     */
    private $login = null;

    /**
     * Имя пользователя криптопрофиля
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as login
     *
     * Логин пользователя криптопрофиля
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
     * Логин пользователя криптопрофиля
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
     * Gets as name
     *
     * Имя пользователя криптопрофиля
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Имя пользователя криптопрофиля
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


}

