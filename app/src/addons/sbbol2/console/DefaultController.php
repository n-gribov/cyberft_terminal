<?php

namespace addons\sbbol2\console;

use addons\sbbol2\Sbbol2Module;
use common\base\ConsoleController;

/**
 * @property Sbbol2Module $module
 */
class DefaultController extends ConsoleController
{
    /**
     * Метод выводит текст подсказки
     */
    public function actionIndex()
    {
        $this->run('/help', ['sbbol2']);
    }
}
