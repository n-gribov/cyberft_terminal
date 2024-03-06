<?php
namespace common\rbac\rules;

use common\models\User;
use Yii;
use yii\rbac\Rule;

class AccessServiceRule extends Rule
{
    public $name = 'canAccessService';

    public function execute($userId, $item, $params)
    {
        if (empty(Yii::$app->user->identity)) {
            return false;
        }

        // Получить модель пользователя из активной сессии
        $user = Yii::$app->user->identity;

        if (User::ROLE_ADMIN === $user->role
            || User::ROLE_LSO === $user->role
            || User::ROLE_RSO === $user->role
            || User::ROLE_ADDITIONAL_ADMIN === $user->role) {

            return true;
        } else {
            // Кроме доступности пользователю конкретных сервисов,
            // проверяем доступность терминалов

            if ($extModel = $user->getServiceExtModel($params['serviceId'])) {
                $serviceAccess = $extModel->isAllowedAccess();

                return $serviceAccess && $user->terminals;
            }
        }

        return false;
    }
}
