<?php

namespace common\modules\monitor\console;

use common\base\ConsoleController as ConsoleController;

/**
 * Main messaging transport module
 */
class DefaultController extends ConsoleController
{
    public function actionIndex()
	{
		$this->run('/help', ['monitor']);
		return ConsoleController::EXIT_CODE_NORMAL;
	}
}