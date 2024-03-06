<?php

namespace common\models\sbbolxml\response\CryptoproType;

/**
 * Class representing UsersAType
 */
class UsersAType
{

    /**
     * Пользователь криптопрофиля
     *
     * @property \common\models\sbbolxml\response\CryptoproType\UsersAType\UserAType[] $user
     */
    private $user = array(
        
    );

    /**
     * Adds as user
     *
     * Пользователь криптопрофиля
     *
     * @return static
     * @param \common\models\sbbolxml\response\CryptoproType\UsersAType\UserAType $user
     */
    public function addToUser(\common\models\sbbolxml\response\CryptoproType\UsersAType\UserAType $user)
    {
        $this->user[] = $user;
        return $this;
    }

    /**
     * isset user
     *
     * Пользователь криптопрофиля
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetUser($index)
    {
        return isset($this->user[$index]);
    }

    /**
     * unset user
     *
     * Пользователь криптопрофиля
     *
     * @param scalar $index
     * @return void
     */
    public function unsetUser($index)
    {
        unset($this->user[$index]);
    }

    /**
     * Gets as user
     *
     * Пользователь криптопрофиля
     *
     * @return \common\models\sbbolxml\response\CryptoproType\UsersAType\UserAType[]
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets a new user
     *
     * Пользователь криптопрофиля
     *
     * @param \common\models\sbbolxml\response\CryptoproType\UsersAType\UserAType[] $user
     * @return static
     */
    public function setUser(array $user)
    {
        $this->user = $user;
        return $this;
    }


}

