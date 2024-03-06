<?php

namespace common\models\raiffeisenxml\request\ChanDPType;

/**
 * Class representing DpInfoAType
 */
class DpInfoAType
{

    /**
     * @property \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType[] $oper
     */
    private $oper = [
        
    ];

    /**
     * Adds as oper
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType $oper
     */
    public function addToOper(\common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType $oper)
    {
        $this->oper[] = $oper;
        return $this;
    }

    /**
     * isset oper
     *
     * @param int|string $index
     * @return bool
     */
    public function issetOper($index)
    {
        return isset($this->oper[$index]);
    }

    /**
     * unset oper
     *
     * @param int|string $index
     * @return void
     */
    public function unsetOper($index)
    {
        unset($this->oper[$index]);
    }

    /**
     * Gets as oper
     *
     * @return \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType[]
     */
    public function getOper()
    {
        return $this->oper;
    }

    /**
     * Sets a new oper
     *
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType[] $oper
     * @return static
     */
    public function setOper(array $oper)
    {
        $this->oper = $oper;
        return $this;
    }


}

