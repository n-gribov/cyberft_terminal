<?php
namespace common\rbac\rules;

use Yii;
use yii\base\Exception;
use yii\rbac\Rule;
use common\models\CommonUserExt;
use common\models\User;

/**
 * Правило проверяет, может ли обычный
 * пользователь управлять страницами документации (wiki)
 * Class PageManagementPermissionRule
 * @package common\rbac\rules
 */

class PageManagementPermissionRule extends Rule
{
    public $name = 'pageManagementRule';

    public function execute($userId, $item, $params)
    {
        // Если пользователь не идентифицирован,
        // дальнейшее не имеет смысла
        if (empty(Yii::$app->user) || empty(Yii::$app->user->identity)) {
            return false;
        }

        /**
         * Главный администратор может
         * управлять страницами документации
         */
        if (Yii::$app->user->identity->role == User::ROLE_ADMIN) {
            return true;
        }

        try {
            // Проверяем возможность пользователя
            // управлять виджетами документации
            $userSetting = CommonUserExt::findOne([
                'type' => CommonUserExt::DOCUMENTATION_WIDGETS,
                'userId' => $userId,
                'canAccess' => 1
            ]);

            return !empty($userSetting);

        } catch (Exception $ex) {

            return false;
        }

    }
}