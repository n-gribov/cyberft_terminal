<?php

namespace common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType;

/**
 * Class representing ServicePacksAType
 */
class ServicePacksAType
{

    /**
     * Пакет услуг
     *
     * @property \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType[] $servicePack
     */
    private $servicePack = [
        
    ];

    /**
     * Adds as servicePack
     *
     * Пакет услуг
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType $servicePack
     */
    public function addToServicePack(\common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType $servicePack)
    {
        $this->servicePack[] = $servicePack;
        return $this;
    }

    /**
     * isset servicePack
     *
     * Пакет услуг
     *
     * @param int|string $index
     * @return bool
     */
    public function issetServicePack($index)
    {
        return isset($this->servicePack[$index]);
    }

    /**
     * unset servicePack
     *
     * Пакет услуг
     *
     * @param int|string $index
     * @return void
     */
    public function unsetServicePack($index)
    {
        unset($this->servicePack[$index]);
    }

    /**
     * Gets as servicePack
     *
     * Пакет услуг
     *
     * @return \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType[]
     */
    public function getServicePack()
    {
        return $this->servicePack;
    }

    /**
     * Sets a new servicePack
     *
     * Пакет услуг
     *
     * @param \common\models\raiffeisenxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType[] $servicePack
     * @return static
     */
    public function setServicePack(array $servicePack)
    {
        $this->servicePack = $servicePack;
        return $this;
    }


}

