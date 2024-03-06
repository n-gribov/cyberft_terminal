<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType;

/**
 * Class representing SignDeviceAType
 */
class SignDeviceAType
{

    /**
     * Идентификатор криптопрофиля
     *
     * @property string $signDeviceId
     */
    private $signDeviceId = null;

    /**
     * Gets as signDeviceId
     *
     * Идентификатор криптопрофиля
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
     * Идентификатор криптопрофиля
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

