<?php

namespace common\models\sbbolxml\request\AdmCashierType;

/**
 * Class representing DeviceInfoAType
 */
class DeviceInfoAType
{

    /**
     * Устройства самообслуживания: ADM - "Устройства самообслуживания на территории Клиента (АДМ)", US - "Устройство самообслуживания (УС)"
     *
     * @property string $deviceType
     */
    private $deviceType = null;

    /**
     * Торговая точка для устройства самообслуживания
     *
     * @property string $tradePointName
     */
    private $tradePointName = null;

    /**
     * Gets as deviceType
     *
     * Устройства самообслуживания: ADM - "Устройства самообслуживания на территории Клиента (АДМ)", US - "Устройство самообслуживания (УС)"
     *
     * @return string
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Sets a new deviceType
     *
     * Устройства самообслуживания: ADM - "Устройства самообслуживания на территории Клиента (АДМ)", US - "Устройство самообслуживания (УС)"
     *
     * @param string $deviceType
     * @return static
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;
        return $this;
    }

    /**
     * Gets as tradePointName
     *
     * Торговая точка для устройства самообслуживания
     *
     * @return string
     */
    public function getTradePointName()
    {
        return $this->tradePointName;
    }

    /**
     * Sets a new tradePointName
     *
     * Торговая точка для устройства самообслуживания
     *
     * @param string $tradePointName
     * @return static
     */
    public function setTradePointName($tradePointName)
    {
        $this->tradePointName = $tradePointName;
        return $this;
    }


}

