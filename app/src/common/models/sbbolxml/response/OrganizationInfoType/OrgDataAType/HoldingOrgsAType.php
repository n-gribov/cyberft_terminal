<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType;

/**
 * Class representing HoldingOrgsAType
 */
class HoldingOrgsAType
{

    /**
     * @property string[] $org
     */
    private $org = array(
        
    );

    /**
     * Adds as org
     *
     * @return static
     * @param string $org
     */
    public function addToOrg($org)
    {
        $this->org[] = $org;
        return $this;
    }

    /**
     * isset org
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOrg($index)
    {
        return isset($this->org[$index]);
    }

    /**
     * unset org
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOrg($index)
    {
        unset($this->org[$index]);
    }

    /**
     * Gets as org
     *
     * @return string[]
     */
    public function getOrg()
    {
        return $this->org;
    }

    /**
     * Sets a new org
     *
     * @param string $org
     * @return static
     */
    public function setOrg(array $org)
    {
        $this->org = $org;
        return $this;
    }


}

