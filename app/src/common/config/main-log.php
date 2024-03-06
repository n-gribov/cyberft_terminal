<?php
$targets = [];

if (getenv('LOG_TYPE') == 'syslog') {
    $targets[] = [
        'class' => 'yii\log\SyslogTarget',
        'levels' => ['info', 'warning'],
        'identity' => 'CyberFT',
        'categories' => ['system', 'application'],
        'logVars' => [],
        'prefix' => function ($message) {
            return null;
        }
    ];
} else {
    $targets[] = [
        'class' => 'yii\log\FileTarget',
        'logFile' => '@logs/app-system.log',
        'logVars' => [],
        'levels' => ['info', 'warning'],
        'categories' => ['system', 'application'],
        'prefix' => function ($message) {
            return null;
        }
    ];
}

if (!empty(getenv('RESQUE_DEBUG'))) {
    $targets[] = [
        'categories' => ['dispatcher'],
        'class' => 'yii\log\FileTarget',
        'logFile' => '@logs/resq-dispatcher.log',
        'logVars' => [],
        'prefix' => function ($message) {
            return null;
        }
    ];
    $targets[] = [
        'class' => 'yii\log\FileTarget',
        'logFile' => '@logs/app-resq.log',
        'logVars' => [],
        'categories' => ['resque'],
        'prefix' => function ($message) {
            return null;
        }
    ];
}

if (!empty(getenv('SENTRY_DSN'))) {
    $targets[] = [
        'class' => 'notamedia\sentry\SentryTarget',
        'dsn' => getenv('SENTRY_DSN'),
        'levels' => ['error', 'warning'],
        'context' => true,
        'clientOptions' => [
            'environment' => getenv('SENTRY_ENV', 'staging'),
        ],
    ];
}

$targets = \yii\helpers\ArrayHelper::merge($targets,
    [
        [
            'class' => 'yii\log\FileTarget',
            'logFile' => '@logs/app-stomp.log',
            'logVars' => [],
            'levels' => ['info', 'warning', 'error'],
            'categories' => ['stomp'],
            'exportInterval' => 1,
            'prefix' => function ($message) {
                return null;
            }
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logFile' => '@logs/app-transport.log',
            'logVars' => [],
            'levels' => ['info'],
            'categories' => ['transport'],
            'prefix' => function ($message) {
                return null;
            }
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logFile' => '@logs/app-swiftfin.log',
            'logVars' => [],
            'levels' => ['info', 'warning', 'error'],
            'categories' => ['swiftfin', 'SwiftFin'],
            'prefix' => function ($message) {
                return null;
            }
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logFile' => '@logs/app-finzip.log',
            'logVars' => [],
            'levels' => ['info', 'warning', 'error'],
            'categories' => ['finzip', 'FinZip'],
            'prefix' => function ($message) {
                return null;
            }
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logFile' => '@logs/app-iso20022.log',
            'logVars' => [],
            'levels' => ['info', 'warning', 'error'],
            'categories' => ['ISO20022'],
            'prefix' => function ($message) {
                return null;
            }
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logFile' => '@logs/app-edm.log',
            'logVars' => [],
            'levels' => ['info', 'warning', 'error'],
            'categories' => ['EDM'],
            'prefix' => function ($message) {
                return null;
            }
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logFile' => '@logs/app-regularjobs.log',
            'logVars' => [],
            'levels' => ['info', 'warning', 'error'],
            'categories' => ['regular-jobs'],
            'prefix' => function ($message) {
                return null;
            }
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logFile' => '@logs/app-error.log',
            'logVars' => [],
            'levels' => ['error'],
            'prefix' => function ($message) {
                return null;
            }
        ],
    ]
);

if (YII_DEBUG) {
    $targets[] = [
        'class' => 'yii\log\FileTarget',
        'logFile' => '@logs/app-debug.log',
        'logVars' => [],
        'levels' => [],
        'categories' => ['debug'],
        'prefix' => function ($message) {
            return null;
        }
    ];
}

if (getenv('ENABLE_INCREMENTAL_STATEMENT_EXPORT_LOG') === 'true') {
    $targets[] = [
        'class' => 'yii\log\FileTarget',
        'logFile' => '@logs/incremental-statement-export.log',
        'logVars' => [],
        'levels' => ['info'],
        'categories' => ['incremental-statement-export'],
        'prefix' => function ($message) {
            return null;
        }
    ];
}

return [
//    'flushInterval' => 10,
    'targets' => $targets
];
