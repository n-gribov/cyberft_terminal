<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType;

/**
 * Class representing AdmContractAType
 */
class AdmContractAType
{

    /**
     * Реквизиты договора самоинкосации
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType\USDetailsAType $uSDetails
     */
    private $uSDetails = null;

    /**
     * Реквизиты договора на обслуживание через АДМ
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType\ADMDetailsAType $aDMDetails
     */
    private $aDMDetails = null;

    /**
     * Gets as uSDetails
     *
     * Реквизиты договора самоинкосации
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType\USDetailsAType
     */
    public function getUSDetails()
    {
        return $this->uSDetails;
    }

    /**
     * Sets a new uSDetails
     *
     * Реквизиты договора самоинкосации
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType\USDetailsAType $uSDetails
     * @return static
     */
    public function setUSDetails(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType\USDetailsAType $uSDetails)
    {
        $this->uSDetails = $uSDetails;
        return $this;
    }

    /**
     * Gets as aDMDetails
     *
     * Реквизиты договора на обслуживание через АДМ
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType\ADMDetailsAType
     */
    public function getADMDetails()
    {
        return $this->aDMDetails;
    }

    /**
     * Sets a new aDMDetails
     *
     * Реквизиты договора на обслуживание через АДМ
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType\ADMDetailsAType $aDMDetails
     * @return static
     */
    public function setADMDetails(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType\ADMDetailsAType $aDMDetails)
    {
        $this->aDMDetails = $aDMDetails;
        return $this;
    }


}

