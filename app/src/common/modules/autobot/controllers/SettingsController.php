<?php

namespace common\modules\autobot\controllers;

use common\base\Controller as BaseController;
use yii\filters\AccessControl;

class SettingsController extends BaseController
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['commonSettings'],
					],
				],
			],
		];
	}
}