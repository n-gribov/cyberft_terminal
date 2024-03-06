<?php

namespace common\models\sbbolxml\response\CryptoproType;

/**
 * Class representing DestinysAType
 */
class DestinysAType
{

    /**
     * Предназначение подписи
     *
     * @property \common\models\sbbolxml\response\CryptoproType\DestinysAType\DestinyAType[] $destiny
     */
    private $destiny = array(
        
    );

    /**
     * Adds as destiny
     *
     * Предназначение подписи
     *
     * @return static
     * @param \common\models\sbbolxml\response\CryptoproType\DestinysAType\DestinyAType $destiny
     */
    public function addToDestiny(\common\models\sbbolxml\response\CryptoproType\DestinysAType\DestinyAType $destiny)
    {
        $this->destiny[] = $destiny;
        return $this;
    }

    /**
     * isset destiny
     *
     * Предназначение подписи
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDestiny($index)
    {
        return isset($this->destiny[$index]);
    }

    /**
     * unset destiny
     *
     * Предназначение подписи
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDestiny($index)
    {
        unset($this->destiny[$index]);
    }

    /**
     * Gets as destiny
     *
     * Предназначение подписи
     *
     * @return \common\models\sbbolxml\response\CryptoproType\DestinysAType\DestinyAType[]
     */
    public function getDestiny()
    {
        return $this->destiny;
    }

    /**
     * Sets a new destiny
     *
     * Предназначение подписи
     *
     * @param \common\models\sbbolxml\response\CryptoproType\DestinysAType\DestinyAType[] $destiny
     * @return static
     */
    public function setDestiny(array $destiny)
    {
        $this->destiny = $destiny;
        return $this;
    }


}

