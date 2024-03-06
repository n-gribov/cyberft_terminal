<?php

namespace common\models\sbbolxml\response\OrganizationInfoType;

/**
 * Class representing ServicePackagesAType
 */
class ServicePackagesAType
{

    /**
     * Пакет услуг
     *
     * @property \common\models\sbbolxml\response\ServicePackageType[] $servicePackage
     */
    private $servicePackage = array(
        
    );

    /**
     * Adds as servicePackage
     *
     * Пакет услуг
     *
     * @return static
     * @param \common\models\sbbolxml\response\ServicePackageType $servicePackage
     */
    public function addToServicePackage(\common\models\sbbolxml\response\ServicePackageType $servicePackage)
    {
        $this->servicePackage[] = $servicePackage;
        return $this;
    }

    /**
     * isset servicePackage
     *
     * Пакет услуг
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\response\ServicePackageType[]
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
     * @param \common\models\sbbolxml\response\ServicePackageType[] $servicePackage
     * @return static
     */
    public function setServicePackage(array $servicePackage)
    {
        $this->servicePackage = $servicePackage;
        return $this;
    }


}

