<?php

namespace common\models\form\UserServicesSettingsForm;

use common\document\DocumentPermission;
use yii\helpers\Url;

class ServiceItem extends Item
{
    private $isCollapsible;
    private $isCollapsed;

    public function init()
    {
        parent::init();
        $documentTypeGroups = $this->module->getDocumentTypeGroups();
        $this->isCollapsible = count($documentTypeGroups) > 0;
        $this->isCollapsed = $this->isCollapsible && $this->userHasNoDocumentTypeGroupPermissionGranted();
    }

    public function getId(): string
    {
        return $this->module::SERVICE_ID;
    }

    public function getName(): string
    {
        return $this->module->getName();
    }

    public function createGridViewRowOptions(): array
    {
        return [];
    }

    protected function isPermissionAvailable($permissionCode): bool
    {
        if ($permissionCode === DocumentPermission::VIEW) {
            return true;
        }

        return $this->userExt->isAllowedAccess();
    }

    protected function isPermissionGrantable($permissionCode): bool
    {
        return true;
    }

    protected function isPermissionGranted($permissionCode): bool
    {
        return $this->userExt->hasPermission($permissionCode);
    }

    public function getAdditionalSettingsUrl()
    {
        return $this->userExt->hasAdditionalSettings() && $this->userExt->isAllowedAccess()
            ? Url::toRoute([$this->module::SERVICE_ID . '/user-ext', 'id' => $this->userId])
            : null;
    }

    public function isCollapsible(): bool
    {
        return $this->isCollapsible;
    }

    public function isCollapsed(): bool
    {
        return $this->isCollapsed;
    }

    private function userHasNoDocumentTypeGroupPermissionGranted()
    {
        foreach ($this->module->getDocumentTypeGroups() as $documentTypeGroup) {
            foreach (DocumentPermission::all() as $permissionCode) {
                if ($this->userExt->hasPermission("$permissionCode:{$documentTypeGroup->id}")) {
                    return false;
                }
            }
        }

        return true;
    }
}
