<?php

namespace addons\finzip\console;

use common\base\ConsoleController;

class DefaultController extends ConsoleController
{
    /**
     * Метод выводит текст подсказки
     */
    public function actionIndex() {
        $this->run('/help', ['finzip']);
    }
}
