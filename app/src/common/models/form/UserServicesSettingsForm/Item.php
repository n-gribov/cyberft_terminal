<?php

namespace common\models\form\UserServicesSettingsForm;

use common\base\BaseBlock;
use common\base\Model;
use common\models\BaseUserExt;
use Yii;

/**
 * @property string $id
 * @property string $name
 * @property mixed $additionalSettingsUrl
 */
abstract class Item extends Model
{
    /** @var BaseBlock */
    public $module;

    public $userId;

    /** @var BaseUserExt */
    protected $userExt;

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app/user', 'Service'),
            'additionalSettingsUrl' => Yii::t('app/user', 'Additional settings'),
        ];
    }

    public function init()
    {
        parent::init();

        if (!$this->module) {
            throw new \Exception('Addon module is not provided');
        }
        if (!$this->userId) {
            throw new \Exception('User id is not provided');
        }

        $this->userExt = $this->module->getUserExtModel($this->userId) ?: $userExt = $this->module->getUserExtModel();
    }

    public function createGridViewCheckboxOptions($permissionCode): array
    {
        $isAvailable = $this->isPermissionAvailable($permissionCode);
        return [
            'checked' => $isAvailable && $this->isPermissionGranted($permissionCode),
            'class' => !$isAvailable ? 'hidden' : '',
            'disabled' => !$isAvailable,
            'data' => [
                'is-grantable' => $this->isPermissionGrantable($permissionCode) ? 'true' : 'false',
            ],
        ];
    }

    abstract public function getId(): string;

    abstract public function getName(): string;

    abstract public function isCollapsible(): bool;

    abstract public function isCollapsed(): bool;

    abstract public function getAdditionalSettingsUrl();

    abstract public function createGridViewRowOptions(): array;

    abstract protected function isPermissionAvailable($permissionCode): bool;

    abstract protected function isPermissionGrantable($permissionCode): bool;

    abstract protected function isPermissionGranted($permissionCode): bool;
}
