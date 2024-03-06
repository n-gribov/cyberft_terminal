<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CallBackType
 *
 * Заявка на обратный звонок
 * XSD Type: CallBack
 */
class CallBackType
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
     * Дата создания
     *
     * @property \DateTime $createdDate
     */
    private $createdDate = null;

    /**
     * Номер телефона для обратного звонка
     *
     * @property string $phone
     */
    private $phone = null;

    /**
     * Предпочтительная дата обратного звонка
     *
     * @property \DateTime $callDate
     */
    private $callDate = null;

    /**
     * Предпочтительное время обратного звонка
     *
     * @property string $callTime
     */
    private $callTime = null;

    /**
     * Часовой пояс пользователя, например GMT+3
     *
     * @property string $userTimeZone
     */
    private $userTimeZone = null;

    /**
     * Комментарий пользователя
     *
     * @property string $userComment
     */
    private $userComment = null;

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
     * Gets as createdDate
     *
     * Дата создания
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Sets a new createdDate
     *
     * Дата создания
     *
     * @param \DateTime $createdDate
     * @return static
     */
    public function setCreatedDate(\DateTime $createdDate)
    {
        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * Gets as phone
     *
     * Номер телефона для обратного звонка
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets a new phone
     *
     * Номер телефона для обратного звонка
     *
     * @param string $phone
     * @return static
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Gets as callDate
     *
     * Предпочтительная дата обратного звонка
     *
     * @return \DateTime
     */
    public function getCallDate()
    {
        return $this->callDate;
    }

    /**
     * Sets a new callDate
     *
     * Предпочтительная дата обратного звонка
     *
     * @param \DateTime $callDate
     * @return static
     */
    public function setCallDate(\DateTime $callDate)
    {
        $this->callDate = $callDate;
        return $this;
    }

    /**
     * Gets as callTime
     *
     * Предпочтительное время обратного звонка
     *
     * @return string
     */
    public function getCallTime()
    {
        return $this->callTime;
    }

    /**
     * Sets a new callTime
     *
     * Предпочтительное время обратного звонка
     *
     * @param string $callTime
     * @return static
     */
    public function setCallTime($callTime)
    {
        $this->callTime = $callTime;
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

    /**
     * Gets as userComment
     *
     * Комментарий пользователя
     *
     * @return string
     */
    public function getUserComment()
    {
        return $this->userComment;
    }

    /**
     * Sets a new userComment
     *
     * Комментарий пользователя
     *
     * @param string $userComment
     * @return static
     */
    public function setUserComment($userComment)
    {
        $this->userComment = $userComment;
        return $this;
    }


}

