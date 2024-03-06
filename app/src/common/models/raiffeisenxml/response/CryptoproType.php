<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing CryptoproType
 *
 *
 * XSD Type: Cryptopro
 */
class CryptoproType
{

    /**
     * Уникальный идентификатор криптопрофиля
     *
     * @property string $id
     */
    private $id = null;

    /**
     * Передается значение:
     *  1 - Инфокрипт
     *  2 - КриптоПро
     *  3 - КриптоКом
     *  4 - PGP
     *
     * @property string $cryptoType
     */
    private $cryptoType = null;

    /**
     * Предназначения подписи
     *
     * @property \common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType[] $destinys
     */
    private $destinys = null;

    /**
     * Наименование криптопрофиля
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Содержит информацию о пользователях криптопрофиля
     *
     * @property \common\models\raiffeisenxml\response\CryptoproType\UsersAType\UserAType[] $users
     */
    private $users = null;

    /**
     * Gets as id
     *
     * Уникальный идентификатор криптопрофиля
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets a new id
     *
     * Уникальный идентификатор криптопрофиля
     *
     * @param string $id
     * @return static
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets as cryptoType
     *
     * Передается значение:
     *  1 - Инфокрипт
     *  2 - КриптоПро
     *  3 - КриптоКом
     *  4 - PGP
     *
     * @return string
     */
    public function getCryptoType()
    {
        return $this->cryptoType;
    }

    /**
     * Sets a new cryptoType
     *
     * Передается значение:
     *  1 - Инфокрипт
     *  2 - КриптоПро
     *  3 - КриптоКом
     *  4 - PGP
     *
     * @param string $cryptoType
     * @return static
     */
    public function setCryptoType($cryptoType)
    {
        $this->cryptoType = $cryptoType;
        return $this;
    }

    /**
     * Adds as destiny
     *
     * Предназначения подписи
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType $destiny
     */
    public function addToDestinys(\common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType $destiny)
    {
        $this->destinys[] = $destiny;
        return $this;
    }

    /**
     * isset destinys
     *
     * Предназначения подписи
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDestinys($index)
    {
        return isset($this->destinys[$index]);
    }

    /**
     * unset destinys
     *
     * Предназначения подписи
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDestinys($index)
    {
        unset($this->destinys[$index]);
    }

    /**
     * Gets as destinys
     *
     * Предназначения подписи
     *
     * @return \common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType[]
     */
    public function getDestinys()
    {
        return $this->destinys;
    }

    /**
     * Sets a new destinys
     *
     * Предназначения подписи
     *
     * @param \common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType[] $destinys
     * @return static
     */
    public function setDestinys(array $destinys)
    {
        $this->destinys = $destinys;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование криптопрофиля
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
     * Наименование криптопрофиля
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Adds as user
     *
     * Содержит информацию о пользователях криптопрофиля
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\CryptoproType\UsersAType\UserAType $user
     */
    public function addToUsers(\common\models\raiffeisenxml\response\CryptoproType\UsersAType\UserAType $user)
    {
        $this->users[] = $user;
        return $this;
    }

    /**
     * isset users
     *
     * Содержит информацию о пользователях криптопрофиля
     *
     * @param int|string $index
     * @return bool
     */
    public function issetUsers($index)
    {
        return isset($this->users[$index]);
    }

    /**
     * unset users
     *
     * Содержит информацию о пользователях криптопрофиля
     *
     * @param int|string $index
     * @return void
     */
    public function unsetUsers($index)
    {
        unset($this->users[$index]);
    }

    /**
     * Gets as users
     *
     * Содержит информацию о пользователях криптопрофиля
     *
     * @return \common\models\raiffeisenxml\response\CryptoproType\UsersAType\UserAType[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Sets a new users
     *
     * Содержит информацию о пользователях криптопрофиля
     *
     * @param \common\models\raiffeisenxml\response\CryptoproType\UsersAType\UserAType[] $users
     * @return static
     */
    public function setUsers(array $users)
    {
        $this->users = $users;
        return $this;
    }


}

