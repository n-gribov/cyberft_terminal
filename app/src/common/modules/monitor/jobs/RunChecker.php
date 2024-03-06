<?php

namespace common\modules\monitor\jobs;

use common\base\Job;
use common\modules\monitor\MonitorModule;
use Yii;

class RunChecker extends Job
{
    public function perform()
    {
        parent::perform();

        $module = MonitorModule::getInstance();
        $checker  = $module->getChecker($this->args['checkerCode']);
        $checker->run();

        return true;
    }
}