<?php

namespace addons\edm\models;

use common\models\BaseUserExt;
use Yii;
use common\models\User;

/**
 * Class EdmUserExt
 * @package addons\edm\models
 * @property boolean $hideZeroTurnoverStatements
 * @property int $userId
 */
class EdmUserExt extends BaseUserExt
{
    public static function tableName()
    {
        return 'edm_UserExt';
    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [['hideZeroTurnoverStatements', 'boolean']]
        );
    }

    public function isAllowedAccess()
    {
        // Получить роль пользователя из активной сессии
        if (Yii::$app->user->identity->role === User::ROLE_CONTROLLER) {
            return true;
        }

        return (bool) $this->canAccess;
    }
}
