<?php

namespace common\models\raiffeisenxml\request\ChanDPCredRaifType;

/**
 * Class representing TranchesInfoAType
 */
class TranchesInfoAType
{

    /**
     * @property \common\models\raiffeisenxml\request\TrancheInfoType[] $trancheInfo
     */
    private $trancheInfo = [
        
    ];

    /**
     * Adds as trancheInfo
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\TrancheInfoType $trancheInfo
     */
    public function addToTrancheInfo(\common\models\raiffeisenxml\request\TrancheInfoType $trancheInfo)
    {
        $this->trancheInfo[] = $trancheInfo;
        return $this;
    }

    /**
     * isset trancheInfo
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTrancheInfo($index)
    {
        return isset($this->trancheInfo[$index]);
    }

    /**
     * unset trancheInfo
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTrancheInfo($index)
    {
        unset($this->trancheInfo[$index]);
    }

    /**
     * Gets as trancheInfo
     *
     * @return \common\models\raiffeisenxml\request\TrancheInfoType[]
     */
    public function getTrancheInfo()
    {
        return $this->trancheInfo;
    }

    /**
     * Sets a new trancheInfo
     *
     * @param \common\models\raiffeisenxml\request\TrancheInfoType[] $trancheInfo
     * @return static
     */
    public function setTrancheInfo(array $trancheInfo)
    {
        $this->trancheInfo = $trancheInfo;
        return $this;
    }


}

