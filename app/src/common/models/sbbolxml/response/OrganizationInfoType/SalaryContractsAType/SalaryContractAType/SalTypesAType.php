<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType;

/**
 * Class representing SalTypesAType
 */
class SalTypesAType
{

    /**
     * Допустимый вид зачислений
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\SalTypesAType\SalTypeAType[] $salType
     */
    private $salType = array(
        
    );

    /**
     * Adds as salType
     *
     * Допустимый вид зачислений
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\SalTypesAType\SalTypeAType $salType
     */
    public function addToSalType(\common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\SalTypesAType\SalTypeAType $salType)
    {
        $this->salType[] = $salType;
        return $this;
    }

    /**
     * isset salType
     *
     * Допустимый вид зачислений
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSalType($index)
    {
        return isset($this->salType[$index]);
    }

    /**
     * unset salType
     *
     * Допустимый вид зачислений
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSalType($index)
    {
        unset($this->salType[$index]);
    }

    /**
     * Gets as salType
     *
     * Допустимый вид зачислений
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\SalTypesAType\SalTypeAType[]
     */
    public function getSalType()
    {
        return $this->salType;
    }

    /**
     * Sets a new salType
     *
     * Допустимый вид зачислений
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\SalTypesAType\SalTypeAType[] $salType
     * @return static
     */
    public function setSalType(array $salType)
    {
        $this->salType = $salType;
        return $this;
    }


}

