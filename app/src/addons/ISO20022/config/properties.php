<?php
return [
    // Класс модуля аддона
    'class' => 'addons\ISO20022\ISO20022Module',
    // Имя сервиса
    'serviceName' => 'ISO20022',
    // Меню
    'menu' => include __DIR__.'/menu.php',
    // Регулярные задания
    'regularJobs' => [
//        [
//            'descriptor' => 'ISO20022Sign',
//            'class' => '\addons\ISO20022\jobs\SignJobRegular',
//            'interval' => 5,
//        ],
        [
            // Дескриптор задания
            'descriptor' => 'ISO20022Import',
            // Класс задания
            'class' => '\addons\ISO20022\jobs\ImportJob',
            // Интервал запуска
            'interval' => 10,
        ],
        [
            'descriptor' => 'ISO20022Temp',
            'class' => '\addons\ISO20022\jobs\TempJob',
            'interval' => '07:00 weekday *',
        ],
        [
            'descriptor' => 'ISO20022Attachment',
            'class' => '\addons\ISO20022\jobs\AttachmentJob',
            'interval' => 5,
        ],
    ],
    // Используемые ресурсы
    'resources' => [
        'storage' => [
            'path' => '@storage/ISO20022',
            'dirs' => [
                'in' => ['directory' => 'in'],
                'out' => ['directory' => 'out'],
            ]
        ],
        'temp' => [
            'path' => '@storage/ISO20022/temp',
            'dirs' => [
                'default' => ['directory' => '', 'usePartition' => false],
            ]
        ],
        'export' => [
            'path' => '@export/ISO20022',
            'dirs' => [
                'default' => ['directory' => '', 'useUniqueName' => false],
            ]
        ],
        'import' => [
            'path' => '@import/ISO20022',
            'dirs' => [
                'default' => ['directory' => ''],
                //'in' => ['directory' => 'in'],
                '1c' => ['directory' => '1c'],
                'IBANK' => ['directory' => 'IBANK'],
                'error' => ['directory' => 'error'],
                'freeformat' => ['directory' => 'freeformat'],
                'job' => [
                    'directory' => 'job', 'usePartition' => false,
                    'useUniqueName' => false, 'ignoreSftp' => true
                ]
            ]
        ]
    ],
    // Обрабатываемые типы документов
    'docTypes' => [
        // Платеж
        'pain.001' => [
            'contentClass' => '\addons\ISO20022\models\Pain001CyberXmlContent',
            'extModelClass' => '\addons\ISO20022\models\ISO20022DocumentExt',
            'typeModelClass' => '\addons\ISO20022\models\Pain001Type',
            'jobs' => [
                'export' => '\addons\ISO20022\jobs\ExportJob',
            ],
            'dataView' => '@addons/ISO20022/views/documents/_view',
        ],
        // Статус
        'pain.002' => [
            'contentClass' => '\addons\ISO20022\models\Pain002CyberXmlContent',
            'extModelClass' => '\addons\ISO20022\models\ISO20022DocumentExt',
            'typeModelClass' => '\addons\ISO20022\models\Pain002Type',
            'jobs' => [
                'export' => '\addons\ISO20022\jobs\ExportJob',
            ],
            'dataView' => '@addons/ISO20022/views/documents/_view',
        ],
        // Передача файла в аттаче
        'auth.026' => [
            'contentClass' => '\addons\ISO20022\models\Auth026CyberXmlContent',
            'extModelClass' => '\addons\ISO20022\models\ISO20022DocumentExt',
            'typeModelClass' => '\addons\ISO20022\models\Auth026Type',
            'jobs' => [
                'export' => '\addons\ISO20022\jobs\ExportJob',
            ],
            'dataView' => '@addons/ISO20022/views/documents/_view',
        ],
        // Справка о валютных операциях
        'auth.024' => [
            'contentClass' => '\addons\ISO20022\models\Auth024CyberXmlContent',
            'extModelClass' => '\addons\ISO20022\models\ISO20022DocumentExt',
            'typeModelClass' => '\addons\ISO20022\models\Auth024Type',
            'jobs' => [
                'export' => '\addons\ISO20022\jobs\ExportJob',
            ],
            'dataView' => '@addons/ISO20022/views/documents/_view',
        ],
        // Справка о подтверждающих документах
        'auth.025' => [
            'contentClass' => '\addons\ISO20022\models\Auth025CyberXmlContent',
            'extModelClass' => '\addons\ISO20022\models\ISO20022DocumentExt',
            'typeModelClass' => '\addons\ISO20022\models\Auth025Type',
            'jobs' => [
                'export' => '\addons\ISO20022\jobs\ExportJob',
            ],
            'dataView' => '@addons/ISO20022/views/documents/_view',
        ],
        // Паспорт сделки
        'auth.018' => [
            'contentClass' => '\addons\ISO20022\models\Auth018CyberXmlContent',
            'extModelClass' => '\addons\ISO20022\models\ISO20022DocumentExt',
            'typeModelClass' => '\addons\ISO20022\models\Auth018Type',
            'jobs' => [
                'export' => '\addons\ISO20022\jobs\ExportJob',
            ],
            'dataView' => '@addons/ISO20022/views/documents/_view',
        ],
        // Статус
        'auth.027' => [
            'contentClass' => '\addons\ISO20022\models\Auth027CyberXmlContent',
            'extModelClass' => '\addons\ISO20022\models\ISO20022DocumentExt',
            'typeModelClass' => '\addons\ISO20022\models\Auth027Type',
            'jobs' => [
                'export' => '\addons\ISO20022\jobs\ExportJob',
            ],
            'dataView' => '@addons/ISO20022/views/documents/_view',
        ],
    ],
];