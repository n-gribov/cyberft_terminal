<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType;

/**
 * Class representing ToleranceAType
 */
class ToleranceAType
{

    /**
     * Плюс
     *
     * @property float $plus
     */
    private $plus = null;

    /**
     * Минус
     *
     * @property float $minus
     */
    private $minus = null;

    /**
     * Gets as plus
     *
     * Плюс
     *
     * @return float
     */
    public function getPlus()
    {
        return $this->plus;
    }

    /**
     * Sets a new plus
     *
     * Плюс
     *
     * @param float $plus
     * @return static
     */
    public function setPlus($plus)
    {
        $this->plus = $plus;
        return $this;
    }

    /**
     * Gets as minus
     *
     * Минус
     *
     * @return float
     */
    public function getMinus()
    {
        return $this->minus;
    }

    /**
     * Sets a new minus
     *
     * Минус
     *
     * @param float $minus
     * @return static
     */
    public function setMinus($minus)
    {
        $this->minus = $minus;
        return $this;
    }


}

