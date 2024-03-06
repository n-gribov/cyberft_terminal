<?php

namespace common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType;

/**
 * Class representing RolePermissionAType
 *
 * Наименование роли в СББОЛ
 */
class RolePermissionAType
{

    /**
     * @property string $role
     */
    private $role = null;

    /**
     * @property string $description
     */
    private $description = null;

    /**
     * @property \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType\PermissionsAType\PermissionAType[] $permissions
     */
    private $permissions = null;

    /**
     * Gets as role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Sets a new role
     *
     * @param string $role
     * @return static
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Gets as description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * @param string $description
     * @return static
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Adds as permission
     *
     * @return static
     * @param \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType\PermissionsAType\PermissionAType $permission
     */
    public function addToPermissions(\common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType\PermissionsAType\PermissionAType $permission)
    {
        $this->permissions[] = $permission;
        return $this;
    }

    /**
     * isset permissions
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPermissions($index)
    {
        return isset($this->permissions[$index]);
    }

    /**
     * unset permissions
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPermissions($index)
    {
        unset($this->permissions[$index]);
    }

    /**
     * Gets as permissions
     *
     * @return \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType\PermissionsAType\PermissionAType[]
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Sets a new permissions
     *
     * @param \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType\PermissionsAType\PermissionAType[] $permissions
     * @return static
     */
    public function setPermissions(array $permissions)
    {
        $this->permissions = $permissions;
        return $this;
    }


}

