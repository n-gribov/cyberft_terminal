<?php

namespace common\models\sbbolxml\response\DebetFullType;

/**
 * Class representing BlockedSumQueuesInfoAType
 */
class BlockedSumQueuesInfoAType
{

    /**
     * Блокировка по сумме и очерёдности платежей
     *
     * @property \common\models\sbbolxml\response\BlockedSumQueuesFullType[] $blockedSumQueues
     */
    private $blockedSumQueues = array(
        
    );

    /**
     * Adds as blockedSumQueues
     *
     * Блокировка по сумме и очерёдности платежей
     *
     * @return static
     * @param \common\models\sbbolxml\response\BlockedSumQueuesFullType $blockedSumQueues
     */
    public function addToBlockedSumQueues(\common\models\sbbolxml\response\BlockedSumQueuesFullType $blockedSumQueues)
    {
        $this->blockedSumQueues[] = $blockedSumQueues;
        return $this;
    }

    /**
     * isset blockedSumQueues
     *
     * Блокировка по сумме и очерёдности платежей
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\response\BlockedSumQueuesFullType[]
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
     * @param \common\models\sbbolxml\response\BlockedSumQueuesFullType[] $blockedSumQueues
     * @return static
     */
    public function setBlockedSumQueues(array $blockedSumQueues)
    {
        $this->blockedSumQueues = $blockedSumQueues;
        return $this;
    }


}

