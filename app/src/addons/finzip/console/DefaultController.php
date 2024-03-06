<?php

namespace addons\finzip\console;

use common\base\ConsoleController;

class DefaultController extends ConsoleController
{
	public function actionIndex() {
		$this->run('/help', ['finzip']);
	}
}
