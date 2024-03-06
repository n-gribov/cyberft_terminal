<?php
return [
    'class' => 'addons\finzip\FinZipModule',
    'serviceName' => 'finzip',
    'resources' => [
        'storage' => [
            'path' => '@storage/finzip',
            'dirs' => [
                'in' => ['directory' => 'in'],
                'inEnc' => ['directory' => 'in', 'adapterClass' => 'common\components\storage\EncryptedPartitionedAdapter'],
                'out' => ['directory' => 'out'],
                'outEnc' => ['directory' => 'out', 'adapterClass' => 'common\components\storage\EncryptedPartitionedAdapter'],
            ]
        ],
        'temp' => [
            'path' => '@storage/finzip/temp',
        ],
        'export' => [
            'path' => '@export/finzip',
            'dirs' => [
                'default' => ['usePartition' => false]
            ]
        ],
//		'import' => [
//			'path' => '@import/finzip',
//			'dirs' => [
//				'default' => ['usePartition' => false],
//                'job' => ['directory' => 'job', 'usePartition' => false, 'useUniqueName' => false]
//			]
//		]
	],

	'docTypes' => [
            'FINZIP' => [
                'contentClass' => '\addons\finzip\models\FinZipCyberXmlContent',
                'extModelClass' => '\addons\finzip\models\FinZipDocumentExt',
                'typeModelClass' => '\addons\finzip\models\FinZipType',
                'jobs' => [
                    'export' => '\addons\finzip\jobs\ExportJob',
                ],
                'dataView' => '@addons/finzip/views/default/_view',
                'views' => [
                    'content' => [
                        'label' => 'Content',
                        'view' => '@addons/finzip/views/default/_content',
                    ],
                ]
            ],
	],

	'menu' => [
            'id' => 'FinZip',
            'label' => 'Free Format',
            'url' => ['/finzip/default'],
            'environment' => 'DEBUG',
            'iconClass' => 'ic-download',
            'serviceID' => 'finzip',
            'after' => 'SwiftFin',
            'items' => [
                [
                    'label' => 'Documents for signing',
                    'url' => ['/finzip/documents/signing-index'],
                    'permission' => \common\document\DocumentPermission::SIGN,
                    'permissionParams' => ['serviceId' => \addons\finzip\FinZipModule::SERVICE_ID],
                    'extData' => 'forSigningFinzipCount',
                    'iconClass' => 'fa fa-pencil-square-o',
                ],
                [
                    'label' => 'Create',
                    'url' => ['/finzip/wizard'],
                    'permission' => \common\document\DocumentPermission::CREATE,
                    'permissionParams' => ['serviceId' => \addons\finzip\FinZipModule::SERVICE_ID],
                ],
                [
                    'label' => 'Registry',
                    'url' => ['/finzip/default'],
                    'permission' => \common\document\DocumentPermission::VIEW,
                    'permissionParams' => ['serviceId' => \addons\finzip\FinZipModule::SERVICE_ID],
                    'extData' => 'newFinZipCount'
                ],
            ]
	],

	'regularJobs' => [
        [
            'descriptor' => 'finzipTemp',
            'class' => '\addons\finzip\jobs\TempJob',
            'interval' => '07:00 weekday *',
        ],
        [
            'descriptor' => 'finzipImportFromEmail',
            'class' => '\addons\finzip\jobs\ImportFromEmailJob',
            'interval' => 10,
        ]
    ],
];
