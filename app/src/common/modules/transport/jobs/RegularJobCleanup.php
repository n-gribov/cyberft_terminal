<?php

namespace common\modules\transport\jobs;

use common\base\RegularJob;
use Resque_Job_Status;
use Yii;

class RegularJobCleanup extends RegularJob
{
	public function perform()
	{
		$keys = Yii::$app->redis->keys('resque:job:*');
		$count = 0;
		$now = time();
		$period = 24 * 3600;

		foreach($keys as $key) {
			$data = json_decode(Yii::$app->redis->get($key));
			//echo "key: " . $key . " status: " . $data->status . " updated: " . date('d.m.Y H:i:s', $data->updated) . "\n";
			if (
				$now - $data->updated > $period
				&& ($data->status == Resque_Job_Status::STATUS_FAILED
					|| $data->status == Resque_Job_Status::STATUS_COMPLETE)
			) {

				Yii::$app->redis->del($key);
				$count++;
			}
		}

		$this->log('Cleaned ' . $count . ' jobs', false, 'regular-jobs');
	}

}