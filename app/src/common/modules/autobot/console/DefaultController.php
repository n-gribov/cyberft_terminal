<?php
namespace common\modules\autobot\console;

use common\base\ConsoleController as ConsoleController;


class DefaultController extends ConsoleController
{
    public function actionIndex()
    {
        $this->run('/help', ['autobot']);
        return ConsoleController::EXIT_CODE_NORMAL;
    }
}