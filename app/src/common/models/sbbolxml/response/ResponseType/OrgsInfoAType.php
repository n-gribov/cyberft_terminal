<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing OrgsInfoAType
 */
class OrgsInfoAType
{

    /**
     * Данные одной организации
     *
     * @property \common\models\sbbolxml\response\OrgInfoType[] $orgInfo
     */
    private $orgInfo = array(
        
    );

    /**
     * Adds as orgInfo
     *
     * Данные одной организации
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType $orgInfo
     */
    public function addToOrgInfo(\common\models\sbbolxml\response\OrgInfoType $orgInfo)
    {
        $this->orgInfo[] = $orgInfo;
        return $this;
    }

    /**
     * isset orgInfo
     *
     * Данные одной организации
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOrgInfo($index)
    {
        return isset($this->orgInfo[$index]);
    }

    /**
     * unset orgInfo
     *
     * Данные одной организации
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOrgInfo($index)
    {
        unset($this->orgInfo[$index]);
    }

    /**
     * Gets as orgInfo
     *
     * Данные одной организации
     *
     * @return \common\models\sbbolxml\response\OrgInfoType[]
     */
    public function getOrgInfo()
    {
        return $this->orgInfo;
    }

    /**
     * Sets a new orgInfo
     *
     * Данные одной организации
     *
     * @param \common\models\sbbolxml\response\OrgInfoType[] $orgInfo
     * @return static
     */
    public function setOrgInfo(array $orgInfo)
    {
        $this->orgInfo = $orgInfo;
        return $this;
    }


}

