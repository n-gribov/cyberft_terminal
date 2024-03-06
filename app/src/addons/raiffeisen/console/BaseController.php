<?php

namespace addons\raiffeisen\console;

use addons\raiffeisen\RaiffeisenModule;
use common\base\ConsoleController;

/**
 * @property RaiffeisenModule $module
 */
abstract class BaseController extends ConsoleController
{
    public function beforeAction($action)
    {
        $parentResult = parent::beforeAction($action);
        if (!$parentResult) {
            return false;
        }

        return $this->checkOsUser('www-data');
    }

    protected function checkOsUser($requiredUserLogin)
    {
        $userLogin = trim(shell_exec('whoami'));
        if ($userLogin !== $requiredUserLogin) {
            echo "Please, run this command as $requiredUserLogin\n";
            return false;
        }

        return true;
    }
}
