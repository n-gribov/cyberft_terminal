<?php

namespace common\models\form\UserServicesSettingsForm;

use common\document\DocumentPermission;
use common\document\DocumentTypeGroup;

class DocumentTypeGroupItem extends Item
{
    /** @var DocumentTypeGroup */
    public $documentTypeGroup;

    /** @var ServiceItem */
    public $parentItem;

    public function init()
    {
        parent::init();

        if (!$this->documentTypeGroup) {
            throw new \Exception('Document type group is not provided');
        }
    }

    public function getId(): string
    {
        return $this->module::SERVICE_ID . ':' . $this->documentTypeGroup->id;
    }

    public function getName(): string
    {
        return $this->documentTypeGroup->name;
    }

    public function createGridViewRowOptions(): array
    {
        return [
            'class' => 'child-row ' . ($this->isCollapsed() ? 'collapse' : 'collapse in'),
            'data' => [
                'service-id' => $this->module::SERVICE_ID,
            ],
        ];
    }

    protected function isPermissionAvailable($permissionCode): bool
    {
        if ($permissionCode === DocumentPermission::VIEW) {
            return true;
        }

        if (!$this->userExt->isAllowedAccess()) {
            return false;
        }

        return $this->isPermissionGrantable($permissionCode)
            && $this->isPermissionGranted(DocumentPermission::VIEW);
    }

    protected function isPermissionGrantable($permissionCode): bool
    {
        return $this->documentTypeGroup->hasPermission($permissionCode);
    }

    protected function isPermissionGranted($permissionCode): bool
    {
        return $this->userExt->hasPermission($permissionCode . ':' . $this->documentTypeGroup->id)
            || $this->userExt->hasPermission($permissionCode);
    }

    public function getAdditionalSettingsUrl()
    {
        return null;
    }

    public function isCollapsible(): bool
    {
        return false;
    }

    public function isCollapsed(): bool
    {
        return $this->parentItem->isCollapsed();
    }
}
