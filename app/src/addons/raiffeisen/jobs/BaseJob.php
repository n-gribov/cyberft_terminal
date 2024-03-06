<?php

namespace addons\raiffeisen\jobs;

use addons\raiffeisen\RaiffeisenModule;
use common\base\RegularJob;
use Resque_Job_DontPerform;
use Yii;

class BaseJob extends RegularJob
{
    /**
     * @var RaiffeisenModule $module
     */
    protected $module;

    public function setUp()
    {
        parent::setUp();

        $this->module = Yii::$app->getModule(RaiffeisenModule::SERVICE_ID);
        if (empty($this->module)) {
            throw new Resque_Job_DontPerform('Raiffeisen module not found');
        }
    }

    public function log($message, $warning = false, $category = false)
    {
        parent::log($message, $warning, 'regular-jobs');
    }
}
