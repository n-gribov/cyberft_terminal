<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing IncomingRolesType
 *
 * Роли
 * XSD Type: IncomingRoles
 */
class IncomingRolesType
{

    /**
     * Имеющиеся роли
     *
     * @property \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType[] $currentRoles
     */
    private $currentRoles = null;

    /**
     * Удаленные роли
     *
     * @property string[] $deletedRoles
     */
    private $deletedRoles = null;

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Adds as rolePermission
     *
     * Имеющиеся роли
     *
     * @return static
     * @param \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType $rolePermission
     */
    public function addToCurrentRoles(\common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType $rolePermission)
    {
        $this->currentRoles[] = $rolePermission;
        return $this;
    }

    /**
     * isset currentRoles
     *
     * Имеющиеся роли
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCurrentRoles($index)
    {
        return isset($this->currentRoles[$index]);
    }

    /**
     * unset currentRoles
     *
     * Имеющиеся роли
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCurrentRoles($index)
    {
        unset($this->currentRoles[$index]);
    }

    /**
     * Gets as currentRoles
     *
     * Имеющиеся роли
     *
     * @return \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType[]
     */
    public function getCurrentRoles()
    {
        return $this->currentRoles;
    }

    /**
     * Sets a new currentRoles
     *
     * Имеющиеся роли
     *
     * @param \common\models\sbbolxml\response\IncomingRolesType\CurrentRolesAType\RolePermissionAType[] $currentRoles
     * @return static
     */
    public function setCurrentRoles(array $currentRoles)
    {
        $this->currentRoles = $currentRoles;
        return $this;
    }

    /**
     * Adds as role
     *
     * Удаленные роли
     *
     * @return static
     * @param string $role
     */
    public function addToDeletedRoles($role)
    {
        $this->deletedRoles[] = $role;
        return $this;
    }

    /**
     * isset deletedRoles
     *
     * Удаленные роли
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDeletedRoles($index)
    {
        return isset($this->deletedRoles[$index]);
    }

    /**
     * unset deletedRoles
     *
     * Удаленные роли
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDeletedRoles($index)
    {
        unset($this->deletedRoles[$index]);
    }

    /**
     * Gets as deletedRoles
     *
     * Удаленные роли
     *
     * @return string[]
     */
    public function getDeletedRoles()
    {
        return $this->deletedRoles;
    }

    /**
     * Sets a new deletedRoles
     *
     * Удаленные роли
     *
     * @param string[] $deletedRoles
     * @return static
     */
    public function setDeletedRoles(array $deletedRoles)
    {
        $this->deletedRoles = $deletedRoles;
        return $this;
    }

    /**
     * Gets as sign
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

