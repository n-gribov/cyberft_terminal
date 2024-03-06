<?php

namespace common\base\traits;

use common\models\User;
use common\models\UserTerminal;
use Yii;
use yii\web\ForbiddenHttpException;

trait ChecksTerminalAccess
{
    public function ensureUserHasTerminalAccess($terminalId)
    {
        // Получить модель пользователя из активной сессии
        $user = Yii::$app->user->identity;
        if ($user->role == User::ROLE_ADDITIONAL_ADMIN) {
            $availableTerminals = UserTerminal::getUserTerminalIds($user->id);
            if (!isset($availableTerminals[$terminalId])) {
                throw new ForbiddenHttpException();
            }
        } else if ($user->role != User::ROLE_ADMIN) {
            throw new ForbiddenHttpException();
        }
    }
}
