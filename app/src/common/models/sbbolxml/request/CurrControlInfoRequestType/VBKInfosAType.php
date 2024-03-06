<?php

namespace common\models\sbbolxml\request\CurrControlInfoRequestType;

/**
 * Class representing VBKInfosAType
 */
class VBKInfosAType
{

    /**
     * Ведомость банковского контроля
     *
     * @property \common\models\sbbolxml\request\VBKInfoType[] $vBKInfo
     */
    private $vBKInfo = array(
        
    );

    /**
     * Adds as vBKInfo
     *
     * Ведомость банковского контроля
     *
     * @return static
     * @param \common\models\sbbolxml\request\VBKInfoType $vBKInfo
     */
    public function addToVBKInfo(\common\models\sbbolxml\request\VBKInfoType $vBKInfo)
    {
        $this->vBKInfo[] = $vBKInfo;
        return $this;
    }

    /**
     * isset vBKInfo
     *
     * Ведомость банковского контроля
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetVBKInfo($index)
    {
        return isset($this->vBKInfo[$index]);
    }

    /**
     * unset vBKInfo
     *
     * Ведомость банковского контроля
     *
     * @param scalar $index
     * @return void
     */
    public function unsetVBKInfo($index)
    {
        unset($this->vBKInfo[$index]);
    }

    /**
     * Gets as vBKInfo
     *
     * Ведомость банковского контроля
     *
     * @return \common\models\sbbolxml\request\VBKInfoType[]
     */
    public function getVBKInfo()
    {
        return $this->vBKInfo;
    }

    /**
     * Sets a new vBKInfo
     *
     * Ведомость банковского контроля
     *
     * @param \common\models\sbbolxml\request\VBKInfoType[] $vBKInfo
     * @return static
     */
    public function setVBKInfo(array $vBKInfo)
    {
        $this->vBKInfo = $vBKInfo;
        return $this;
    }


}

