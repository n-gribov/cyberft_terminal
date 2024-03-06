<?php

namespace common\models\raiffeisenxml\request\DealPassCred138IRaifType\SpecDataAType;

/**
 * Class representing DebtSumAType
 */
class DebtSumAType
{

    /**
     * Сумма
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * Gets as docSum
     *
     * Сумма
     *
     * @return float
     */
    public function getDocSum()
    {
        return $this->docSum;
    }

    /**
     * Sets a new docSum
     *
     * Сумма
     *
     * @param float $docSum
     * @return static
     */
    public function setDocSum($docSum)
    {
        $this->docSum = $docSum;
        return $this;
    }


}
