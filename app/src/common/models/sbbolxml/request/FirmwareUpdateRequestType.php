<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing FirmwareUpdateRequestType
 *
 *
 * XSD Type: FirmwareUpdateRequest
 */
class FirmwareUpdateRequestType
{

    /**
     * Конфигурация токена (IC0_A16P0000L_C1_VT397)
     *
     * @property string $deviceConfig
     */
    private $deviceConfig = null;

    /**
     * Build прошивки
     *
     * @property integer $build
     */
    private $build = null;

    /**
     * Gets as deviceConfig
     *
     * Конфигурация токена (IC0_A16P0000L_C1_VT397)
     *
     * @return string
     */
    public function getDeviceConfig()
    {
        return $this->deviceConfig;
    }

    /**
     * Sets a new deviceConfig
     *
     * Конфигурация токена (IC0_A16P0000L_C1_VT397)
     *
     * @param string $deviceConfig
     * @return static
     */
    public function setDeviceConfig($deviceConfig)
    {
        $this->deviceConfig = $deviceConfig;
        return $this;
    }

    /**
     * Gets as build
     *
     * Build прошивки
     *
     * @return integer
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * Sets a new build
     *
     * Build прошивки
     *
     * @param integer $build
     * @return static
     */
    public function setBuild($build)
    {
        $this->build = $build;
        return $this;
    }


}

