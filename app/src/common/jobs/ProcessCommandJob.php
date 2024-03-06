<?php
namespace common\jobs;

use Yii;
use common\base\Job;

class ProcessCommandJob extends Job
{
    private $_commandId;

    /**
     * @inheritdoc
     */
	public function setUp()
	{
		parent::setUp();

        if (isset($this->args['id'])) {
            $this->_commandId = $this->args['id'];
        } else {
            $this->log("Command ID must be set");
            throw new \Resque_Job_DontPerform();
        }
	}

    /**
     * @inheritdoc
     */
	public function perform()
	{
        if (Yii::$app->commandBus->perform($this->_commandId)) {
            $this->log('Command #'. $this->_commandId .' perform succesful!');
        } else {
            $this->log('Error! Command #'. $this->_commandId .' perform failure!');
            throw new \Resque_Job_DontPerform();
        }
	}

}