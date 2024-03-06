<?php

return [
    'class' => 'addons\VTB\VTBModule',
    'serviceName' => 'VTB',
    'menu' => include __DIR__.'/menu.php',
    'regularJobs' => getenv('DISABLE_VTB_REGULAR_JOBS') === 'true'
        ? []
        : [
            [
                'descriptor' => 'vtbSendDocuments',
                'class' => '\addons\VTB\jobs\SendVTBDocumentsJob',
                'interval' => 10,
            ],
            [
                'descriptor' => 'vtbCheckDocumnetsStatus',
                'class' => '\addons\VTB\jobs\CheckVTBDocumentsStatusJob',
                'interval' => 60,
            ],
            [
                'descriptor' => 'vtbUpdateCustomersData',
                'class' => '\addons\VTB\jobs\UpdateVTBCustomersDataJob',
                'interval' => '01:00 weekday *',
            ],
            [
                'descriptor' => 'vtbReceiveBankDocumentsJob',
                'class' => '\addons\VTB\jobs\ReceiveVTBBankDocumentsJob',
                'interval' => 30,
            ],
        ],
    'resources' => [
        'storage' => [
            'path' => '@storage/VTB',
            'dirs' => [
                'in'  => ['directory' => 'in'],
                'out' => ['directory' => 'out'],
            ]
        ],
        'temp' => [
            'path' => '@storage/VTB/temp',
            'dirs' => [
                'default' => [
                    'directory'    => '',
                    'usePartition' => false
                ],
            ]
        ],
        'export' => [
            'path' => '@export/VTB',
            'dirs' => [
                'default' => [
                    'directory'     => '',
                    'useUniqueName' => false
                ],
            ]
        ],
        'import' => [
            'path' => '@import/VTB',
            'dirs' => [
                'default' => ['directory' => ''],
                'error'   => ['directory' => 'error'],
                'job'     => [
                    'directory'     => 'job',
                    'usePartition'  => false,
                    'useUniqueName' => false,
                    'ignoreSftp'    => true
                ]
            ]
        ]
    ],

    'docTypes' => [
    ],

    'components' => [
        'transport' => [
            'class' => 'addons\VTB\components\VTBTransport'
        ]
    ],
];
