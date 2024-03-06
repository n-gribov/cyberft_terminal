<?php

namespace common\models\raiffeisenxml\response\OrgInfoType;

/**
 * Class representing SignDevicesAType
 */
class SignDevicesAType
{

    /**
     * Информация о средстве подписи
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType[] $signDevice
     */
    private $signDevice = [
        
    ];

    /**
     * Adds as signDevice
     *
     * Информация о средстве подписи
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType $signDevice
     */
    public function addToSignDevice(\common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType $signDevice)
    {
        $this->signDevice[] = $signDevice;
        return $this;
    }

    /**
     * isset signDevice
     *
     * Информация о средстве подписи
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSignDevice($index)
    {
        return isset($this->signDevice[$index]);
    }

    /**
     * unset signDevice
     *
     * Информация о средстве подписи
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSignDevice($index)
    {
        unset($this->signDevice[$index]);
    }

    /**
     * Gets as signDevice
     *
     * Информация о средстве подписи
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType[]
     */
    public function getSignDevice()
    {
        return $this->signDevice;
    }

    /**
     * Sets a new signDevice
     *
     * Информация о средстве подписи
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType[] $signDevice
     * @return static
     */
    public function setSignDevice(array $signDevice)
    {
        $this->signDevice = $signDevice;
        return $this;
    }


}

