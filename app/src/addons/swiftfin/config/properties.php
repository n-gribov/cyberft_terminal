<?php

return [
    'class' => 'addons\swiftfin\SwiftfinModule',
    'serviceName' => 'swiftfin',
    'defaultDataViews' => [
        'source' => [
            'label' => 'Source',
            'view' => '@addons/swiftfin/views/documents/_source',
        ],
        'container' => [
            'label' => 'Container',
            'view' => '@addons/swiftfin/views/documents/_container',
        ],
    ],
    'resources' => [
        'storage' => [
            'path' => '@storage/swiftfin',
            'dirs' => [
                'in/xml' => ['directory' => 'in/xml'],
                'in/sources' => ['directory' => 'in/sources'],
                'in/invalid' => ['directory' => 'in/invalid'],
                'out/xml' => ['directory' => 'out/xml'],
                'out/sources' => ['directory' => 'out/sources'],
                'out/invalid' => ['directory' => 'out/invalid'],
            ]
        ],
        'temp' => [
            'path' => '@storage/swiftfin/temp',
        ],
        'import' => [
            'path' => '@import/swiftfin',
            'dirs' => [
                'swift' => ['directory' => 'swift', 'usePartition' => false],
                'xml' => ['directory' => 'xml', 'usePartition' => false],
                'error' => ['directory' => 'error', 'usePartition' => false],
                'job' => ['directory' => 'job', 'usePartition' => false, 'useUniqueName' => false]
            ],
        ],
        'export' => [
            'path' => '@export/swiftfin',
            'dirs' => [
                'swift' => ['directory' => 'swift', 'usePartition' => false],
                'delivery' => ['directory' => 'delivery', 'usePartition' => false],
            ],
        ],
    ],
    'menu' => [
        'id' => 'SwiftFin',
        'label' => 'SwiftFin',
        'url' => ['/swiftfin/documents'],
        //'iconClass' => 'fa fa-list',
        'iconClass' => 'ic-globus',
        'serviceID' => 'swiftfin',
        'after' => 'Documents',
        'items' => [
            [
                'label' => 'Documents for signing',
                'url' => ['/swiftfin/documents/signing-index'],
                'permission' => \common\document\DocumentPermission::CREATE,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'extData' => 'forSigningSwiftfinCount',
                'iconClass' => 'fa fa-pencil-square-o',
            ],
            [
                'label' => 'Documents for modification',
                'url' => ['/swiftfin/documents/correction-index'],
                'permission' => \common\document\DocumentPermission::CREATE,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'extData' => 'forCorrectionSwiftfinCount',
                'iconClass' => 'fa fa-pencil-square-o',
            ],
            [
                'label' => 'Documents for user verification',
                'url' => ['/swiftfin/documents/user-verification-index'],
                'permission' => \common\document\DocumentPermission::CREATE,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'extData' => 'forVerificationSwiftfinCount',
                'iconClass' => 'fa fa-pencil-square-o',
            ],
            [
                'label' => 'Documents for authorization',
                'url' => ['/swiftfin/documents/authorization-index'],
                'permission' => \common\document\DocumentPermission::CREATE,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'extData' => 'forAuthorizationSwiftfinCount',
                'iconClass' => 'fa fa-pencil-square-o',
            ],
            [
                'label' => 'Create Document',
                'url' => ['/swiftfin/wizard/index'],
                'permission' => \common\document\DocumentPermission::CREATE,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'iconClass' => 'fa fa-file',
            ],
            [
                'label' => 'Document from file',
                'url' => ['/swiftfin/upload/'],
                'permission' => \common\document\DocumentPermission::CREATE,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'iconClass' => 'fa fa-paste',
            ],
            [
                'label' => 'Document Register',
                'url' => ['/swiftfin/documents/index'],
                'permission' => \common\document\DocumentPermission::VIEW,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'iconClass' => 'fa fa-files-o',
            ],
            [
                'label' => 'Incoming documents',
                'url' => ['/swiftfin/documents/index?SwiftFinSearch[direction]=IN'],
                'permission' => \common\document\DocumentPermission::VIEW,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'iconClass' => 'fa fa-download',
            ],
            [
                'label' => 'Outgoing documents',
                'url' => ['/swiftfin/documents/index?SwiftFinSearch[direction]=OUT'],
                'permission' => \common\document\DocumentPermission::VIEW,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'iconClass' => 'fa fa-upload',
            ],
            [
                'label' => 'Invalid documents',
                'url' => ['/swiftfin/documents/errors'],
                'permission' => \common\document\DocumentPermission::VIEW,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'iconClass' => 'fa fa-exclamation-triangle',
            ],
            [
                'label' => 'Statements MT950',
                'url' => ['/swiftfin/documents/index?SwiftFinSearch[type]=MT950'],
                'permission' => \common\document\DocumentPermission::CREATE,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'iconClass' => 'fa fa-paste',
            ],
            [
                'label' => 'Document templates',
                'url' => ['/swiftfin/templates'],
                'permission' => \common\document\DocumentPermission::CREATE,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'iconClass' => 'fa fa-files-o',
            ],
            [
                'label' => 'Settings',
                'url' => ['/swiftfin/settings'],
                'permission' => 'documentManage',
                'iconClass' => 'fa fa-settings',
            ],
            [
                'label' => 'SWIFT code directory',
                'url' => ['/swiftfin/dict-bank'],
                'permission' => \common\document\DocumentPermission::VIEW,
                'permissionParams' => ['serviceId' => \addons\swiftfin\SwiftfinModule::SERVICE_ID],
                'iconClass' => 'fa fa-settings',
            ],
        ],
    ],
    'regularJobs' => [
//        [
//            'descriptor' => 'swiftfinSign',
//            'class' => '\addons\swiftfin\jobs\SwiftFinSignJobRegular',
//            'interval' => 5,
//        ],
        [
            'descriptor' => 'swiftfinUndelivered',
            'class' => '\addons\swiftfin\jobs\SwiftFinUndeliveredJobRegular',
            'interval' => 5,
        ],
        [
            'descriptor' => 'swiftfinRegister',
            'class' => '\addons\swiftfin\jobs\ImportJob',
            'interval' => 5,
            'params' => ['deleteSource' => true]
        ],
        [
            'descriptor' => 'swiftfinTemp',
            'class' => '\addons\swiftfin\jobs\TempJob',
            'interval' => '07:00 weekday *',
        ],
    ],
];
