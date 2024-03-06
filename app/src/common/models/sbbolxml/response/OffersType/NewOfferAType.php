<?php

namespace common\models\sbbolxml\response\OffersType;

/**
 * Class representing NewOfferAType
 */
class NewOfferAType
{

    /**
     * Идентификатор пользователя в СББОЛ (ID)
     *
     * @property integer $userId
     */
    private $userId = null;

    /**
     * Глобальный идентификатор пользователя в СББОЛ (ID)
     *
     * @property string $userGuid
     */
    private $userGuid = null;

    /**
     * Идентификатор предложения в СББОЛ (ID)
     *
     * @property integer $offerId
     */
    private $offerId = null;

    /**
     * Глобальный идентификатор предложения в SAS (Ext_ID)
     *
     * @property string $extOfferId
     */
    private $extOfferId = null;

    /**
     * Тип предложения:
     *  PERSONAL_OFFER
     *  PERSONAL_NEWS
     *  MR
     *
     * @property string $offerType
     */
    private $offerType = null;

    /**
     * Приоритет предложения
     *
     * @property integer $priority
     */
    private $priority = null;

    /**
     * Дата начала действия предложения (для отображения клиенту)
     *
     * @property \DateTime $startDate
     */
    private $startDate = null;

    /**
     * Дата окончания действия предложения (для отображения клиенту)
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Дата загрузки предложения (Техническая)
     *
     * @property \DateTime $loadDate
     */
    private $loadDate = null;

    /**
     * Свойства предложения
     *
     * @property \common\models\sbbolxml\response\OffersType\NewOfferAType\OfferAttributesAType\OfferAttributeAType[] $offerAttributes
     */
    private $offerAttributes = null;

    /**
     * Gets as userId
     *
     * Идентификатор пользователя в СББОЛ (ID)
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets a new userId
     *
     * Идентификатор пользователя в СББОЛ (ID)
     *
     * @param integer $userId
     * @return static
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Gets as userGuid
     *
     * Глобальный идентификатор пользователя в СББОЛ (ID)
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
     * Глобальный идентификатор пользователя в СББОЛ (ID)
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
     * Gets as offerId
     *
     * Идентификатор предложения в СББОЛ (ID)
     *
     * @return integer
     */
    public function getOfferId()
    {
        return $this->offerId;
    }

    /**
     * Sets a new offerId
     *
     * Идентификатор предложения в СББОЛ (ID)
     *
     * @param integer $offerId
     * @return static
     */
    public function setOfferId($offerId)
    {
        $this->offerId = $offerId;
        return $this;
    }

    /**
     * Gets as extOfferId
     *
     * Глобальный идентификатор предложения в SAS (Ext_ID)
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
     * Глобальный идентификатор предложения в SAS (Ext_ID)
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
     * Gets as offerType
     *
     * Тип предложения:
     *  PERSONAL_OFFER
     *  PERSONAL_NEWS
     *  MR
     *
     * @return string
     */
    public function getOfferType()
    {
        return $this->offerType;
    }

    /**
     * Sets a new offerType
     *
     * Тип предложения:
     *  PERSONAL_OFFER
     *  PERSONAL_NEWS
     *  MR
     *
     * @param string $offerType
     * @return static
     */
    public function setOfferType($offerType)
    {
        $this->offerType = $offerType;
        return $this;
    }

    /**
     * Gets as priority
     *
     * Приоритет предложения
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Sets a new priority
     *
     * Приоритет предложения
     *
     * @param integer $priority
     * @return static
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Gets as startDate
     *
     * Дата начала действия предложения (для отображения клиенту)
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Sets a new startDate
     *
     * Дата начала действия предложения (для отображения клиенту)
     *
     * @param \DateTime $startDate
     * @return static
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата окончания действия предложения (для отображения клиенту)
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets a new endDate
     *
     * Дата окончания действия предложения (для отображения клиенту)
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Gets as loadDate
     *
     * Дата загрузки предложения (Техническая)
     *
     * @return \DateTime
     */
    public function getLoadDate()
    {
        return $this->loadDate;
    }

    /**
     * Sets a new loadDate
     *
     * Дата загрузки предложения (Техническая)
     *
     * @param \DateTime $loadDate
     * @return static
     */
    public function setLoadDate(\DateTime $loadDate)
    {
        $this->loadDate = $loadDate;
        return $this;
    }

    /**
     * Adds as offerAttribute
     *
     * Свойства предложения
     *
     * @return static
     * @param \common\models\sbbolxml\response\OffersType\NewOfferAType\OfferAttributesAType\OfferAttributeAType $offerAttribute
     */
    public function addToOfferAttributes(\common\models\sbbolxml\response\OffersType\NewOfferAType\OfferAttributesAType\OfferAttributeAType $offerAttribute)
    {
        $this->offerAttributes[] = $offerAttribute;
        return $this;
    }

    /**
     * isset offerAttributes
     *
     * Свойства предложения
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOfferAttributes($index)
    {
        return isset($this->offerAttributes[$index]);
    }

    /**
     * unset offerAttributes
     *
     * Свойства предложения
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOfferAttributes($index)
    {
        unset($this->offerAttributes[$index]);
    }

    /**
     * Gets as offerAttributes
     *
     * Свойства предложения
     *
     * @return \common\models\sbbolxml\response\OffersType\NewOfferAType\OfferAttributesAType\OfferAttributeAType[]
     */
    public function getOfferAttributes()
    {
        return $this->offerAttributes;
    }

    /**
     * Sets a new offerAttributes
     *
     * Свойства предложения
     *
     * @param \common\models\sbbolxml\response\OffersType\NewOfferAType\OfferAttributesAType\OfferAttributeAType[] $offerAttributes
     * @return static
     */
    public function setOfferAttributes(array $offerAttributes)
    {
        $this->offerAttributes = $offerAttributes;
        return $this;
    }


}

