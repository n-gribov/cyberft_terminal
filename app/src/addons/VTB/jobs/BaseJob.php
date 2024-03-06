<?php


namespace addons\VTB\jobs;


use addons\VTB\VTBModule;
use common\base\RegularJob;
use Resque_Job_DontPerform;
use Yii;

class BaseJob extends RegularJob
{
    /**
     * @var VTBModule $module
     */
    protected $module;

    public function setUp()
    {
        parent::setUp();

        $this->module = Yii::$app->getModule('VTB');
        if (empty($this->module)) {
            throw new Resque_Job_DontPerform('VTB module not found');
        }
    }

    public function log($message, $warning = false, $category = false)
    {
        parent::log($message, $warning, 'regular-jobs');
    }
}
