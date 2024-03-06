<?php
namespace common\rbac\rules;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\rbac\Rule;

class MainAdminPermissionRule extends Rule
{
    public $name = 'mainAdminRule';

    /**
     * Правило определяет возможность доступа
     * к данным доступным для главного администратора
     * @param int|string $userId
     * @param \yii\rbac\Item $item
     * @param array $params
     * @return bool
     */
    public function execute($userId, $item, $params)
    {
        // Если пользователь не идентифицирован, дальнейшее не имеет смысла
        if (empty(Yii::$app->user) || empty(Yii::$app->user->identity)) {
            return false;
        }

        try {
            // Проверяем, является ли текущий пользователь главным администратором
            return Yii::$app->user->identity->role == User::ROLE_ADMIN;
        } catch (Exception $e) {
            return false;
        }

        return false;
    }
}
