<?php

namespace common\jobs;

use common\base\Job;
use common\states\StateRunner;
use Resque_Job_DontPerform;

/**
 * Run State
 */
class StateJob extends Job
{
    private $_stateClass;
    private $_state;
    private $_stateParams;

	public function setUp()
	{
		parent::setUp();

        if (isset($this->args['stateClass'])) {
            $this->_stateClass = $this->args['stateClass'];
        } else {
            $this->log('State class must be set');
            throw new Resque_Job_DontPerform('State class must be set');
        }

        if (isset($this->args['params'])) {
            $this->_stateParams = unserialize($this->args['params']);
        }

        try {
            $this->_state = new $this->_stateClass($this->_stateParams);
        } catch(\Error $ex) {
            $this->log($ex->getMessage());
        }

        if (empty($this->_state)) {
            $this->log('Failed to create state ' . $this->_stateClass);

            throw new Resque_Job_DontPerform('Failed to create state ' . $this->_stateClass);
        }
	}

	public function perform()
	{
        StateRunner::run($this->_state);
	}

}