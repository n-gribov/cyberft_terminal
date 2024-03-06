<?php

namespace common\models\sbbolxml\response\ServicePackageType;

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
     * Признак доступности услуги в режиме Stand-in 99,99
     *
     * @property boolean $availableInStandIn9999
     */
    private $availableInStandIn9999 = null;

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

    /**
     * Gets as availableInStandIn9999
     *
     * Признак доступности услуги в режиме Stand-in 99,99
     *
     * @return boolean
     */
    public function getAvailableInStandIn9999()
    {
        return $this->availableInStandIn9999;
    }

    /**
     * Sets a new availableInStandIn9999
     *
     * Признак доступности услуги в режиме Stand-in 99,99
     *
     * @param boolean $availableInStandIn9999
     * @return static
     */
    public function setAvailableInStandIn9999($availableInStandIn9999)
    {
        $this->availableInStandIn9999 = $availableInStandIn9999;
        return $this;
    }


}

