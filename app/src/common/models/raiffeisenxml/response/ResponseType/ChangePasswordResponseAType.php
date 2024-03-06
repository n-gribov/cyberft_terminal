<?php

namespace common\models\raiffeisenxml\response\ResponseType;

/**
 * Class representing ChangePasswordResponseAType
 */
class ChangePasswordResponseAType
{

    /**
     * Результат, изменен пароль или нет:
     *  1- изменен;
     *  0-не изменен
     *
     * @property string $login
     */
    private $login = null;

    /**
     * Gets as login
     *
     * Результат, изменен пароль или нет:
     *  1- изменен;
     *  0-не изменен
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
     * Результат, изменен пароль или нет:
     *  1- изменен;
     *  0-не изменен
     *
     * @param string $login
     * @return static
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }


}

