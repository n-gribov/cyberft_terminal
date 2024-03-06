<?php

namespace common\models\raiffeisenxml\response\DebetFullType;

/**
 * Class representing BlockedSumQueuesInfoAType
 */
class BlockedSumQueuesInfoAType
{

    /**
     * Блокировка по сумме и очерёдности платежей
     *
     * @property \common\models\raiffeisenxml\response\BlockedSumQueuesFullType[] $blockedSumQueues
     */
    private $blockedSumQueues = [
        
    ];

    /**
     * Adds as blockedSumQueues
     *
     * Блокировка по сумме и очерёдности платежей
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\BlockedSumQueuesFullType $blockedSumQueues
     */
    public function addToBlockedSumQueues(\common\models\raiffeisenxml\response\BlockedSumQueuesFullType $blockedSumQueues)
    {
        $this->blockedSumQueues[] = $blockedSumQueues;
        return $this;
    }

    /**
     * isset blockedSumQueues
     *
     * Блокировка по сумме и очерёдности платежей
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBlockedSumQueues($index)
    {
        return isset($this->blockedSumQueues[$index]);
    }

    /**
     * unset blockedSumQueues
     *
     * Блокировка по сумме и очерёдности платежей
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBlockedSumQueues($index)
    {
        unset($this->blockedSumQueues[$index]);
    }

    /**
     * Gets as blockedSumQueues
     *
     * Блокировка по сумме и очерёдности платежей
     *
     * @return \common\models\raiffeisenxml\response\BlockedSumQueuesFullType[]
     */
    public function getBlockedSumQueues()
    {
        return $this->blockedSumQueues;
    }

    /**
     * Sets a new blockedSumQueues
     *
     * Блокировка по сумме и очерёдности платежей
     *
     * @param \common\models\raiffeisenxml\response\BlockedSumQueuesFullType[] $blockedSumQueues
     * @return static
     */
    public function setBlockedSumQueues(array $blockedSumQueues)
    {
        $this->blockedSumQueues = $blockedSumQueues;
        return $this;
    }


}

