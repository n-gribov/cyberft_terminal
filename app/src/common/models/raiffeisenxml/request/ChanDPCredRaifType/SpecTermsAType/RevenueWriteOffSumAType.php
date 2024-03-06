<?php

namespace common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType;

/**
 * Class representing RevenueWriteOffSumAType
 */
class RevenueWriteOffSumAType
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

