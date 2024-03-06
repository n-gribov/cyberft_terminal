<?php

namespace common\modules\wiki\jobs;

use common\base\Job;
use common\modules\wiki\models\Dump;
use Resque_Job_DontPerform;

/**
 * Making wiki database dump file
 *
 * @package core
 * @subpackage jobs
 */
class ExportJob extends Job
{
    /**
     *
     * @var Dump
     */
    protected $dump;


    public function setUp()
    {
        parent::setUp();

        if (!$this->dump = Dump::loadFromCache()) {
            throw new Resque_Job_DontPerform('Cannot load dump model');
        }
    }

    /**
     * @inheritdoc
     */
    public function perform()
    {
        $this->log('Building dump');
        $this->dump->status = Dump::STATUS_WORKING;
        $this->dump->save();

        $this->dump->cleanup();
        $this->dump->build();
        
        $this->dump->status = Dump::STATUS_READY;
        $this->dump->save();
        $this->log('Dump created: ' . $this->dump->getTargetFilename());
    }
}