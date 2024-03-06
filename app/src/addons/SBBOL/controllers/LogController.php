<?php

namespace addons\SBBOL\controllers;

use addons\SBBOL\models\SBBOLRequestLogRecord;
use common\base\BaseServiceController;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class LogController extends BaseServiceController
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['admin'],
					],
				],
			],
		];
	}

    public function actionIndex()
    {
        $query = $logRecord = SBBOLRequestLogRecord::find()
            ->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => false,
        ]);

        return $this->render(
            'index',
            ['dataProvider' => $dataProvider]
        );
    }

}
