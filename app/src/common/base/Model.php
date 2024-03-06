<?php

namespace common\base;

use common\helpers\ModelHelper;

class Model extends \yii\base\Model {
	public function getErrorsSummary($ignoreLabel = false)
	{
	    return ModelHelper::getErrorsSummary($this, $ignoreLabel);
	}
}
