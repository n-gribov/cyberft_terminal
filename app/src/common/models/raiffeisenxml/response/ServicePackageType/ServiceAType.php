<?php

namespace common\models\raiffeisenxml\response\ServicePackageType;

/**
 * Class representing ServiceAType
 */
class ServiceAType
{

    /**
     * Идентификатор услуги в Corrects
     *
     * @property string $serviceId
     */
    private $serviceId = null;

    /**
     * Наименование услуги
     *
     * @property string $serviceName
     */
    private $serviceName = null;

    /**
     * Gets as serviceId
     *
     * Идентификатор услуги в Corrects
     *
     * @return string
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * Sets a new serviceId
     *
     * Идентификатор услуги в Corrects
     *
     * @param string $serviceId
     * @return static
     */
    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;
        return $this;
    }

    /**
     * Gets as serviceName
     *
     * Наименование услуги
     *
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * Sets a new serviceName
     *
     * Наименование услуги
     *
     * @param string $serviceName
     * @return static
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
        return $this;
    }


}

