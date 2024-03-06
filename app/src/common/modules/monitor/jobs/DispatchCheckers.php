<?php
namespace common\modules\monitor\jobs;

use common\base\RegularJob;
use common\models\Terminal;
use Yii;
use yii\helpers\ArrayHelper;
use common\modules\monitor\MonitorModule;

/**
 * Undelivered messages check job class
 *
 */
class DispatchCheckers extends RegularJob
{
    public function perform()
    {
        $module = Yii::$app->getModule('monitor');

        foreach($module->getActiveCheckers() as $checkerCode) {
            $checkerCode = lcfirst($checkerCode);
            $checker = $module->getChecker($checkerCode);

            $nextIterationTime = strtotime('+' . $checker->iterationInterval . ' seconds', $checker->checkTime);

            if ($nextIterationTime <= mktime()) {
                $this->log('Running checker: ' . $checkerCode, false, 'regular-jobs');
                Yii::$app->resque->enqueue('common\modules\monitor\jobs\RunChecker', [
                    'checkerCode' => $checkerCode
                ]);
            }
        }
    }
}