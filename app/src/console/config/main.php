<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);
$config = [
    'id' => 'app-console',
    'language' => getenv('APP_LANGUAGE'),
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'urlManager' => [
            'class' => 'pheme\i18n\I18nUrlManager',
            'baseUrl' => getenv('BASE_URL'),
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            // 'languageParam' => 'lang',
            'languages' => [
                'English' => 'en',
                'Русский' => 'ru'
            ],
            'rules' => require Yii::getAlias('@common/config/urlManagerRules.php'),
        ],
    ],
    'bootstrap' => [
        'log'
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'=>'yii\gii\Module',
        'allowedIPs' => ['*']
    ];
}

return $config;
