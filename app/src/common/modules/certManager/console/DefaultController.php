<?php
namespace common\modules\certManager\console;

use common\base\ConsoleController as ConsoleController;


class DefaultController extends ConsoleController
{
    public function actionIndex()
    {
        $this->run('/help', ['certManager']);
        return ConsoleController::EXIT_CODE_NORMAL;
    }
}