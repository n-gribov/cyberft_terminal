<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing OfferResponseType
 *
 * Отклик на персональное предложение
 * XSD Type: OfferResponse
 */
class OfferResponseType
{

    /**
     * Индентификатор пользователя
     *
     * @property integer $userID
     */
    private $userID = null;

    /**
     * Глобальный индентификатор пользователя
     *
     * @property string $userGuid
     */
    private $userGuid = null;

    /**
     * Глобальный идентификатор предложения в SAS
     *
     * @property string $extOfferId
     */
    private $extOfferId = null;

    /**
     * Дата/время в которое клиент совершил отклик
     *
     * @property \DateTime $responseDtTm
     */
    private $responseDtTm = null;

    /**
     * Тип отклика
     *
     * @property string $responseTypeCd
     */
    private $responseTypeCd = null;

    /**
     * Gets as userID
     *
     * Индентификатор пользователя
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
     * Индентификатор пользователя
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
     * Глобальный индентификатор пользователя
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
     * Глобальный индентификатор пользователя
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
     * Gets as responseDtTm
     *
     * Дата/время в которое клиент совершил отклик
     *
     * @return \DateTime
     */
    public function getResponseDtTm()
    {
        return $this->responseDtTm;
    }

    /**
     * Sets a new responseDtTm
     *
     * Дата/время в которое клиент совершил отклик
     *
     * @param \DateTime $responseDtTm
     * @return static
     */
    public function setResponseDtTm(\DateTime $responseDtTm)
    {
        $this->responseDtTm = $responseDtTm;
        return $this;
    }

    /**
     * Gets as responseTypeCd
     *
     * Тип отклика
     *
     * @return string
     */
    public function getResponseTypeCd()
    {
        return $this->responseTypeCd;
    }

    /**
     * Sets a new responseTypeCd
     *
     * Тип отклика
     *
     * @param string $responseTypeCd
     * @return static
     */
    public function setResponseTypeCd($responseTypeCd)
    {
        $this->responseTypeCd = $responseTypeCd;
        return $this;
    }


}

