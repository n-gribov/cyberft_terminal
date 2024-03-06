<?php

namespace common\modules\wiki\jobs;

use common\base\Job;
use common\modules\wiki\models\Dump;
use common\modules\wiki\WikiModule;
use Resque_Job_DontPerform;
use Yii;

/**
 * Applying wiki database dump file
 *
 * @package core
 * @subpackage jobs
 */
class ImportJob extends Job
{
    protected $path;

    public function setUp()
    {
        parent::setUp();

        $this->path = $this->args['tempFile'];

        if (!Yii::$app->fsTemp->has($this->path)) {
            $this->log('Temp file not found', true);

            throw new Resque_Job_DontPerform('Cannot load dump');
        }
    }

    /**
     * @inheritdoc
     */
    public function perform()
    {
        if (Dump::import(Yii::getAlias("@temp/{$this->path}"))) {
            WikiModule::info('Dump file imported');
        } else {
            WikiModule::warning('Dump file import failed');
        }

        Yii::$app->fsTemp->delete($this->path);
    }
}