<?php

namespace common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType;

/**
 * Class representing PermissionsAType
 *
 * Права роли
 */
class PermissionsAType
{

    /**
     * @property \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType\PermissionsAType\PermissionAType[] $permission
     */
    private $permission = array(
        
    );

    /**
     * Adds as permission
     *
     * @return static
     * @param \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType\PermissionsAType\PermissionAType $permission
     */
    public function addToPermission(\common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType\PermissionsAType\PermissionAType $permission)
    {
        $this->permission[] = $permission;
        return $this;
    }

    /**
     * isset permission
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPermission($index)
    {
        return isset($this->permission[$index]);
    }

    /**
     * unset permission
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPermission($index)
    {
        unset($this->permission[$index]);
    }

    /**
     * Gets as permission
     *
     * @return \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType\PermissionsAType\PermissionAType[]
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Sets a new permission
     *
     * @param \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType\PermissionsAType\PermissionAType[] $permission
     * @return static
     */
    public function setPermission(array $permission)
    {
        $this->permission = $permission;
        return $this;
    }


}

