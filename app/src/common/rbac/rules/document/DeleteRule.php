<?php

namespace common\rbac\rules\document;

use common\document\DocumentPermission;
use common\models\User;

class DeleteRule extends Rule
{
    public $name = 'documentDeleteRule';

    protected function isAllowed($serviceId, $document, $documentTypeGroupCondition): bool
    {
        // Получить роль пользователя из активной сессии
        $isAllowedWithoutPermissionCheck = in_array(
            \Yii::$app->user->identity->role,
            [
                User::ROLE_ADMIN,
                User::ROLE_ADDITIONAL_ADMIN,
            ]
        );

        if ($isAllowedWithoutPermissionCheck) {
            return true;
        }

        return parent::isAllowed($serviceId, $document, $documentTypeGroupCondition);
    }   

    protected function getPermissionCode()
    {
        return DocumentPermission::DELETE;
    }
}
