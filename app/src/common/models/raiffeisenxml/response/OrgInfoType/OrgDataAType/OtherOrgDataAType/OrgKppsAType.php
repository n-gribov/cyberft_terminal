<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType;

/**
 * Class representing OrgKppsAType
 */
class OrgKppsAType
{

    /**
     * @property \common\models\raiffeisenxml\response\OrgKppType[] $orgKpp
     */
    private $orgKpp = [
        
    ];

    /**
     * Adds as orgKpp
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgKppType $orgKpp
     */
    public function addToOrgKpp(\common\models\raiffeisenxml\response\OrgKppType $orgKpp)
    {
        $this->orgKpp[] = $orgKpp;
        return $this;
    }

    /**
     * isset orgKpp
     *
     * @param int|string $index
     * @return bool
     */
    public function issetOrgKpp($index)
    {
        return isset($this->orgKpp[$index]);
    }

    /**
     * unset orgKpp
     *
     * @param int|string $index
     * @return void
     */
    public function unsetOrgKpp($index)
    {
        unset($this->orgKpp[$index]);
    }

    /**
     * Gets as orgKpp
     *
     * @return \common\models\raiffeisenxml\response\OrgKppType[]
     */
    public function getOrgKpp()
    {
        return $this->orgKpp;
    }

    /**
     * Sets a new orgKpp
     *
     * @param \common\models\raiffeisenxml\response\OrgKppType[] $orgKpp
     * @return static
     */
    public function setOrgKpp(array $orgKpp)
    {
        $this->orgKpp = $orgKpp;
        return $this;
    }


}

