<?php

namespace common\models\raiffeisenxml\response\ResponseType;

/**
 * Class representing OrgsInfoAType
 */
class OrgsInfoAType
{

    /**
     * @property \common\models\raiffeisenxml\response\OrgInfoType[] $orgInfo
     */
    private $orgInfo = [
        
    ];

    /**
     * Adds as orgInfo
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType $orgInfo
     */
    public function addToOrgInfo(\common\models\raiffeisenxml\response\OrgInfoType $orgInfo)
    {
        $this->orgInfo[] = $orgInfo;
        return $this;
    }

    /**
     * isset orgInfo
     *
     * @param int|string $index
     * @return bool
     */
    public function issetOrgInfo($index)
    {
        return isset($this->orgInfo[$index]);
    }

    /**
     * unset orgInfo
     *
     * @param int|string $index
     * @return void
     */
    public function unsetOrgInfo($index)
    {
        unset($this->orgInfo[$index]);
    }

    /**
     * Gets as orgInfo
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType[]
     */
    public function getOrgInfo()
    {
        return $this->orgInfo;
    }

    /**
     * Sets a new orgInfo
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType[] $orgInfo
     * @return static
     */
    public function setOrgInfo(array $orgInfo)
    {
        $this->orgInfo = $orgInfo;
        return $this;
    }


}

