<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\CreditContractsAType;

/**
 * Class representing CreditContractAType
 */
class CreditContractAType
{

    /**
     * Идентификатор кредитного
     *  договора
     *
     * @property string $credContrtId
     */
    private $credContrtId = null;

    /**
     * Gets as credContrtId
     *
     * Идентификатор кредитного
     *  договора
     *
     * @return string
     */
    public function getCredContrtId()
    {
        return $this->credContrtId;
    }

    /**
     * Sets a new credContrtId
     *
     * Идентификатор кредитного
     *  договора
     *
     * @param string $credContrtId
     * @return static
     */
    public function setCredContrtId($credContrtId)
    {
        $this->credContrtId = $credContrtId;
        return $this;
    }


}

