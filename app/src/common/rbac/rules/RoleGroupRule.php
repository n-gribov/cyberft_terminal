<?php
namespace common\rbac\rules;
use common\models\User;
use Yii;
use yii\rbac\Rule;

/**
 * Checks if user group matches
 *
 * @author fuzz
 */
class RoleGroupRule extends Rule
{
    public $name = 'roleGroup';

    public function execute($userId, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            // Получить роль пользователя из активной сессии
            $role = Yii::$app->user->identity->role;
            if ($item->name === 'admin') {
                return $role == User::ROLE_ADMIN;
            } else if ($item->name === 'user') {
                return $role == User::ROLE_USER;
            } else if ($item->name === 'lso') {
                return $role == User::ROLE_LSO;
            } else if ($item->name === 'rso') {
                return $role == User::ROLE_RSO;
            } else if ($item->name === 'controller') {
                return $role == User::ROLE_CONTROLLER;
            } else if ($item->name === 'additionalAdmin') {
                return $role == User::ROLE_ADDITIONAL_ADMIN;
            }
        }

        return false;
    }
}
