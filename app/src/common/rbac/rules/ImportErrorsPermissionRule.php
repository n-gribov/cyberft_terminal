<?php
namespace common\rbac\rules;

use Yii;
use yii\base\Exception;
use yii\rbac\Rule;
use common\models\CommonUserExt;
use common\models\User;

/**
 * Правило проверяет, имеет ли обычный
 * пользователь доступ к журналу ошибок импорта документов
 * Class PageManagementPermissionRule
 * @package common\rbac\rules
 */
class ImportErrorsPermissionRule extends Rule
{
    public $name = 'importErrorsRule';

    public function execute($userId, $item, $params)
    {
        // Если пользователь не идентифицирован, дальнейшее не имеет смысла
        if (empty(Yii::$app->user) || empty(Yii::$app->user->identity)) {
            return false;
        }

        // Администратор по умолчанию имеет доступ
        if (Yii::$app->user->identity->role == User::ROLE_ADMIN) {
            return true;
        }

        try {
            // Проверяем доступ пользователя
            $userSetting = CommonUserExt::findOne([
                'type' => CommonUserExt::IMPORT_ERRORS_JOURNAL,
                'userId' => $userId,
                'canAccess' => 1
            ]);

            return !empty($userSetting);

        } catch (Exception $ex) {
            return false;
        }
    }
}