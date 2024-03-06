<?php
namespace common\commands\UserActivate;

use common\commands\BaseCommand;
use common\models\User;

class UserActivateCommand extends BaseCommand
{
    public function getAcceptsCount()
    {
        if (is_null($this->_acceptsCount)) {
            $this->_acceptsCount = User::getNotDeletedSecureOfficersCount();
            if ($this->_acceptsCount < 2) {
                $this->_acceptsCount = 0;
            }
        }

        return $this->_acceptsCount;
    }
}
