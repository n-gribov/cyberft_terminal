<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing OrganizationsInfoAType
 */
class OrganizationsInfoAType
{

    /**
     * Данные одной организации
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType[] $organizationInfo
     */
    private $organizationInfo = array(
        
    );

    /**
     * Adds as organizationInfo
     *
     * Данные одной организации
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType $organizationInfo
     */
    public function addToOrganizationInfo(\common\models\sbbolxml\response\OrganizationInfoType $organizationInfo)
    {
        $this->organizationInfo[] = $organizationInfo;
        return $this;
    }

    /**
     * isset organizationInfo
     *
     * Данные одной организации
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOrganizationInfo($index)
    {
        return isset($this->organizationInfo[$index]);
    }

    /**
     * unset organizationInfo
     *
     * Данные одной организации
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOrganizationInfo($index)
    {
        unset($this->organizationInfo[$index]);
    }

    /**
     * Gets as organizationInfo
     *
     * Данные одной организации
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType[]
     */
    public function getOrganizationInfo()
    {
        return $this->organizationInfo;
    }

    /**
     * Sets a new organizationInfo
     *
     * Данные одной организации
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType[] $organizationInfo
     * @return static
     */
    public function setOrganizationInfo(array $organizationInfo)
    {
        $this->organizationInfo = $organizationInfo;
        return $this;
    }


}

