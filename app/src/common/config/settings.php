<?php

return [
    'processing' => [
        'dsn'                 => null,
        'address'             => null,
        'safeMode'            => false, //если true, то отправлять только через STOMP
        'safeModeMaxFileSize' => '1572864', //максимальный размер сообщения в байтах
    ],
    'cftcp' => [
        'dirIn'     => Yii::getAlias('@cftcp'),
        'timeout'   => 15,
        'chunkSize' => 1000000,
    ],
    'ssh2' => [
        'host'     => getenv('SSH2_HOSTNAME'),
        'port'     => getenv('SSH2_PORT'),
        'username' => getenv('SSH2_USERNAME'),
        'password' => getenv('SSH2_PASSWORD'),
    ],
];
