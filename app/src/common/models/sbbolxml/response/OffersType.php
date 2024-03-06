<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing OffersType
 *
 * Предложения
 * XSD Type: Offers
 */
class OffersType
{

    /**
     * Новое предложение
     *
     * @property \common\models\sbbolxml\response\OffersType\NewOfferAType[] $newOffer
     */
    private $newOffer = array(
        
    );

    /**
     * Удаленные предложения
     *
     * @property string[] $deletedOffers
     */
    private $deletedOffers = null;

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Adds as newOffer
     *
     * Новое предложение
     *
     * @return static
     * @param \common\models\sbbolxml\response\OffersType\NewOfferAType $newOffer
     */
    public function addToNewOffer(\common\models\sbbolxml\response\OffersType\NewOfferAType $newOffer)
    {
        $this->newOffer[] = $newOffer;
        return $this;
    }

    /**
     * isset newOffer
     *
     * Новое предложение
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetNewOffer($index)
    {
        return isset($this->newOffer[$index]);
    }

    /**
     * unset newOffer
     *
     * Новое предложение
     *
     * @param scalar $index
     * @return void
     */
    public function unsetNewOffer($index)
    {
        unset($this->newOffer[$index]);
    }

    /**
     * Gets as newOffer
     *
     * Новое предложение
     *
     * @return \common\models\sbbolxml\response\OffersType\NewOfferAType[]
     */
    public function getNewOffer()
    {
        return $this->newOffer;
    }

    /**
     * Sets a new newOffer
     *
     * Новое предложение
     *
     * @param \common\models\sbbolxml\response\OffersType\NewOfferAType[] $newOffer
     * @return static
     */
    public function setNewOffer(array $newOffer)
    {
        $this->newOffer = $newOffer;
        return $this;
    }

    /**
     * Adds as offerId
     *
     * Удаленные предложения
     *
     * @return static
     * @param string $offerId
     */
    public function addToDeletedOffers($offerId)
    {
        $this->deletedOffers[] = $offerId;
        return $this;
    }

    /**
     * isset deletedOffers
     *
     * Удаленные предложения
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDeletedOffers($index)
    {
        return isset($this->deletedOffers[$index]);
    }

    /**
     * unset deletedOffers
     *
     * Удаленные предложения
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDeletedOffers($index)
    {
        unset($this->deletedOffers[$index]);
    }

    /**
     * Gets as deletedOffers
     *
     * Удаленные предложения
     *
     * @return string[]
     */
    public function getDeletedOffers()
    {
        return $this->deletedOffers;
    }

    /**
     * Sets a new deletedOffers
     *
     * Удаленные предложения
     *
     * @param string[] $deletedOffers
     * @return static
     */
    public function setDeletedOffers(array $deletedOffers)
    {
        $this->deletedOffers = $deletedOffers;
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

