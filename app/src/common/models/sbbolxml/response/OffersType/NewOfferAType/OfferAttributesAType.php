<?php

namespace common\models\sbbolxml\response\OffersType\NewOfferAType;

/**
 * Class representing OfferAttributesAType
 */
class OfferAttributesAType
{

    /**
     * @property \common\models\sbbolxml\response\OffersType\NewOfferAType\OfferAttributesAType\OfferAttributeAType[] $offerAttribute
     */
    private $offerAttribute = array(
        
    );

    /**
     * Adds as offerAttribute
     *
     * @return static
     * @param \common\models\sbbolxml\response\OffersType\NewOfferAType\OfferAttributesAType\OfferAttributeAType $offerAttribute
     */
    public function addToOfferAttribute(\common\models\sbbolxml\response\OffersType\NewOfferAType\OfferAttributesAType\OfferAttributeAType $offerAttribute)
    {
        $this->offerAttribute[] = $offerAttribute;
        return $this;
    }

    /**
     * isset offerAttribute
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOfferAttribute($index)
    {
        return isset($this->offerAttribute[$index]);
    }

    /**
     * unset offerAttribute
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOfferAttribute($index)
    {
        unset($this->offerAttribute[$index]);
    }

    /**
     * Gets as offerAttribute
     *
     * @return \common\models\sbbolxml\response\OffersType\NewOfferAType\OfferAttributesAType\OfferAttributeAType[]
     */
    public function getOfferAttribute()
    {
        return $this->offerAttribute;
    }

    /**
     * Sets a new offerAttribute
     *
     * @param \common\models\sbbolxml\response\OffersType\NewOfferAType\OfferAttributesAType\OfferAttributeAType[] $offerAttribute
     * @return static
     */
    public function setOfferAttribute(array $offerAttribute)
    {
        $this->offerAttribute = $offerAttribute;
        return $this;
    }


}

