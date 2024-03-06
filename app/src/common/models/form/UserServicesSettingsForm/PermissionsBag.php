<?php

namespace common\models\form\UserServicesSettingsForm;

use common\document\DocumentPermission;

class PermissionsBag
{
    private $permissions;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->permissions = [];
        foreach (DocumentPermission::all() as $permissionCode) {
            $this->permissions[$permissionCode] = [];
        }
    }

    public function put(string $permissionCode, string $value)
    {
        $this->permissions[$permissionCode][] = $value;
    }

    public function values(string $permissionCode): array
    {
        return $this->permissions[$permissionCode];
    }

    public function has(string $permissionCode, string $value): bool
    {
        return in_array($value, $this->values($permissionCode));
    }

    public function getPermissionsByValue($value): array
    {
        return array_values(
            array_filter(
                DocumentPermission::all(),
                function ($permissionCode) use ($value) {
                    return $this->has($permissionCode, $value);
                }
            )
        );
    }
}
