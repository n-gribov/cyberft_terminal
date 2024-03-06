<?php

namespace backend\controllers;

use common\base\Controller;
use yii\filters\AccessControl;

class HelpController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        // Accessible for authorized users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Help index action
     *
     * dummy action
     */
    public function actionIndex()
    {
        // Dummy action
        // Вывести страницу
        return $this->render('about');
    }

    /**
     * Help about action
     */
    public function actionAbout()
    {
        // Вывести страницу
        return $this->render('about');
    }

}
