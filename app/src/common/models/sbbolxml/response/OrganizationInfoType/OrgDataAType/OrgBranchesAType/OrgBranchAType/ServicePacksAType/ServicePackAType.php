<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\ServicePacksAType;

/**
 * Class representing ServicePackAType
 */
class ServicePackAType
{

    /**
     * Идентификатор пакета
     *  услуг
     *
     * @property string $packageId
     */
    private $packageId = null;

    /**
     * Gets as packageId
     *
     * Идентификатор пакета
     *  услуг
     *
     * @return string
     */
    public function getPackageId()
    {
        return $this->packageId;
    }

    /**
     * Sets a new packageId
     *
     * Идентификатор пакета
     *  услуг
     *
     * @param string $packageId
     * @return static
     */
    public function setPackageId($packageId)
    {
        $this->packageId = $packageId;
        return $this;
    }


}

