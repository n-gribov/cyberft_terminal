<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing HoldingInfoAType
 */
class HoldingInfoAType
{

    /**
     * @property \common\models\sbbolxml\response\HoldingInfoOrgType[] $orgs
     */
    private $orgs = null;

    /**
     * Adds as org
     *
     * @return static
     * @param \common\models\sbbolxml\response\HoldingInfoOrgType $org
     */
    public function addToOrgs(\common\models\sbbolxml\response\HoldingInfoOrgType $org)
    {
        $this->orgs[] = $org;
        return $this;
    }

    /**
     * isset orgs
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOrgs($index)
    {
        return isset($this->orgs[$index]);
    }

    /**
     * unset orgs
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOrgs($index)
    {
        unset($this->orgs[$index]);
    }

    /**
     * Gets as orgs
     *
     * @return \common\models\sbbolxml\response\HoldingInfoOrgType[]
     */
    public function getOrgs()
    {
        return $this->orgs;
    }

    /**
     * Sets a new orgs
     *
     * @param \common\models\sbbolxml\response\HoldingInfoOrgType[] $orgs
     * @return static
     */
    public function setOrgs(array $orgs)
    {
        $this->orgs = $orgs;
        return $this;
    }


}

