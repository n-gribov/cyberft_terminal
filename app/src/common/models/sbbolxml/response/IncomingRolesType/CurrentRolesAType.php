<?php

namespace common\models\sbbolxml\response\IncomingRolesType;

/**
 * Class representing CurrentRolesAType
 */
class CurrentRolesAType
{

    /**
     * @property \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType[] $rolePermission
     */
    private $rolePermission = array(
        
    );

    /**
     * Adds as rolePermission
     *
     * @return static
     * @param \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType $rolePermission
     */
    public function addToRolePermission(\common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType $rolePermission)
    {
        $this->rolePermission[] = $rolePermission;
        return $this;
    }

    /**
     * isset rolePermission
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetRolePermission($index)
    {
        return isset($this->rolePermission[$index]);
    }

    /**
     * unset rolePermission
     *
     * @param scalar $index
     * @return void
     */
    public function unsetRolePermission($index)
    {
        unset($this->rolePermission[$index]);
    }

    /**
     * Gets as rolePermission
     *
     * @return \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType[]
     */
    public function getRolePermission()
    {
        return $this->rolePermission;
    }

    /**
     * Sets a new rolePermission
     *
     * @param \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType[] $rolePermission
     * @return static
     */
    public function setRolePermission(array $rolePermission)
    {
        $this->rolePermission = $rolePermission;
        return $this;
    }


}

