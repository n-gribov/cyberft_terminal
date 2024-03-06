<?php

namespace common\models;

use common\base\Model;
use Yii;

class ProfilingLink extends Model 
{
	public $entity;
	public $entityId;
	public $jobId;

	protected static $_profilingLinkKey = 'resque:profile:link:{entityName}:{entityId}';

	public function rules() {
		return [
			[['jobId', 'entity', 'entityId'], 'required'],
		];
	}
	public function save() {
		$key = str_replace(['{entityName}','{entityId}'], [$this->entity, $this->entityId], self::$_profilingLinkKey);
		Yii::$app->redis->sadd($key, $this->jobId);
	}
}

