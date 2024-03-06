<?php

namespace backend\controllers;

use common\base\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class DiagnosticController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        // Accessible for authorized users only
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->actionSetupWeb();
    }

    public function actionSetupWeb()
    {
        // Вывести страницу
        return $this->render('setup',[
            'config' => $this->getConfig('backend'),
            'title' => Yii::t('app/diagnostic', 'Web application')
        ]);
    }

    public function actionSetupConsole()
    {
        // Вывести страницу
        return $this->render('setup',[
            'config' => $this->getConfig('console'),
            'title' => Yii::t('app/diagnostic', 'Console application')
        ]);
    }

    public function actionEnvironments()
    {
        // Вывести конфигурацию PHP
        return $this->render('phpinfo');
    }

    /**
     * @param string $appName
     * @return array
     */
    private function getConfig($appName='backend')
    {
        $appPath	= Yii::getAlias('@'.$appName);
        $commonPath	= Yii::getAlias('@common');

        $config = ArrayHelper::merge(
            require($commonPath . '/config/main.php'),
            require($appPath . '/config/main.php')
        );

        // убираем переменные с паролями
        array_walk_recursive($config, function(&$v,$k) {
            if (is_string($v) && $k==='password' ) {
                $v = '********';
            }
        });

        return $config;
    }
}
