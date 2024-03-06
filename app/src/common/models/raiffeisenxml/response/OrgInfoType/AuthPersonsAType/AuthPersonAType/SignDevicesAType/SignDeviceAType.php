<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType;

/**
 * Class representing SignDeviceAType
 */
class SignDeviceAType
{

    /**
     * Идентификатор средства подписи
     *
     * @property string $signDeviceId
     */
    private $signDeviceId = null;

    /**
     * Gets as signDeviceId
     *
     * Идентификатор средства подписи
     *
     * @return string
     */
    public function getSignDeviceId()
    {
        return $this->signDeviceId;
    }

    /**
     * Sets a new signDeviceId
     *
     * Идентификатор средства подписи
     *
     * @param string $signDeviceId
     * @return static
     */
    public function setSignDeviceId($signDeviceId)
    {
        $this->signDeviceId = $signDeviceId;
        return $this;
    }


}

