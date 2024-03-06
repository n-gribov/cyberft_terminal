<?php

namespace common\models\sbbolxml\response\StatementType;

/**
 * Class representing DocsAType
 */
class DocsAType
{

    /**
     * @property \common\models\sbbolxml\response\TransInfoType[] $transInfo
     */
    private $transInfo = array(
        
    );

    /**
     * Adds as transInfo
     *
     * @return static
     * @param \common\models\sbbolxml\response\TransInfoType $transInfo
     */
    public function addToTransInfo(\common\models\sbbolxml\response\TransInfoType $transInfo)
    {
        $this->transInfo[] = $transInfo;
        return $this;
    }

    /**
     * isset transInfo
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetTransInfo($index)
    {
        return isset($this->transInfo[$index]);
    }

    /**
     * unset transInfo
     *
     * @param scalar $index
     * @return void
     */
    public function unsetTransInfo($index)
    {
        unset($this->transInfo[$index]);
    }

    /**
     * Gets as transInfo
     *
     * @return \common\models\sbbolxml\response\TransInfoType[]
     */
    public function getTransInfo()
    {
        return $this->transInfo;
    }

    /**
     * Sets a new transInfo
     *
     * @param \common\models\sbbolxml\response\TransInfoType[] $transInfo
     * @return static
     */
    public function setTransInfo(array $transInfo)
    {
        $this->transInfo = $transInfo;
        return $this;
    }


}

