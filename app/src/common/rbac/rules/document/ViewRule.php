<?php

namespace common\rbac\rules\document;

use common\document\DocumentPermission;
use common\models\User;

class ViewRule extends Rule
{
    public $name = 'documentViewRule';

    protected function isAllowed($serviceId, $document, $documentTypeGroupCondition): bool
    {
        $isAllowedWithoutPermissionCheck = in_array(
            \Yii::$app->user->identity->role,
            [
                User::ROLE_ADMIN,
                User::ROLE_ADDITIONAL_ADMIN,
                User::ROLE_CONTROLLER,
                User::ROLE_LSO,
                User::ROLE_RSO,
            ]
        );

        if ($isAllowedWithoutPermissionCheck) {
            return true;
        }

        return parent::isAllowed($serviceId, $document, $documentTypeGroupCondition);
    }

    protected function getPermissionCode()
    {
        return DocumentPermission::VIEW;
    }
}
