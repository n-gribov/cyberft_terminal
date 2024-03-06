<?php

namespace common\commands\UserSetKeyAuth;

use common\commands\BaseCommand;
use common\commands\BaseHandler;
use common\models\User;

/**
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 */
class UserSetKeyAuthHandler extends BaseHandler
{
    /**
     * @inheritdoc
     */
    public function perform($command)
    {
        if ($command instanceof BaseCommand) {
            $user = User::findOne($command->applyUser);
            if (is_null($user)) {
                $this->log('Wrong user ID');
                return FALSE;
            }

            $user->authType = User::AUTH_TYPE_KEY;
            if (!$user->save()) {
                $this->log('Save user error');
                return FALSE;
            }
        } else {
            return false;
        }

        return [];
    }
}