<?php
/**
 * Конфигурационный файл для отображения меню ISO20022
  */
return [
    // id
    'id' => 'ISO20022',
    // Название пункта меню
    'label'       => 'ISO20022',
    // В каком окружении показывать
    'environment' => 'DEBUG',
    // Ссылка на пункт меню
    'url'         => ['/ISO20022/documents'],
    // Иконка меню
    'iconClass'   => 'ic-reload',
    // Ид сервиса
    'serviceID'   => 'ISO20022',
    // После какого пункта меню ставить
    'after'       => 'Edm',
    // Подразделы
    'items' => [
        [
            // Название подраздела
            'label'      => 'Document Register',
            // Ссылка на подраздел
            'url'        => ['/ISO20022/documents'],
            // Уровни доступа
            'permission' => \common\document\DocumentPermission::VIEW,
            // Параметры уровней доступа
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