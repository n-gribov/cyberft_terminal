<?php
namespace common\rbac\rules;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\rbac\Rule;
use common\models\CommonUserExt;

class CertStatusManagementPermissionRule extends Rule
{
    public $name = 'certStatusManagementRule';

    public function execute($userId, $item, $params)
    {
        // Если пользователь не идентифицирован,
        // дальнейшее не имеет смысла
        if (empty(Yii::$app->user) || empty(Yii::$app->user->identity)) {
            return false;
        }

        if (Yii::$app->user->identity->role == User::ROLE_ADMIN) {
            return true;
        }

        try {
            // Проверяем возможность пользователя
            // управлять статусом сертификатов
            $userSetting = CommonUserExt::findOne([
                'type' => CommonUserExt::CERTIFICATES,
                'userId' => $userId,
                'canAccess' => 1
            ]);

            return $userSetting;

        } catch (Exception $ex) {

            return false;
        }

        return false;
    }
}