<?php

namespace common\models\raiffeisenxml\response\DebetFullType;

/**
 * Class representing BlockedSumInfoAType
 */
class BlockedSumInfoAType
{

    /**
     * Заблокированные (арестованные) суммы на счёте.
     *
     * @property \common\models\raiffeisenxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType[] $blockedSum
     */
    private $blockedSum = [
        
    ];

    /**
     * Adds as blockedSum
     *
     * Заблокированные (арестованные) суммы на счёте.
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType $blockedSum
     */
    public function addToBlockedSum(\common\models\raiffeisenxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType $blockedSum)
    {
        $this->blockedSum[] = $blockedSum;
        return $this;
    }

    /**
     * isset blockedSum
     *
     * Заблокированные (арестованные) суммы на счёте.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBlockedSum($index)
    {
        return isset($this->blockedSum[$index]);
    }

    /**
     * unset blockedSum
     *
     * Заблокированные (арестованные) суммы на счёте.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBlockedSum($index)
    {
        unset($this->blockedSum[$index]);
    }

    /**
     * Gets as blockedSum
     *
     * Заблокированные (арестованные) суммы на счёте.
     *
     * @return \common\models\raiffeisenxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType[]
     */
    public function getBlockedSum()
    {
        return $this->blockedSum;
    }

    /**
     * Sets a new blockedSum
     *
     * Заблокированные (арестованные) суммы на счёте.
     *
     * @param \common\models\raiffeisenxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType[] $blockedSum
     * @return static
     */
    public function setBlockedSum(array $blockedSum)
    {
        $this->blockedSum = $blockedSum;
        return $this;
    }


}

