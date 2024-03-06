<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing ContractType
 *
 *
 * XSD Type: Contract
 */
class ContractType
{

    /**
     * Информация о контракте
     *
     * @property \common\models\raiffeisenxml\response\ContractInfoType $contractInfo
     */
    private $contractInfo = null;

    /**
     * Услуги по контракту
     *
     * @property \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType[] $contractServices
     */
    private $contractServices = null;

    /**
     * Gets as contractInfo
     *
     * Информация о контракте
     *
     * @return \common\models\raiffeisenxml\response\ContractInfoType
     */
    public function getContractInfo()
    {
        return $this->contractInfo;
    }

    /**
     * Sets a new contractInfo
     *
     * Информация о контракте
     *
     * @param \common\models\raiffeisenxml\response\ContractInfoType $contractInfo
     * @return static
     */
    public function setContractInfo(\common\models\raiffeisenxml\response\ContractInfoType $contractInfo)
    {
        $this->contractInfo = $contractInfo;
        return $this;
    }

    /**
     * Adds as contractService
     *
     * Услуги по контракту
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType $contractService
     */
    public function addToContractServices(\common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType $contractService)
    {
        $this->contractServices[] = $contractService;
        return $this;
    }

    /**
     * isset contractServices
     *
     * Услуги по контракту
     *
     * @param int|string $index
     * @return bool
     */
    public function issetContractServices($index)
    {
        return isset($this->contractServices[$index]);
    }

    /**
     * unset contractServices
     *
     * Услуги по контракту
     *
     * @param int|string $index
     * @return void
     */
    public function unsetContractServices($index)
    {
        unset($this->contractServices[$index]);
    }

    /**
     * Gets as contractServices
     *
     * Услуги по контракту
     *
     * @return \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType[]
     */
    public function getContractServices()
    {
        return $this->contractServices;
    }

    /**
     * Sets a new contractServices
     *
     * Услуги по контракту
     *
     * @param \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType[] $contractServices
     * @return static
     */
    public function setContractServices(array $contractServices)
    {
        $this->contractServices = $contractServices;
        return $this;
    }


}

