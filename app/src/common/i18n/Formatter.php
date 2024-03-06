<?php

namespace common\i18n;


use yii\helpers\Html;

class Formatter extends \yii\i18n\Formatter {

	public function asPre($value)
    {
		return Html::tag('pre', $value);
	}


	public function asJson($value)
    {
		$string = json_encode($value,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

		return '<pre style="height: 150px; overflow-y: auto; margin: 0">' . $string . '</pre>';
	}
} 