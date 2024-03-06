<?php
namespace common\rbac\rules;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\rbac\Rule;
use common\models\CommonUserExt;

class CertsViewPermissionRule extends Rule
{
    public $name = 'certViewPermissionRule';

    public function execute($userId, $item, $params)
    {
        // Если пользователь не идентифицирован, дальнейшее не имеет смысла
        if (empty(Yii::$app->user) || empty(Yii::$app->user->identity)) {
            return false;
        }

        try {
            // Если пользователь имеет главный администратор, то ему доступно меню сертификатов
            if (Yii::$app->user->identity->role == User::ROLE_ADMIN) {
                return true;
            }

            // Если пользователь может управлять статусом сертификата, то ему доступно меню сертификатов

            // Проверяем возможность пользователя управлять статусом сертификатов
            $userSetting = CommonUserExt::findOne(['userId' => $userId, 'type' => CommonUserExt::CERTIFICATES]);

            // Если настройки управления сертификатами доступны и активны
            return $userSetting && $userSetting->canAccess;

        } catch (Exception $ex) {
            return false;
        }

        return false;
    }
}
