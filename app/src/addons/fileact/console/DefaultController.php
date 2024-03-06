<?php

namespace addons\fileact\console;

use common\base\ConsoleController;


class DefaultController extends ConsoleController
{
	public function actionIndex() 
	{
		$this->run('/help', ['fileact']);
	}
}