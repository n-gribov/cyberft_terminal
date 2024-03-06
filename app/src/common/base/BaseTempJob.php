<?php

namespace common\base;

use common\base\Job;
use Resque_Job_DontPerform;
use Yii;

class BaseTempJob extends Job
{
    const MODULE_ID = null;
	const MAX_DAYS = 1; //время жизни файлов в сутках

    private $_resource;

    public function setup()
    {
        if (!static::MODULE_ID) {
            $this->log('TempJob module id is not set');

            throw new Resque_Job_DontPerform('TempJob module id is not set');
        }

        $this->_resource = Yii::$app->registry->getTempResource(static::MODULE_ID);

   		if (!$this->_resource) {
			$this->log('Temp resource allocation error for module id ' . static::MODULE_ID);

			throw new Resque_Job_DontPerform('Temp resource allocation error for module id ' . static::MODULE_ID);
		}

    }

	public function perform()
	{
		$this->_resource->deleteOldFiles(static::MAX_DAYS);
	}

}