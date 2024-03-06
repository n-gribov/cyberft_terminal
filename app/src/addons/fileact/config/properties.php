<?php
return [
	'class' => 'addons\fileact\FileActModule',
	'serviceName' => 'fileact',

	'fileMask' => '*.xml',
	'fileExt' => '.xml',
	'containerExt' => '.zip',
	'patchCode' => 'X',

	'resourceXml' => 'xml',
	'resourceBin' => 'bin',
    'resourceError' => 'error',
	'resourceReceipt' => 'receipt',
	'resourceSignatures' => 'signatures',
	'resourceIn' => 'in',
	'resourceOut' => 'out',

	'resources' => [
		'storage' => [
			'path' => '@storage/fileact',
			'dirs' => [
				'in' => ['directory' => 'in'],
				'out' => ['directory' => 'out'],
			]
		],
		'temp' => [
			'path' => '@storage/fileact/temp',
		],
		'export' => [
			'path' => '@export/fileact',
			'dirs' => [
				'xml' => ['directory' => 'xml', 'usePartition' => false, 'useUniqueName' => false],
				'bin' => ['directory' => 'bin', 'usePartition' => false, 'useUniqueName' => false],
				'receipt' => ['directory' => 'receipt', 'usePartition' => false, 'useUniqueName' => false],
				'signatures' => ['directory' => 'signatures', 'usePartition' => false, 'useUniqueName' => false],
			],
		],
		'import' => [
			'path' => '@import/fileact',
			'dirs' => [
				'xml' => ['directory' => 'xml', 'usePartition' => false],
				'bin' => ['directory' => 'bin', 'usePartition' => false],
                'error' => ['directory' => 'error', 'usePartition' => false, 'useUniqueName' => false],
                'job' => ['directory' => 'job', 'usePartition' => false, 'useUniqueName' => false]
			],
		]
	],

	'docTypes' => [
		'FileAct' => [
            'contentClass' => '\addons\fileact\models\FileActCyberXmlContent',
            'extModelClass' => '\addons\fileact\models\FileActDocumentExt',
            'typeModelClass' => '\addons\fileact\models\FileActType',
			'resources' => [
				'export' => 'xml',
				'import' => 'xml'
			],
			'jobs' => [
				'export' => '\addons\fileact\jobs\ExportJob',
			],
			'dataView' => '@addons/fileact/views/default/_view',
			'views' => [
                'content' => [
                    'label' => 'Content',
                    'view' => '@addons/fileact/views/default/_content',
                ],
			]
		],
	],

	'menu' => [
		'id' => 'FileAct',
		'label' => 'FileAct',
        'url' => ['/fileact/default'],
		'iconClass' => 'ic-mail',
        'serviceID' => 'fileact',
        'after' => 'FinZip',
		'items' => [
			[
				'label' => 'Documents for signing',
				'url' => ['/fileact/documents/signing-index'],
				'permission' => \common\document\DocumentPermission::SIGN,
                'permissionParams' => ['serviceId' => \addons\fileact\FileActModule::SERVICE_ID],
                'extData' => 'forSigningFileactCount',
				'iconClass' => 'fa fa-pencil-square-o',
			],
			[
				'label' => 'Create',
				'url' => ['/fileact/wizard'],
				'permission' => \common\document\DocumentPermission::CREATE,
                'permissionParams' => ['serviceId' => \addons\fileact\FileActModule::SERVICE_ID],
			],
			[
				'label' => 'Registry',
				'url' => ['/fileact/default'],
				'permission' => \common\document\DocumentPermission::VIEW,
                'permissionParams' => ['serviceId' => \addons\fileact\FileActModule::SERVICE_ID],
			],
            [
                'label' => 'Settings',
                'url' => ['/fileact/settings'],
                'permission' => 'documentManage'
            ],
		]
	],

	'regularJobs' => [
//		[
//			'descriptor' => 'fileactSign',
//			'class' => '\addons\fileact\jobs\FileActSignJobRegular',
//			'interval' => 5,
//		],
		[
			'descriptor' => 'fileactImport',
			'class' => '\addons\fileact\jobs\ImportJob',
			'interval' => 60,
		],
		[
			'descriptor' => 'fileactTemp',
			'class' => '\addons\fileact\jobs\TempJob',
			'interval' => '07:00 weekday *',
		],
	],
];