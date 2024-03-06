<?php

namespace addons\sbbol2\jobs;

use addons\sbbol2\Sbbol2Module;
use common\base\RegularJob;
use Resque_Job_DontPerform;
use Yii;

class BaseJob extends RegularJob
{
    /**
     * @var Sbbol2Module $module
     */
    protected $module;

    public function setUp()
    {
        parent::setUp();

        $this->module = Yii::$app->getModule(Sbbol2Module::SERVICE_ID);
        if (empty($this->module)) {
            throw new Resque_Job_DontPerform('Sbbol2 module not found');
        }
    }

    public function log($message, $warning = false, $category = 'regular-jobs')
    {
        parent::log($message, $warning, $category);
    }

}
