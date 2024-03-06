<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\SalaryContractsAType;

/**
 * Class representing SalaryContractAType
 */
class SalaryContractAType
{

    /**
     * Идентификатор договора
     *
     * @property string $contrID
     */
    private $contrID = null;

    /**
     * Gets as contrID
     *
     * Идентификатор договора
     *
     * @return string
     */
    public function getContrID()
    {
        return $this->contrID;
    }

    /**
     * Sets a new contrID
     *
     * Идентификатор договора
     *
     * @param string $contrID
     * @return static
     */
    public function setContrID($contrID)
    {
        $this->contrID = $contrID;
        return $this;
    }


}

