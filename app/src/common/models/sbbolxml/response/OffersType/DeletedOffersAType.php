<?php

namespace common\models\sbbolxml\response\OffersType;

/**
 * Class representing DeletedOffersAType
 */
class DeletedOffersAType
{

    /**
     * Идентификатор предложения в СББОЛ (ID)
     *
     * @property string[] $offerId
     */
    private $offerId = array(
        
    );

    /**
     * Adds as offerId
     *
     * Идентификатор предложения в СББОЛ (ID)
     *
     * @return static
     * @param string $offerId
     */
    public function addToOfferId($offerId)
    {
        $this->offerId[] = $offerId;
        return $this;
    }

    /**
     * isset offerId
     *
     * Идентификатор предложения в СББОЛ (ID)
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOfferId($index)
    {
        return isset($this->offerId[$index]);
    }

    /**
     * unset offerId
     *
     * Идентификатор предложения в СББОЛ (ID)
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOfferId($index)
    {
        unset($this->offerId[$index]);
    }

    /**
     * Gets as offerId
     *
     * Идентификатор предложения в СББОЛ (ID)
     *
     * @return string[]
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
     * @param string[] $offerId
     * @return static
     */
    public function setOfferId(array $offerId)
    {
        $this->offerId = $offerId;
        return $this;
    }


}

