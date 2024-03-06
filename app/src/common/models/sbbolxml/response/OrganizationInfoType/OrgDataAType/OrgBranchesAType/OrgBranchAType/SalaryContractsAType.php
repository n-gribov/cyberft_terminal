<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType;

/**
 * Class representing SalaryContractsAType
 */
class SalaryContractsAType
{

    /**
     * Содержит информацию одного зп
     *  договора
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\SalaryContractsAType\SalaryContractAType[] $salaryContract
     */
    private $salaryContract = array(
        
    );

    /**
     * Adds as salaryContract
     *
     * Содержит информацию одного зп
     *  договора
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\SalaryContractsAType\SalaryContractAType $salaryContract
     */
    public function addToSalaryContract(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\SalaryContractsAType\SalaryContractAType $salaryContract)
    {
        $this->salaryContract[] = $salaryContract;
        return $this;
    }

    /**
     * isset salaryContract
     *
     * Содержит информацию одного зп
     *  договора
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSalaryContract($index)
    {
        return isset($this->salaryContract[$index]);
    }

    /**
     * unset salaryContract
     *
     * Содержит информацию одного зп
     *  договора
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSalaryContract($index)
    {
        unset($this->salaryContract[$index]);
    }

    /**
     * Gets as salaryContract
     *
     * Содержит информацию одного зп
     *  договора
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\SalaryContractsAType\SalaryContractAType[]
     */
    public function getSalaryContract()
    {
        return $this->salaryContract;
    }

    /**
     * Sets a new salaryContract
     *
     * Содержит информацию одного зп
     *  договора
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OrgBranchesAType\OrgBranchAType\SalaryContractsAType\SalaryContractAType[] $salaryContract
     * @return static
     */
    public function setSalaryContract(array $salaryContract)
    {
        $this->salaryContract = $salaryContract;
        return $this;
    }


}

