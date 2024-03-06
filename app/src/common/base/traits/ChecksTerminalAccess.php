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
        $userIdentity = Yii::$app->user->identity;
        if ($userIdentity->role == User::ROLE_ADDITIONAL_ADMIN) {
            $availableTerminals = UserTerminal::getUserTerminalIds($userIdentity->id);
            if (!isset($availableTerminals[$terminalId])) {
                throw new ForbiddenHttpException();
            }
        } elseif ($userIdentity->role != User::ROLE_ADMIN) {
            throw new ForbiddenHttpException();
        }
    }
}
