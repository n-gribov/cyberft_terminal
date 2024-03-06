<?php

namespace common\models\raiffeisenxml\response\StatementType;

/**
 * Class representing DocsAType
 */
class DocsAType
{

    /**
     * @property \common\models\raiffeisenxml\response\TransInfoType[] $transInfo
     */
    private $transInfo = [
        
    ];

    /**
     * Adds as transInfo
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\TransInfoType $transInfo
     */
    public function addToTransInfo(\common\models\raiffeisenxml\response\TransInfoType $transInfo)
    {
        $this->transInfo[] = $transInfo;
        return $this;
    }

    /**
     * isset transInfo
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTransInfo($index)
    {
        return isset($this->transInfo[$index]);
    }

    /**
     * unset transInfo
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTransInfo($index)
    {
        unset($this->transInfo[$index]);
    }

    /**
     * Gets as transInfo
     *
     * @return \common\models\raiffeisenxml\response\TransInfoType[]
     */
    public function getTransInfo()
    {
        return $this->transInfo;
    }

    /**
     * Sets a new transInfo
     *
     * @param \common\models\raiffeisenxml\response\TransInfoType[] $transInfo
     * @return static
     */
    public function setTransInfo(array $transInfo)
    {
        $this->transInfo = $transInfo;
        return $this;
    }


}

