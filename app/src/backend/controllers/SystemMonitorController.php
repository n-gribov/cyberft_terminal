<?php

namespace backend\controllers;

use common\base\Controller;
use yii\filters\AccessControl;

class SystemMonitorController extends Controller
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

    public function actionResources()
    {
        return $this->render('resources', [
           'resources'  => \Yii::$app->registry->getResources(),
        ]);
    }
}