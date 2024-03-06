<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType;

/**
 * Class representing CreditContractsAType
 */
class CreditContractsAType
{

    /**
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\CreditContractsAType\CreditContractAType[] $creditContract
     */
    private $creditContract = array(
        
    );

    /**
     * Adds as creditContract
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\CreditContractsAType\CreditContractAType $creditContract
     */
    public function addToCreditContract(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\CreditContractsAType\CreditContractAType $creditContract)
    {
        $this->creditContract[] = $creditContract;
        return $this;
    }

    /**
     * isset creditContract
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCreditContract($index)
    {
        return isset($this->creditContract[$index]);
    }

    /**
     * unset creditContract
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCreditContract($index)
    {
        unset($this->creditContract[$index]);
    }

    /**
     * Gets as creditContract
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\CreditContractsAType\CreditContractAType[]
     */
    public function getCreditContract()
    {
        return $this->creditContract;
    }

    /**
     * Sets a new creditContract
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\CreditContractsAType\CreditContractAType[] $creditContract
     * @return static
     */
    public function setCreditContract(array $creditContract)
    {
        $this->creditContract = $creditContract;
        return $this;
    }


}

