<?php

namespace common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType;

/**
 * Class representing SignDevicesAType
 */
class SignDevicesAType
{

    /**
     * Средство подписи
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\SignDevicesAType\SignDeviceAType[] $signDevice
     */
    private $signDevice = array(
        
    );

    /**
     * Все доступные средства
     *  подписи.
     *  0- признак НЕ активен;
     *  1- признак активен.
     *
     * @property boolean $all
     */
    private $all = null;

    /**
     * Adds as signDevice
     *
     * Средство подписи
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\SignDevicesAType\SignDeviceAType $signDevice
     */
    public function addToSignDevice(\common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\SignDevicesAType\SignDeviceAType $signDevice)
    {
        $this->signDevice[] = $signDevice;
        return $this;
    }

    /**
     * isset signDevice
     *
     * Средство подписи
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
     * Средство подписи
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
     * Средство подписи
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\SignDevicesAType\SignDeviceAType[]
     */
    public function getSignDevice()
    {
        return $this->signDevice;
    }

    /**
     * Sets a new signDevice
     *
     * Средство подписи
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\SignDevicesAType\SignDeviceAType[] $signDevice
     * @return static
     */
    public function setSignDevice(array $signDevice)
    {
        $this->signDevice = $signDevice;
        return $this;
    }

    /**
     * Gets as all
     *
     * Все доступные средства
     *  подписи.
     *  0- признак НЕ активен;
     *  1- признак активен.
     *
     * @return boolean
     */
    public function getAll()
    {
        return $this->all;
    }

    /**
     * Sets a new all
     *
     * Все доступные средства
     *  подписи.
     *  0- признак НЕ активен;
     *  1- признак активен.
     *
     * @param boolean $all
     * @return static
     */
    public function setAll($all)
    {
        $this->all = $all;
        return $this;
    }


}

