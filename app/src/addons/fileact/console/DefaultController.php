<?php

namespace addons\fileact\console;

use common\base\ConsoleController;


class DefaultController extends ConsoleController
{
    /**
     * Метод выводит текст подсказки
     */
    public function actionIndex() 
    {
	$this->run('/help', ['fileact']);
    }
}
