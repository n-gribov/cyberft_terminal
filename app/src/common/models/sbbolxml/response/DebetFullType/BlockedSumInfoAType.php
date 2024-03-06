<?php

namespace common\models\sbbolxml\response\DebetFullType;

/**
 * Class representing BlockedSumInfoAType
 */
class BlockedSumInfoAType
{

    /**
     * Заблокированные (арестованные) суммы на счёте.
     *
     * @property \common\models\sbbolxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType[] $blockedSum
     */
    private $blockedSum = array(
        
    );

    /**
     * Adds as blockedSum
     *
     * Заблокированные (арестованные) суммы на счёте.
     *
     * @return static
     * @param \common\models\sbbolxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType $blockedSum
     */
    public function addToBlockedSum(\common\models\sbbolxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType $blockedSum)
    {
        $this->blockedSum[] = $blockedSum;
        return $this;
    }

    /**
     * isset blockedSum
     *
     * Заблокированные (арестованные) суммы на счёте.
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType[]
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
     * @param \common\models\sbbolxml\response\DebetFullType\BlockedSumInfoAType\BlockedSumAType[] $blockedSum
     * @return static
     */
    public function setBlockedSum(array $blockedSum)
    {
        $this->blockedSum = $blockedSum;
        return $this;
    }


}

