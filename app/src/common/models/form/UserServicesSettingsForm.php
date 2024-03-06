<?php

namespace common\models\form;

use common\base\BaseBlock;
use common\base\interfaces\BlockInterface;
use common\document\DocumentPermission;
use common\document\DocumentTypeGroup;
use common\models\BaseUserExt;
use common\models\form\UserServicesSettingsForm\DocumentTypeGroupItem;
use common\models\form\UserServicesSettingsForm\PermissionsBag;
use common\models\form\UserServicesSettingsForm\ServiceItem;
use common\models\User;
use Yii;
use yii\base\BaseObject;
use yii\data\ArrayDataProvider;

class UserServicesSettingsForm extends BaseObject
{
    public $user;
    public $documentView = [];
    public $documentCreate = [];
    public $documentDelete = [];
    public $documentSign = [];

    private $items = [];

    /** @var PermissionsBag */
    private $permissionsBag;

    public function init()
    {
        parent::init();

        if (!$this->user) {
            throw new \Exception('User is not provided');
        }

        if ($this->user->role !== User::ROLE_USER) {
            return;
        }

        $this->permissionsBag = new PermissionsBag();

        $this->initializeItems();
    }

    private function initializeItems()
    {
        $this->items = [];
        $addons = Yii::$app->addon->getRegisteredAddons();

        foreach ($addons as $serviceId => $module) {
            /** @var BaseBlock $module */
            if (!$module->hasUserAccessSettings()) {
                continue;
            }

            $serviceItem = new ServiceItem([
                'module' => $module,
                'userId' => $this->user->id,
            ]);
            $this->items[] = $serviceItem;

            $documentTypeGroups = $module->getDocumentTypeGroups();
            foreach ($documentTypeGroups as $documentTypeGroup) {
                $this->addDocumentTypeGroupItem($module, $documentTypeGroup, $serviceItem);
            }
        }
    }

    private function addDocumentTypeGroupItem(BaseBlock $module, DocumentTypeGroup $documentTypeGroup, ServiceItem $parentItem)
    {
        $this->items[] = new DocumentTypeGroupItem([
            'module' => $module,
            'userId' => $this->user->id,
            'documentTypeGroup' => $documentTypeGroup,
            'parentItem' => $parentItem,
        ]);
    }

    public function load($data)
    {
        foreach (DocumentPermission::all() as $permissionCode) {
            if (!isset($data[$permissionCode]) || !is_array($data[$permissionCode])) {
                continue;
            }
            foreach ($data[$permissionCode] as $value) {
                $this->permissionsBag->put($permissionCode, $value);
            }
        }
        return true;
    }

    public function createItemsDataProvider()
    {
        return new ArrayDataProvider([
            'allModels' => $this->items,
            'key' => 'id',
            'pagination' => false,
        ]);
    }

    public function save()
    {
        $addons = Yii::$app->addon->getRegisteredAddons();
        foreach ($addons as $serviceId => $module) {
            /** @var BlockInterface $module */
            if (!$module->hasUserAccessSettings()) {
                continue;
            }

            /** @var BaseUserExt $userExt */
            $userExt = $module->getUserExtModel($this->user->id);
            if ($userExt->isNewRecord) {
                $userExt->userId = $this->user->id;
            }

            $hasServiceAccess = $this->permissionsBag->has(DocumentPermission::VIEW, $serviceId);
            $servicePermissions = $hasServiceAccess
                ? $this->permissionsBag->getPermissionsByValue($serviceId)
                : [];

            $documentTypeGroupPermissions = [];
            foreach ($module->getDocumentTypeGroups() as $documentTypeGroup) {
                $permissionValue = "$serviceId:{$documentTypeGroup->id}";
                $hasDocumentTypeGroupAccess =  $this->permissionsBag->has(DocumentPermission::VIEW, $permissionValue);
                if (!$hasDocumentTypeGroupAccess) {
                    continue;
                }

                $availablePermissions = $documentTypeGroup->availablePermissions;
                foreach ($availablePermissions as $permissionCode) {
                    // Уже есть право на все типы документов
                    if (in_array($permissionCode, $servicePermissions)) {
                        continue;
                    }
                    if ($this->permissionsBag->has($permissionCode, $permissionValue)) {
                        $documentTypeGroupPermissions[] = "$permissionCode:{$documentTypeGroup->id}";
                    }
                }
            }

            $allPermissions = array_merge($servicePermissions, $documentTypeGroupPermissions);

            $userExt->canAccess = !empty($allPermissions) ? 1 : 0;
            $userExt->permissions = $allPermissions;
            $userExt->save();
        }
    }
}
