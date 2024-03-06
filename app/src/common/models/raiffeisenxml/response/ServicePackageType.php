<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing ServicePackageType
 *
 *
 * XSD Type: ServicePackage
 */
class ServicePackageType
{

    /**
     * Идентификатор пакета услуг
     *
     * @property string $packageId
     */
    private $packageId = null;

    /**
     * Наименование пакета услуг
     *
     * @property string $packageName
     */
    private $packageName = null;

    /**
     * Информация об услуге в пакете услуг
     *
     * @property \common\models\raiffeisenxml\response\ServicePackageType\ServiceAType[] $service
     */
    private $service = [
        
    ];

    /**
     * Gets as packageId
     *
     * Идентификатор пакета услуг
     *
     * @return string
     */
    public function getPackageId()
    {
        return $this->packageId;
    }

    /**
     * Sets a new packageId
     *
     * Идентификатор пакета услуг
     *
     * @param string $packageId
     * @return static
     */
    public function setPackageId($packageId)
    {
        $this->packageId = $packageId;
        return $this;
    }

    /**
     * Gets as packageName
     *
     * Наименование пакета услуг
     *
     * @return string
     */
    public function getPackageName()
    {
        return $this->packageName;
    }

    /**
     * Sets a new packageName
     *
     * Наименование пакета услуг
     *
     * @param string $packageName
     * @return static
     */
    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;
        return $this;
    }

    /**
     * Adds as service
     *
     * Информация об услуге в пакете услуг
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ServicePackageType\ServiceAType $service
     */
    public function addToService(\common\models\raiffeisenxml\response\ServicePackageType\ServiceAType $service)
    {
        $this->service[] = $service;
        return $this;
    }

    /**
     * isset service
     *
     * Информация об услуге в пакете услуг
     *
     * @param int|string $index
     * @return bool
     */
    public function issetService($index)
    {
        return isset($this->service[$index]);
    }

    /**
     * unset service
     *
     * Информация об услуге в пакете услуг
     *
     * @param int|string $index
     * @return void
     */
    public function unsetService($index)
    {
        unset($this->service[$index]);
    }

    /**
     * Gets as service
     *
     * Информация об услуге в пакете услуг
     *
     * @return \common\models\raiffeisenxml\response\ServicePackageType\ServiceAType[]
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Sets a new service
     *
     * Информация об услуге в пакете услуг
     *
     * @param \common\models\raiffeisenxml\response\ServicePackageType\ServiceAType[] $service
     * @return static
     */
    public function setService(array $service)
    {
        $this->service = $service;
        return $this;
    }


}

