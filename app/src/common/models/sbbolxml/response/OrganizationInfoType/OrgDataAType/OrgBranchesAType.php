<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType;

/**
 * Class representing OrgBranchesAType
 */
class OrgBranchesAType
{

    /**
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType[] $orgBranch
     */
    private $orgBranch = array(
        
    );

    /**
     * Adds as orgBranch
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType $orgBranch
     */
    public function addToOrgBranch(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType $orgBranch)
    {
        $this->orgBranch[] = $orgBranch;
        return $this;
    }

    /**
     * isset orgBranch
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOrgBranch($index)
    {
        return isset($this->orgBranch[$index]);
    }

    /**
     * unset orgBranch
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOrgBranch($index)
    {
        unset($this->orgBranch[$index]);
    }

    /**
     * Gets as orgBranch
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType[]
     */
    public function getOrgBranch()
    {
        return $this->orgBranch;
    }

    /**
     * Sets a new orgBranch
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType[] $orgBranch
     * @return static
     */
    public function setOrgBranch(array $orgBranch)
    {
        $this->orgBranch = $orgBranch;
        return $this;
    }


}

