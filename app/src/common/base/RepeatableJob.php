<?php
namespace common\base;

use Yii;
use ResqueScheduler;

/**
 * Description of Job
 *
 * @author fuzz
 */
abstract class RepeatableJob extends Job
{
	const REPEAT_INTERVAL = 30;

	protected $repeatArgs = [];

	public function tearDown()
	{
		Yii::$app->resque->enqueue(
			static::REPEAT_INTERVAL,
			static::DEFAULT_QUEUE_NAME,
			get_class($this),
			$this->repeatArgs
		);
	}
}