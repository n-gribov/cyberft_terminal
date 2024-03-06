<?php

namespace common\models\sbbolxml\response\OrgInfoType;

/**
 * Class representing SignDevicesAType
 */
class SignDevicesAType
{

    /**
     * Информация о средстве подписи
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType[] $signDevice
     */
    private $signDevice = array(
        
    );

    /**
     * Adds as signDevice
     *
     * Информация о средстве подписи
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType $signDevice
     */
    public function addToSignDevice(\common\models\sbbolxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType $signDevice)
    {
        $this->signDevice[] = $signDevice;
        return $this;
    }

    /**
     * isset signDevice
     *
     * Информация о средстве подписи
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType[]
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
     * @param \common\models\sbbolxml\response\OrgInfoType\SignDevicesAType\SignDeviceAType[] $signDevice
     * @return static
     */
    public function setSignDevice(array $signDevice)
    {
        $this->signDevice = $signDevice;
        return $this;
    }


}

