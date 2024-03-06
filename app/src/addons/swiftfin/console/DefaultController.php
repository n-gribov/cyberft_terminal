<?php
namespace addons\swiftfin\console;

use common\base\ConsoleController;

class DefaultController extends ConsoleController
{
    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex() {
        $this->run('/help', ['swiftfin']);
    }

}