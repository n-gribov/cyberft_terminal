<?php
use kartik\datecontrol\Module as KartikModule;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

$config =  [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
	'language' => getenv('APP_LANGUAGE'),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'transport'],
    'components' => [
		'user' => [
			'loginUrl' => ['site/login'],
		],
		'request' => [
			'cookieValidationKey' => getenv('COOKIE_VALIDATION_KEY'),
            'csrfCookie' => [
                'httpOnly' => true,
                'secure' => true,
            ],
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
		],
		'formatter' => [
			'dateFormat' => 'php:Y.m.d',
			'timeFormat' => 'php:H:i:s',
			'datetimeFormat' => 'php:Y.m.d H:i:s',
			'decimalSeparator' => ',',
			'thousandSeparator' => ' ',
			'currencyCode' => 'EUR',
		],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		'urlManager' => [
	        'class' => 'pheme\i18n\I18nUrlManager',
			'enablePrettyUrl' => true,
			'enableStrictParsing' => false,
			'showScriptName' => false,
		//  'languageParam' => 'lang',
			'languages' => [
				'English' => 'en',
                'Русский' => 'ru'
			],
			'rules' => require Yii::getAlias('@common/config/urlManagerRules.php'),
		],
		'assetManager' => [
            'bundles' => require Yii::getAlias('@common/config/assets-prod.php'),
//			'converter' => [
//				'class' => 'common\web\AssetConverter',
//				'commands' => [
//					'less'	=> ['css', 'lessc {from} {to} --no-color', '../css'],
//				],
//			],
//			'linkAssets' => true, // "сим линкаем" assets
//			'bundles' => [
//				'yii\bootstrap\BootstrapAsset' => [
//					'sourcePath' => '@webroot/bootstrap/avant',
//					'css' => ['less/styles.less']
//				],
//			],
		],
        'session' => [
            'cookieParams' => [
                'httpOnly' => true,
                'secure' => true,
            ]
        ],
		'menu' => include (__DIR__.'/menu.php'),
        'terminalAccess' => [
            'class' => 'common\components\TerminalAccess',
        ],
        'edmAccountAccess' => [
            'class' => 'common\components\EdmAccountAccess',
        ]

	],
	'modules' => [
		// http://demos.krajee.com/field-range
		'datecontrol' => [
			'class' => 'kartik\datecontrol\Module',

			// format settings for displaying each date attribute
			'displaySettings' => [
				KartikModule::FORMAT_DATE => 'php:Y-m-d',
				KartikModule::FORMAT_TIME => 'php:H:i:s A',
				KartikModule::FORMAT_DATETIME => 'php:Y-m-d H:i:s A',
			],

			// format settings for saving each date attribute
			'saveSettings' => [
				KartikModule::FORMAT_DATE => 'U', // saves as unix timestamp
				KartikModule::FORMAT_TIME => 'H:i:s',
				KartikModule::FORMAT_DATETIME => 'Y-m-d H:i:s',
			],

			// automatically use kartik\widgets for each of the above formats
			'autoWidget' => true,

			// use ajax conversion for processing dates from display format to save format.
			'ajaxConversion' => false,

			// default settings for each widget from kartik\widgets used when autoWidget is true
			'autoWidgetSettings' => [
				KartikModule::FORMAT_DATE => ['type' => 2, 'pluginOptions' => ['autoclose' => true]], // example
				KartikModule::FORMAT_DATETIME => [], // setup if needed
				KartikModule::FORMAT_TIME => [], // setup if needed
			],

			// custom widget settings that will be used to render the date input instead of kartik\widgets,
			// this will be used when autoWidget is set to false at module or widget level.
			'widgetSettings' => [
				KartikModule::FORMAT_DATE => [
					'class' => 'yii\jui\DatePicker', // example
					'options' => [
						'options' => ['class' => 'form-control'],
						'clientOptions' => ['dateFormat' => 'dd-mm-yy'],
					]
				]
			]
		]
	],
    'params' => $params,
];

if (YII_ENV_DEV) {
 	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class'=>'yii\gii\Module',
        'allowedIPs' => ['*']
		//'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '172.17.42.1', '172.17.0.1'],
	];

    $config['bootstrap'][] = 'debug';
 	$config['modules']['debug'] = [
 		'class' => 'yii\debug\Module',
        'allowedIPs' => ['*']
 		//'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '172.17.42.1', '172.17.0.1'],
 	];
}

return $config;
