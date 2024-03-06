<?php

namespace common\modules\participant\console;

use common\base\ConsoleController;
use common\modules\participant\jobs\LoadDirectoryJob;
use Yii;

/**
 * Participant module
 */
class DefaultController extends ConsoleController
{
    public function actionIndex()
	{
		$this->run('/help', ['participant']);
	}

    public function actionUpdate($forceUpdate = false)
    {
        Yii::$app->resque->enqueue(LoadDirectoryJob::class, ['forceUpdate' => boolval($forceUpdate)]);
    }
}
