<?php

use addons\sbbol2\authClient\SberbankOpenIdConnect;
use addons\sbbol2\components\ApiAccessTokenProvider;
use addons\sbbol2\components\ApiFactory;
use addons\sbbol2\settings\Sbbol2Settings;
use yii\httpclient\CurlTransport;

return [
    'class' => 'addons\sbbol2\Sbbol2Module',
    'serviceName' => 'sbbol2',
    'menu' => include __DIR__.'/menu.php',
    'regularJobs' => getenv('DISABLE_SBBOL2_REGULAR_JOBS') === 'true'
        ? []
        : [
            [
                'descriptor' => 'sendSbbol2Documents',
                'class' => '\addons\sbbol2\jobs\SendSbbol2DocumentsJob',
                'interval' => 10,
            ],
            [
                'descriptor' => 'checkSbbol2DocumentsStatus',
                'class' => '\addons\sbbol2\jobs\CheckSbbol2DocumentsStatusJob',
                'interval' => 60,
            ],
            [
                'descriptor' => 'queuedSbbol2Statement',
                'class' => '\addons\sbbol2\jobs\QueuedSbbol2StatementJob',
                'interval' => 10,
            ],
            [
                'descriptor' => 'requestSbbol2Statement',
                'class' => '\addons\sbbol2\jobs\RequestSbbol2StatementJob',
                'interval' => 3600,
            ],

        ],
    'resources' => [
        'storage' => [
            'path' => '@storage/sbbol2',
            'dirs' => [
                'in' => ['directory' => 'in'],
                'out' => ['directory' => 'out'],
            ]
        ],
        'temp' => [
            'path' => '@storage/sbbol2/temp',
            'dirs' => [
                'default' => ['directory' => '', 'usePartition' => false],
            ]
        ],
        'export' => [
            'path' => '@export/sbbol2',
            'dirs' => [
                'default' => ['directory' => '', 'useUniqueName' => false],
            ]
        ],
        'import' => [
            'path' => '@import/sbbol2',
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
        'authClientCollection' => function () {
            /** @var Sbbol2Settings $settings */
            $settings = Yii::$app->getModule('sbbol2')->getSettings();
            return new yii\authclient\Collection([
                'clients' => [
                    'sberbank' => [
                        'class'          => SberbankOpenIdConnect::class,
                        'clientId'       => $settings->apiClientId,
                        'clientSecret'   => $settings->apiClientSecret,
                        'name'           => 'sberbank',
                        'title'          => 'Sberbank OAuth Client',
                        'authUrl'        => $settings->authorizationUrl,
                        'tokenUrl'       => "{$settings->authorizationApiUrl}/ic/sso/api/oauth/token",
                        'scope'          => trim("openid {$settings->authorizationPartnerScope}"),
                        'apiBaseUrl'     => $settings->apiUrl,
                        'requestOptions' => [
                            'sslLocalCert'  => $settings->tlsClientCertificatePath,
                            'sslLocalPk'    => $settings->tlsKeyPath,
                            'sslCafile'     => $settings->tlsCaBundlePath,
                            'sslPassphrase' => $settings->tlsKeyPassword,
                            'timeout'       => 30,
                        ],
                    ],
                ],
                'httpClient' => [
                    'transport' => CurlTransport::class,
                ],
            ]);
        },
        'apiFactory' => [
            'class' => ApiFactory::class,
        ],
        'apiAccessTokenProvider' => [
            'class' => ApiAccessTokenProvider::class,
        ],
    ],
];
