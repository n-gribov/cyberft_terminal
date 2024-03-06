<?php

namespace common\models\raiffeisenxml\response\ContractType;

/**
 * Class representing ContractServicesAType
 */
class ContractServicesAType
{

    /**
     * Услуга по контракту
     *
     * @property \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType[] $contractService
     */
    private $contractService = [
        
    ];

    /**
     * Adds as contractService
     *
     * Услуга по контракту
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType $contractService
     */
    public function addToContractService(\common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType $contractService)
    {
        $this->contractService[] = $contractService;
        return $this;
    }

    /**
     * isset contractService
     *
     * Услуга по контракту
     *
     * @param int|string $index
     * @return bool
     */
    public function issetContractService($index)
    {
        return isset($this->contractService[$index]);
    }

    /**
     * unset contractService
     *
     * Услуга по контракту
     *
     * @param int|string $index
     * @return void
     */
    public function unsetContractService($index)
    {
        unset($this->contractService[$index]);
    }

    /**
     * Gets as contractService
     *
     * Услуга по контракту
     *
     * @return \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType[]
     */
    public function getContractService()
    {
        return $this->contractService;
    }

    /**
     * Sets a new contractService
     *
     * Услуга по контракту
     *
     * @param \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType[] $contractService
     * @return static
     */
    public function setContractService(array $contractService)
    {
        $this->contractService = $contractService;
        return $this;
    }


}

