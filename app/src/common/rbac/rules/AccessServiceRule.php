<?php
namespace common\rbac\rules;

use common\models\User;
use Yii;
use yii\rbac\Rule;

class AccessServiceRule extends Rule
{
	public $name = 'canAccessService';

	public function execute($user, $item, $params)
	{
		if (empty(Yii::$app->user->identity)) {
			return false;
		}

        $userIdentity = Yii::$app->user->identity;

        if (User::ROLE_ADMIN === $userIdentity->role
            || User::ROLE_LSO === $userIdentity->role
            || User::ROLE_RSO === $userIdentity->role
            || User::ROLE_ADDITIONAL_ADMIN === $userIdentity->role) {
            
            return true;
        } else {
            // Кроме доступности пользователю конкретных сервисов,
            // проверяем доступность терминалов

            if ($extModel = $userIdentity->getServiceExtModel($params['serviceId'])) {
                $serviceAccess = $extModel->isAllowedAccess();

                return $serviceAccess && $userIdentity->terminals;
            }
        }

        return false;
	}
}
