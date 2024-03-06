<?php

namespace common\models\raiffeisenxml\request\ChanDPConRaifType;

/**
 * Class representing DealPassportsAType
 */
class DealPassportsAType
{

    /**
     * Данные пасспорта сделки
     *
     * @property \common\models\raiffeisenxml\request\ChanDPConRaifType\DealPassportsAType\DealPassAType[] $dealPass
     */
    private $dealPass = [
        
    ];

    /**
     * Adds as dealPass
     *
     * Данные пасспорта сделки
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ChanDPConRaifType\DealPassportsAType\DealPassAType $dealPass
     */
    public function addToDealPass(\common\models\raiffeisenxml\request\ChanDPConRaifType\DealPassportsAType\DealPassAType $dealPass)
    {
        $this->dealPass[] = $dealPass;
        return $this;
    }

    /**
     * isset dealPass
     *
     * Данные пасспорта сделки
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDealPass($index)
    {
        return isset($this->dealPass[$index]);
    }

    /**
     * unset dealPass
     *
     * Данные пасспорта сделки
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDealPass($index)
    {
        unset($this->dealPass[$index]);
    }

    /**
     * Gets as dealPass
     *
     * Данные пасспорта сделки
     *
     * @return \common\models\raiffeisenxml\request\ChanDPConRaifType\DealPassportsAType\DealPassAType[]
     */
    public function getDealPass()
    {
        return $this->dealPass;
    }

    /**
     * Sets a new dealPass
     *
     * Данные пасспорта сделки
     *
     * @param \common\models\raiffeisenxml\request\ChanDPConRaifType\DealPassportsAType\DealPassAType[] $dealPass
     * @return static
     */
    public function setDealPass(array $dealPass)
    {
        $this->dealPass = $dealPass;
        return $this;
    }


}

