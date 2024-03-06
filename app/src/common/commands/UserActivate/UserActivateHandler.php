<?php
namespace common\commands\UserActivate;

use common\commands\BaseHandler;
use common\commands\UserActivate\UserActivateCommand;
use common\models\User;

class UserActivateHandler extends BaseHandler
{
    public function perform($command)
    {
        if ($command instanceof UserActivateCommand) {
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
                $user->updateStatus(User::STATUS_INACTIVE);
                return false;
            }

            if (!User::canUseSecurityOfficers()) {
                $status = User::STATUS_ACTIVE;
            } else {
                $status = User::STATUS_ACTIVATED;
            }

            if (!$user->updateStatus($status)) {
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
