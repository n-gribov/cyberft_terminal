<?php
namespace addons\swiftfin\console;

use common\base\ConsoleController;

class DefaultController extends ConsoleController
{
    public function actionIndex() {
        $this->run('/help', ['swiftfin']);
    }

}