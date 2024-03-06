<?php

namespace common\models\sbbolxml\response\StatementType;

/**
 * Class representing OutdatedDocsAType
 */
class OutdatedDocsAType
{

    /**
     * @property \common\models\sbbolxml\response\StatementType\OutdatedDocsAType\TransInfoAType[] $transInfo
     */
    private $transInfo = array(
        
    );

    /**
     * Adds as transInfo
     *
     * @return static
     * @param \common\models\sbbolxml\response\StatementType\OutdatedDocsAType\TransInfoAType $transInfo
     */
    public function addToTransInfo(\common\models\sbbolxml\response\StatementType\OutdatedDocsAType\TransInfoAType $transInfo)
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
     * @return \common\models\sbbolxml\response\StatementType\OutdatedDocsAType\TransInfoAType[]
     */
    public function getTransInfo()
    {
        return $this->transInfo;
    }

    /**
     * Sets a new transInfo
     *
     * @param \common\models\sbbolxml\response\StatementType\OutdatedDocsAType\TransInfoAType[] $transInfo
     * @return static
     */
    public function setTransInfo(array $transInfo)
    {
        $this->transInfo = $transInfo;
        return $this;
    }


}

