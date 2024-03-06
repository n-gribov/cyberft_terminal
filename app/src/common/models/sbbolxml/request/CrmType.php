<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CrmType
 *
 * Заявка в CRM
 * XSD Type: Crm
 */
class CrmType
{

    /**
     * Глобальный идентификатор предложения в SAS
     *
     * @property string $extOfferId
     */
    private $extOfferId = null;

    /**
     * Идентификатор пользователя в СББОЛ
     *
     * @property integer $userID
     */
    private $userID = null;

    /**
     * Глобальный идентификатор пользователя в СББОЛ
     *
     * @property string $userGuid
     */
    private $userGuid = null;

    /**
     * Часовой пояс пользователя, например GMT+3
     *
     * @property string $userTimeZone
     */
    private $userTimeZone = null;

    /**
     * Gets as extOfferId
     *
     * Глобальный идентификатор предложения в SAS
     *
     * @return string
     */
    public function getExtOfferId()
    {
        return $this->extOfferId;
    }

    /**
     * Sets a new extOfferId
     *
     * Глобальный идентификатор предложения в SAS
     *
     * @param string $extOfferId
     * @return static
     */
    public function setExtOfferId($extOfferId)
    {
        $this->extOfferId = $extOfferId;
        return $this;
    }

    /**
     * Gets as userID
     *
     * Идентификатор пользователя в СББОЛ
     *
     * @return integer
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * Sets a new userID
     *
     * Идентификатор пользователя в СББОЛ
     *
     * @param integer $userID
     * @return static
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
        return $this;
    }

    /**
     * Gets as userGuid
     *
     * Глобальный идентификатор пользователя в СББОЛ
     *
     * @return string
     */
    public function getUserGuid()
    {
        return $this->userGuid;
    }

    /**
     * Sets a new userGuid
     *
     * Глобальный идентификатор пользователя в СББОЛ
     *
     * @param string $userGuid
     * @return static
     */
    public function setUserGuid($userGuid)
    {
        $this->userGuid = $userGuid;
        return $this;
    }

    /**
     * Gets as userTimeZone
     *
     * Часовой пояс пользователя, например GMT+3
     *
     * @return string
     */
    public function getUserTimeZone()
    {
        return $this->userTimeZone;
    }

    /**
     * Sets a new userTimeZone
     *
     * Часовой пояс пользователя, например GMT+3
     *
     * @param string $userTimeZone
     * @return static
     */
    public function setUserTimeZone($userTimeZone)
    {
        $this->userTimeZone = $userTimeZone;
        return $this;
    }


}

