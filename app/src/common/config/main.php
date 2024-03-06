<?php
return [
    'id' => 'myApp',
    'basePath' => __DIR__ . '/../../',
    'version' => '4.3.1',
    'name' => 'CyberFT Terminal',
    'timeZone' => 'Europe/Moscow',
    'charset' => 'UTF-8',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        // Путь к хранилищу пользовательских ключей
        '@userKeyStorage' => '@storage/userkeys',
        // Путь к хранилищу экспортируемых документов
        '@docExport' => '@projectRoot/export',
        // Путь к клиентскому конфигу
        '@clientConfig' => '@projectRoot/config',
        '@bower' => '@vendor/bower-asset',
    ],
    'components' => [
        'settings' => [
            'class' => 'common\components\Settings',
        ],
        'elasticsearch' => [
            'class' => 'common\components\ElasticSearch',
            'index' => getenv('ELASTIC_INDEX'),
            'nodes' => [
                ['http_address' => getenv('ELASTIC_HTTP_ADDRESS')],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . getenv('MYSQL_HOST') . ';dbname=' . getenv('MYSQL_DBNAME'),
            'username' => getenv('MYSQL_USERNAME'),
            'password' => getenv('MYSQL_PASSWORD'),
            'charset' => 'utf8',
        ],
        'exchange' => [
            'class' => 'common\components\terminal\Exchange',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
        ],
        'resque' => [
            'class' => 'common\components\Resque',
            'redisLocation' => getenv('REDIS_HOSTNAME') . ':' . getenv('REDIS_PORT'),
            'redisDataBase' => getenv('REDIS_DATABASE'),
        ],
        'commandBus' => 'common\components\CommandBus',
        'cryptography' => 'common\components\cryptography\Cryptography',
        'user' => array(
            'class' => 'common\components\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'authTimeout' => \common\helpers\UserHelper::getUserLogoutTimeout() * 60,
        ),
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'itemFile' => '@common/rbac/files/items.php',
            'ruleFile' => '@common/rbac/files/rules.php',
            'assignmentFile' => '@common/rbac/files/assignments.php',
            'defaultRoles' => ['admin', 'user', 'lso', 'rso', 'controller', 'additionalAdmin'],
        ],
        'formatter' => [
            'class' => 'common\i18n\Formatter',
            'defaultTimeZone' => 'Europe/Moscow',
        ],
        'log' => require('main-log.php'),
        'i18n' => require('main-i18n.php'),
        'redis' => [
            'class' => 'common\db\RedisConnection',
            'hostname' => getenv('REDIS_HOSTNAME'),
            'port' => getenv('REDIS_PORT'),
            'database' => getenv('REDIS_DATABASE'),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@appRuntime/cache',
        ],
        'storage' => 'common\components\Storage',
        'registry' => 'common\components\Registry',
        'addon' => [
            'class' => 'common\components\Addon'
        ],
        'xmlsec' => [
            'class' => 'common\components\xmlsec\XMLSec',
        ],
        'mailNotifier' => [
            'class' => 'common\components\MailNotifier',
            'from' => getenv('MAIL_FROM'),
        ],
        'monitoring'   => [
            'class' => 'common\modules\monitor\components\MonitorComponent'
        ],
        'fsTemp'    => [
            'class' => 'creocoder\flysystem\LocalFilesystem',
            'path' => '@temp',
        ],
        'processingApiFactory' => 'common\components\processingApi\ProcessingApiFactory',
    ],
    'bootstrap' => [
        'addon',
        'transport',
        'monitor',
        'participant',
        'wiki',
        'log',
        'autobot',
    ],
    'modules' => [
        'certManager' => [
            'class' => 'common\modules\certManager\Module',
            'keyLength' => 2048
        ],
        'api' => [
            'class' => 'common\modules\api\ApiModule',
        ],
        'autobot' => [
            'class' => 'common\modules\autobot\AutobotModule',
        ],
        'monitor' => [
            'class' => 'common\modules\monitor\MonitorModule',
        ],
        'transport' => [
            'class' => 'common\modules\transport\TransportModule',
            'modeExportDuplicate' => getenv('MODE_EXPORT_DUPLICATE') == 'true',
            'components' => [
                'stomp' => [
                    'class' => 'common\modules\transport\components\StompTransport',
                ],
                'cftcp' => [
                    'class' => 'common\modules\transport\components\CftcpTransport',
                    'cftcp' => Yii::getAlias('@bin/cftcp'),
                ],
            ]
        ],
        'participant' => [
            'class' => 'common\modules\participant\ParticipantModule',
        ],
        'wiki' => [
            'class' => 'common\modules\wiki\WikiModule',
        ],
    ],
];
