<?php

namespace backend\controllers;

use common\base\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use common\models\ProfilingData;

class ResqueController extends Controller
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
        // Вывести страницу
        return $this->render('stat', ['stat' => Yii::$app->resque->info()]);
    }

    public function actionProfiling() 
    {

        $dataProvider = new ArrayDataProvider([
            'allModels' => ProfilingData::all(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        // Вывести страницу
        return $this->render('profiling', ['dataProvider' => $dataProvider]);
    }
}