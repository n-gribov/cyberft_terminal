<?php

namespace common\models\sbbolxml\response\OrgInfoType;

/**
 * Class representing ContractsAType
 */
class ContractsAType
{

    /**
     * Информация о контракте и услугах в рамках контракта
     *
     * @property \common\models\sbbolxml\response\ContractType[] $contract
     */
    private $contract = array(
        
    );

    /**
     * Adds as contract
     *
     * Информация о контракте и услугах в рамках контракта
     *
     * @return static
     * @param \common\models\sbbolxml\response\ContractType $contract
     */
    public function addToContract(\common\models\sbbolxml\response\ContractType $contract)
    {
        $this->contract[] = $contract;
        return $this;
    }

    /**
     * isset contract
     *
     * Информация о контракте и услугах в рамках контракта
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetContract($index)
    {
        return isset($this->contract[$index]);
    }

    /**
     * unset contract
     *
     * Информация о контракте и услугах в рамках контракта
     *
     * @param scalar $index
     * @return void
     */
    public function unsetContract($index)
    {
        unset($this->contract[$index]);
    }

    /**
     * Gets as contract
     *
     * Информация о контракте и услугах в рамках контракта
     *
     * @return \common\models\sbbolxml\response\ContractType[]
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Sets a new contract
     *
     * Информация о контракте и услугах в рамках контракта
     *
     * @param \common\models\sbbolxml\response\ContractType[] $contract
     * @return static
     */
    public function setContract(array $contract)
    {
        $this->contract = $contract;
        return $this;
    }


}

