<?php

namespace common\models\raiffeisenxml\response\OrgInfoType;

/**
 * Class representing ServicePackagesAType
 */
class ServicePackagesAType
{

    /**
     * Пакет услуг
     *
     * @property \common\models\raiffeisenxml\response\ServicePackageType[] $servicePackage
     */
    private $servicePackage = [
        
    ];

    /**
     * Adds as servicePackage
     *
     * Пакет услуг
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ServicePackageType $servicePackage
     */
    public function addToServicePackage(\common\models\raiffeisenxml\response\ServicePackageType $servicePackage)
    {
        $this->servicePackage[] = $servicePackage;
        return $this;
    }

    /**
     * isset servicePackage
     *
     * Пакет услуг
     *
     * @param int|string $index
     * @return bool
     */
    public function issetServicePackage($index)
    {
        return isset($this->servicePackage[$index]);
    }

    /**
     * unset servicePackage
     *
     * Пакет услуг
     *
     * @param int|string $index
     * @return void
     */
    public function unsetServicePackage($index)
    {
        unset($this->servicePackage[$index]);
    }

    /**
     * Gets as servicePackage
     *
     * Пакет услуг
     *
     * @return \common\models\raiffeisenxml\response\ServicePackageType[]
     */
    public function getServicePackage()
    {
        return $this->servicePackage;
    }

    /**
     * Sets a new servicePackage
     *
     * Пакет услуг
     *
     * @param \common\models\raiffeisenxml\response\ServicePackageType[] $servicePackage
     * @return static
     */
    public function setServicePackage(array $servicePackage)
    {
        $this->servicePackage = $servicePackage;
        return $this;
    }


}

