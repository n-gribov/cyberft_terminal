<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType;

/**
 * Class representing EPSIdsAType
 */
class EPSIdsAType
{

    /**
     * @property string[] $ePSId
     */
    private $ePSId = array(
        
    );

    /**
     * Adds as ePSId
     *
     * @return static
     * @param string $ePSId
     */
    public function addToEPSId($ePSId)
    {
        $this->ePSId[] = $ePSId;
        return $this;
    }

    /**
     * isset ePSId
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetEPSId($index)
    {
        return isset($this->ePSId[$index]);
    }

    /**
     * unset ePSId
     *
     * @param scalar $index
     * @return void
     */
    public function unsetEPSId($index)
    {
        unset($this->ePSId[$index]);
    }

    /**
     * Gets as ePSId
     *
     * @return string[]
     */
    public function getEPSId()
    {
        return $this->ePSId;
    }

    /**
     * Sets a new ePSId
     *
     * @param string[] $ePSId
     * @return static
     */
    public function setEPSId(array $ePSId)
    {
        $this->ePSId = $ePSId;
        return $this;
    }


}

