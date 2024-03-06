<?php

namespace common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType;

/**
 * Class representing GroundInfoAType
 */
class GroundInfoAType
{

    /**
     * @property \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType[] $ground
     */
    private $ground = [
        
    ];

    /**
     * Adds as ground
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType $ground
     */
    public function addToGround(\common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType $ground)
    {
        $this->ground[] = $ground;
        return $this;
    }

    /**
     * isset ground
     *
     * @param int|string $index
     * @return bool
     */
    public function issetGround($index)
    {
        return isset($this->ground[$index]);
    }

    /**
     * unset ground
     *
     * @param int|string $index
     * @return void
     */
    public function unsetGround($index)
    {
        unset($this->ground[$index]);
    }

    /**
     * Gets as ground
     *
     * @return \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType[]
     */
    public function getGround()
    {
        return $this->ground;
    }

    /**
     * Sets a new ground
     *
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType[] $ground
     * @return static
     */
    public function setGround(array $ground)
    {
        $this->ground = $ground;
        return $this;
    }


}

