<?php

namespace common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType;

/**
 * Class representing ServicePacksAType
 */
class ServicePacksAType
{

    /**
     * Пакет услуг
     *
     * @property \common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType[] $servicePack
     */
    private $servicePack = array(
        
    );

    /**
     * Adds as servicePack
     *
     * Пакет услуг
     *
     * @return static
     * @param \common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType $servicePack
     */
    public function addToServicePack(\common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType $servicePack)
    {
        $this->servicePack[] = $servicePack;
        return $this;
    }

    /**
     * isset servicePack
     *
     * Пакет услуг
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType[]
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
     * @param \common\models\sbbolxml\response\ContractType\ContractServicesAType\ContractServiceAType\ServicePacksAType\ServicePackAType[] $servicePack
     * @return static
     */
    public function setServicePack(array $servicePack)
    {
        $this->servicePack = $servicePack;
        return $this;
    }


}

