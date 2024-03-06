<?php

namespace common\models\raiffeisenxml\response\CryptoproType;

/**
 * Class representing DestinysAType
 */
class DestinysAType
{

    /**
     * Предназначение подписи
     *
     * @property \common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType[] $destiny
     */
    private $destiny = [
        
    ];

    /**
     * Adds as destiny
     *
     * Предназначение подписи
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType $destiny
     */
    public function addToDestiny(\common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType $destiny)
    {
        $this->destiny[] = $destiny;
        return $this;
    }

    /**
     * isset destiny
     *
     * Предназначение подписи
     *
     * @param int|string $index
     * @return bool
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
     * @param int|string $index
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
     * @return \common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType[]
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
     * @param \common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType[] $destiny
     * @return static
     */
    public function setDestiny(array $destiny)
    {
        $this->destiny = $destiny;
        return $this;
    }


}

