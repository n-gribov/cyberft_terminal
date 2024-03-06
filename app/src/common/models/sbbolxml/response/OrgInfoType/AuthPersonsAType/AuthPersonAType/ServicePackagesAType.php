<?php

namespace common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType;

/**
 * Class representing ServicePackagesAType
 */
class ServicePackagesAType
{

    /**
     * Пакет услуг
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\ServicePackagesAType\ServicePackageAType[] $servicePackage
     */
    private $servicePackage = array(
        
    );

    /**
     * Adds as servicePackage
     *
     * Пакет услуг
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\ServicePackagesAType\ServicePackageAType $servicePackage
     */
    public function addToServicePackage(\common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\ServicePackagesAType\ServicePackageAType $servicePackage)
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
     * @return \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\ServicePackagesAType\ServicePackageAType[]
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
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\ServicePackagesAType\ServicePackageAType[] $servicePackage
     * @return static
     */
    public function setServicePackage(array $servicePackage)
    {
        $this->servicePackage = $servicePackage;
        return $this;
    }


}

