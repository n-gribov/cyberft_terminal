<?php
namespace common\commands\UserSettingApprove;

use common\commands\BaseHandler;
use common\commands\UserActivateCommand;
use common\models\User;

class UserSettingApproveHandler extends BaseHandler
{
    public function perform($command)
    {
        if ($command instanceof UserSettingApproveCommand) {
            $user = User::findOne($command->entityId);

            if (is_null($user)) {
                $this->log('Wrong user ID');
                return false;
            }

            if (
                    ($user->role == User::ROLE_LSO && User::getNotDeletedRoleCount(User::ROLE_LSO) > 0)
                 ||
                    ($user->role == User::ROLE_RSO && User::getNotDeletedRoleCount(User::ROLE_RSO) > 0)
            ) {
                $this->log('Cannot add more security officers!');
                return false;
            }

            if (!$user->updateStatus(User::STATUS_ACTIVE)) {
                $this->log('Save user status failure!');
                return false;
            }
        } else {
            $this->log('Command not instance of UserActivateCommand!');
            return false;
        }

        return [];
    }
}
