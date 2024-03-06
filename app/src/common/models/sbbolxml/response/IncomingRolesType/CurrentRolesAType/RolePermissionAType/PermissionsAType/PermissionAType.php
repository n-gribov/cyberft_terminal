<?php

namespace common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType\PermissionsAType;

/**
 * Class representing PermissionAType
 */
class PermissionAType
{

    /**
     * Услуга, на которую выдан доступ (код услуги в СББОЛ)
     *
     * @property string $serviceCode
     */
    private $serviceCode = null;

    /**
     * Тип доступа:
     *  FULL_ACCESS
     *  READ_ONLY
     *
     * @property string $accessMode
     */
    private $accessMode = null;

    /**
     * Gets as serviceCode
     *
     * Услуга, на которую выдан доступ (код услуги в СББОЛ)
     *
     * @return string
     */
    public function getServiceCode()
    {
        return $this->serviceCode;
    }

    /**
     * Sets a new serviceCode
     *
     * Услуга, на которую выдан доступ (код услуги в СББОЛ)
     *
     * @param string $serviceCode
     * @return static
     */
    public function setServiceCode($serviceCode)
    {
        $this->serviceCode = $serviceCode;
        return $this;
    }

    /**
     * Gets as accessMode
     *
     * Тип доступа:
     *  FULL_ACCESS
     *  READ_ONLY
     *
     * @return string
     */
    public function getAccessMode()
    {
        return $this->accessMode;
    }

    /**
     * Sets a new accessMode
     *
     * Тип доступа:
     *  FULL_ACCESS
     *  READ_ONLY
     *
     * @param string $accessMode
     * @return static
     */
    public function setAccessMode($accessMode)
    {
        $this->accessMode = $accessMode;
        return $this;
    }


}

