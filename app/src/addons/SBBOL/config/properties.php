<?php

return [
	'class' => 'addons\SBBOL\SBBOLModule',
	'serviceName' => 'SBBOL',
    'menu' => include __DIR__.'/menu.php',
    'regularJobs' => getenv('DISABLE_SBBOL_REGULAR_JOBS') === 'true'
        ? []
        : [
            [
                'descriptor' => 'processAsyncSBBOLRequests',
                'class' => '\addons\SBBOL\jobs\ProcessAsyncSBBOLRequestsJob',
                'interval' => 5,
            ],
            [
                'descriptor' => 'requestYesterdaysSBBOLStatements',
                'class'      => '\addons\SBBOL\jobs\RequestSBBOLStatementsJob',
                'interval'   => 3600,
                'params'     => [
                    'date'                => 'yesterday',
                    'timeFromSettingsKey' => 'requestYesterdaysStatementsTimeFrom',
                    'timeToSettingsKey'   => 'requestYesterdaysStatementsTimeTo',
                    'settingsCheckKey'    => 'requestYesterdaysStatements',
                ]
            ],
            [
                'descriptor' => 'requestTodaysSBBOLStatements',
                'class'      => '\addons\SBBOL\jobs\RequestSBBOLStatementsJob',
                'interval'   => function () {
                    $settings = Yii::$app->settings->get('SBBOL:SBBOL');
                    return 60 * max(15, $settings->requestTodaysStatementsInterval ?: 60);
                },
                'params'     => [
                    'date'                => 'today',
                    'timeFromSettingsKey' => 'requestTodaysStatementsTimeFrom',
                    'timeToSettingsKey'   => 'requestTodaysStatementsTimeTo',
                    'settingsCheckKey'    => 'requestTodaysStatements',
                ]
            ],
        ],
	'resources' => [
		'storage' => [
			'path' => '@storage/SBBOL',
            'dirs' => [
                'in' => ['directory' => 'in'],
                'out' => ['directory' => 'out'],
            ]
		],
		'temp' => [
			'path' => '@storage/SBBOL/temp',
            'dirs' => [
                'default' => ['directory' => '', 'usePartition' => false],
            ]
		],
		'export' => [
			'path' => '@export/SBBOL',
            'dirs' => [
                'default' => ['directory' => '', 'useUniqueName' => false],
            ]
		],
		'import' => [
			'path' => '@import/SBBOL',
            'dirs' => [
                'default' => ['directory' => ''],
                'error' => ['directory' => 'error'],
                'job' => [
                    'directory' => 'job', 'usePartition' => false,
                    'useUniqueName' => false, 'ignoreSftp' => true
                ]
            ]
		]
	],

	'docTypes' => [
	],

    'components' => [
        'transport' => [
            'class' => 'addons\SBBOL\components\SBBOLTransport'
        ],
        'sessionManager' => [
            'class' => 'addons\SBBOL\components\SBBOLSessionManager'
        ],
    ],
];
