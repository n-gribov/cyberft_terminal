<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing ChangePasswordRequestType
 *
 *
 * XSD Type: ChangePasswordRequest
 */
class ChangePasswordRequestType
{

    /**
     * Соль (строка случайных данных)
     *
     * @property string $salt
     */
    private $salt = null;

    /**
     * Хэш, рассчитанный по паролю
     *
     * @property string $passwordHash
     */
    private $passwordHash = null;

    /**
     * Gets as salt
     *
     * Соль (строка случайных данных)
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Sets a new salt
     *
     * Соль (строка случайных данных)
     *
     * @param string $salt
     * @return static
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Gets as passwordHash
     *
     * Хэш, рассчитанный по паролю
     *
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * Sets a new passwordHash
     *
     * Хэш, рассчитанный по паролю
     *
     * @param string $passwordHash
     * @return static
     */
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
        return $this;
    }


}

