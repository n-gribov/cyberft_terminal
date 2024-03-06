<?php

namespace addons\SBBOL\jobs;

use addons\SBBOL\SBBOLModule;
use common\base\RegularJob;
use Resque_Job_DontPerform;
use Yii;

class BaseJob extends RegularJob
{
    /**
     * @var SBBOLModule $module
     */
    protected $module;

    public function setUp()
    {
        parent::setUp();

        $this->module = Yii::$app->getModule(SBBOLModule::SERVICE_ID);
        if (empty($this->module)) {
            throw new Resque_Job_DontPerform('SBBOL module not found');
        }
    }

    public function log($message, $warning = false, $category = false)
    {
        parent::log($message, $warning, 'regular-jobs');
    }
}
