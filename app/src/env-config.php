<?php
return [
    'INSTALL_TYPE' => ['default' => 'docker', 'skipOnPrompt' => true],
    // Docker
    'DOCKER_CONTAINER_ID' => ['default' => '', 'skipOnPrompt' => true],

    // NGINX
    'NGINX_USER' => ['default' => 'www-data', 'skipOnPrompt' => true],
    
    // MySQL
    'MYSQL_HOST' => 'localhost',
    'MYSQL_DBNAME' => 'cyberft',
    'MYSQL_USERNAME' => 'root',
    'MYSQL_PASSWORD' => '123qwe',

    // ElasticSearch
    'ELASTIC_HTTP_ADDRESS' => 'localhost:9200',
    'ELASTIC_INDEX' => 'cyberft',

    // Redis
    'REDIS_HOSTNAME' => 'localhost',
    'REDIS_PORT' => '6379',
    'REDIS_DATABASE' => '0',
    'REDIS_KEY' => 'cyberft',

    // Framework
    'YII_DEBUG' => ['default' => 'false', 'skipOnPrompt' => true],
    'YII_ENV' => ['default' => 'prod', 'skipOnPrompt' => true],
    'LOG_TYPE' => ['default' => 'default', 'skipOnPrompt' => true],

    'APP_LANGUAGE' => [
        'label' => 'Terminal language',
        'default' => 'ru',
        'pattern' => '/ru|en/',
    ],
    'BASE_URL' => 'https://cyberft.local',


    // Mailer
    'MAIL_FROM' => 'no-reply@cyberft.local',
];