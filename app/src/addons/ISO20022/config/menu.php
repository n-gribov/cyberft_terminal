<?php
return [
    'id' => 'ISO20022',
    'label'       => 'ISO20022',
    'environment' => 'DEBUG',
    'url'         => ['/ISO20022/documents'],
    'iconClass'   => 'ic-reload',
    'serviceID'   => 'ISO20022',
    'after'       => 'Edm',
    'items'       => [
        [
            'label'      => 'Document Register',
            'url'        => ['/ISO20022/documents'],
            'permission' => \common\document\DocumentPermission::VIEW,
            'permissionParams' => ['serviceId' => \addons\ISO20022\ISO20022Module::SERVICE_ID],
	],
        [
            'label'      => 'Free Format',
            'url'        => ['/ISO20022/documents/free-format'],
            'permission' => \common\document\DocumentPermission::VIEW,
            'permissionParams' => ['serviceId' => \addons\ISO20022\ISO20022Module::SERVICE_ID],
        ],
        [
            'label'      => 'Payment Documents',
            'url'        => ['/ISO20022/documents/payments'],
            'permission' => \common\document\DocumentPermission::VIEW,
            'permissionParams' => ['serviceId' => \addons\ISO20022\ISO20022Module::SERVICE_ID],
        ],
        [
            'label'      => 'Statement Document Register',
            'url'        => ['/ISO20022/documents/statements'],
            'permission' => \common\document\DocumentPermission::VIEW,
            'permissionParams' => ['serviceId' => \addons\ISO20022\ISO20022Module::SERVICE_ID],
        ],
        [
            'label'      => 'Foreign currency control',
            'url'        => ['/ISO20022/documents/foreign-currency-control'],
            'permission' => \common\document\DocumentPermission::VIEW,
            'permissionParams' => ['serviceId' => \addons\ISO20022\ISO20022Module::SERVICE_ID],
        ],
        [
            'label' => 'Create free format document',
            'url' => ['/ISO20022/wizard'],
            'permission' => \common\document\DocumentPermission::CREATE,
            'permissionParams' => ['serviceId' => \addons\ISO20022\ISO20022Module::SERVICE_ID],
            'iconClass'	=> 'fa fa-settings',
        ],
        [
            'label' => 'Settings',
            'url' => ['/ISO20022/settings'],
            'permission' => 'documentManage',
            'iconClass'	=> 'fa fa-settings',
        ],
    ]
];