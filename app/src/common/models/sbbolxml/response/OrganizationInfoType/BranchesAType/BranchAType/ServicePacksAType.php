<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType;

/**
 * Class representing ServicePacksAType
 */
class ServicePacksAType
{

    /**
     * @property \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ServicePacksAType\ServicePackAType[] $servicePack
     */
    private $servicePack = array(
        
    );

    /**
     * Adds as servicePack
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ServicePacksAType\ServicePackAType $servicePack
     */
    public function addToServicePack(\common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ServicePacksAType\ServicePackAType $servicePack)
    {
        $this->servicePack[] = $servicePack;
        return $this;
    }

    /**
     * isset servicePack
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetServicePack($index)
    {
        return isset($this->servicePack[$index]);
    }

    /**
     * unset servicePack
     *
     * @param scalar $index
     * @return void
     */
    public function unsetServicePack($index)
    {
        unset($this->servicePack[$index]);
    }

    /**
     * Gets as servicePack
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ServicePacksAType\ServicePackAType[]
     */
    public function getServicePack()
    {
        return $this->servicePack;
    }

    /**
     * Sets a new servicePack
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType\ServicePacksAType\ServicePackAType[] $servicePack
     * @return static
     */
    public function setServicePack(array $servicePack)
    {
        $this->servicePack = $servicePack;
        return $this;
    }


}

