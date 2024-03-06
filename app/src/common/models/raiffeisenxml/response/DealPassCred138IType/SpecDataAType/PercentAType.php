<?php

namespace common\models\raiffeisenxml\response\DealPassCred138IType\SpecDataAType;

/**
 * Class representing PercentAType
 */
class PercentAType
{

    /**
     * 1. Фиксированный размер процентной ставки (% годовых)
     *
     * @property float $fixPercent
     */
    private $fixPercent = null;

    /**
     * 2. Код ставки LIBOR
     *  например, Л06
     *
     * @property string $lIBOR
     */
    private $lIBOR = null;

    /**
     * 3. Другие методы определения процентной ставки
     *
     * @property string $otherMethod
     */
    private $otherMethod = null;

    /**
     * 4. Размер процентной надбавки (% годовых)
     *
     * @property float $incrPercent
     */
    private $incrPercent = null;

    /**
     * Gets as fixPercent
     *
     * 1. Фиксированный размер процентной ставки (% годовых)
     *
     * @return float
     */
    public function getFixPercent()
    {
        return $this->fixPercent;
    }

    /**
     * Sets a new fixPercent
     *
     * 1. Фиксированный размер процентной ставки (% годовых)
     *
     * @param float $fixPercent
     * @return static
     */
    public function setFixPercent($fixPercent)
    {
        $this->fixPercent = $fixPercent;
        return $this;
    }

    /**
     * Gets as lIBOR
     *
     * 2. Код ставки LIBOR
     *  например, Л06
     *
     * @return string
     */
    public function getLIBOR()
    {
        return $this->lIBOR;
    }

    /**
     * Sets a new lIBOR
     *
     * 2. Код ставки LIBOR
     *  например, Л06
     *
     * @param string $lIBOR
     * @return static
     */
    public function setLIBOR($lIBOR)
    {
        $this->lIBOR = $lIBOR;
        return $this;
    }

    /**
     * Gets as otherMethod
     *
     * 3. Другие методы определения процентной ставки
     *
     * @return string
     */
    public function getOtherMethod()
    {
        return $this->otherMethod;
    }

    /**
     * Sets a new otherMethod
     *
     * 3. Другие методы определения процентной ставки
     *
     * @param string $otherMethod
     * @return static
     */
    public function setOtherMethod($otherMethod)
    {
        $this->otherMethod = $otherMethod;
        return $this;
    }

    /**
     * Gets as incrPercent
     *
     * 4. Размер процентной надбавки (% годовых)
     *
     * @return float
     */
    public function getIncrPercent()
    {
        return $this->incrPercent;
    }

    /**
     * Sets a new incrPercent
     *
     * 4. Размер процентной надбавки (% годовых)
     *
     * @param float $incrPercent
     * @return static
     */
    public function setIncrPercent($incrPercent)
    {
        $this->incrPercent = $incrPercent;
        return $this;
    }


}

