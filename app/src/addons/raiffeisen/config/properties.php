<?php

return [
	'class' => 'addons\raiffeisen\RaiffeisenModule',
	'serviceName' => 'raiffeisen',
    'menu' => include __DIR__.'/menu.php',
    'regularJobs' => getenv('DISABLE_RAIFFEISEN_REGULAR_JOBS') === 'true'
        ? []
        : [
            [
                'descriptor' => 'processAsyncRaiffeisenRequests',
                'class'      => '\addons\raiffeisen\jobs\ProcessAsyncRaiffeisenRequestsJob',
                'interval'   => function () {
                    $settings = Yii::$app->settings->get('raiffeisen:Raiffeisen');
                    return 60 * max(1, $settings->processAsyncRequestsInterval);
                },
            ],
            [
                'descriptor' => 'requestRaiffeisenIncommingDocument',
                'class'      => '\addons\raiffeisen\jobs\RequestRaiffeisenIncomingDocumentsJob',
                'interval'   => function () {
                    $settings = Yii::$app->settings->get('raiffeisen:Raiffeisen');
                    return 60 * max(5, $settings->requestIncomingDocumentsInterval);
                },
                'params'     => [
                    'timeFromSettingsKey' => 'requestIncomingDocumentsTimeFrom',
                    'timeToSettingsKey'   => 'requestIncomingDocumentsTimeTo',
                ],
            ],
        ],
	'resources' => [
		'storage' => [
			'path' => '@storage/raiffesien',
            'dirs' => [
                'in' => ['directory' => 'in'],
                'out' => ['directory' => 'out'],
            ]
		],
		'temp' => [
			'path' => '@storage/raiffesien/temp',
            'dirs' => [
                'default' => ['directory' => '', 'usePartition' => false],
            ]
		],
		'export' => [
			'path' => '@export/raiffesien',
            'dirs' => [
                'default' => ['directory' => '', 'useUniqueName' => false],
            ]
		],
		'import' => [
			'path' => '@import/raiffesien',
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
            'class' => 'addons\raiffeisen\components\RaiffeisenTransport'
        ],
        'sessionManager' => [
            'class' => 'addons\raiffeisen\components\RaiffeisenSessionManager'
        ],
    ],
];
