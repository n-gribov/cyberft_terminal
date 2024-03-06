<?php

namespace common\models;

use common\base\Model;
use Yii;

/**
 * Reports model
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package \common\models
 */
class ProfilingData extends Model
{
	public $startTime;
	public $endTime;
	public $time;
	public $memoryUsageStart;
	public $memoryUsageEnd;
	public $jobName;
	public $jobId;

	protected static $_profilingDataKey = 'resque:profile:data';

	public function rules()
    {
		return [
			[['jobId', 'jobName'], 'required'],
			[['memoryUsageStart', 'memoryUsageEnd', 'startTime', 'endTime', 'time'], 'safe'],
		];
	}

	public static function all()
    {
		$data = Yii::$app->redis->hmget(self::$_profilingDataKey);
		$result = [];

        foreach ($data as $key => $value) {
			$value = json_decode($value, true);
			if (is_null($value)) {
				$value = [];
			}
			$value['jobId'] = $key;
			$result[] = new self($value);
		}

        return $result;
	}

	public static function getProfilingByJobId($jobId)
    {
		$data = json_decode(Yii::$app->redis->hget(self::$_profilingDataKey, $jobId), true);

        if (is_null($data)) {
			$data = [];
		}

		if (!empty($data)) {
			$data['jobId'] = $jobId;
		}

        return (new self($data));
	}

	public function save()
    {
		Yii::$app->redis->hset(self::$_profilingDataKey, $this->jobId, json_encode($this->getAttributes()));

        return true;
	}

}