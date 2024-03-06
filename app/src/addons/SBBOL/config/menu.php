<?php
return [
    'id' => 'SBBOL',
    'label'       =>  Yii::t('app/menu', 'SBBOL gateway'),
    'environment' => 'DEBUG',
    'url'         => ['/SBBOL/default'],
    'iconClass'   => 'ic-bank',
    'serviceID'   => 'SBBOL',
    'before'        => 'Users',
    'items'       => [
        [
            'label' => 'Customers dictionary',
            'url' => ['/SBBOL/organization/index'],
            'permission' => 'admin',
        ],
        [
            'label' => 'Certificates',
            'url' => ['/SBBOL/certificate/index'],
            'permission' => 'admin',
        ],
        [
            'label' => 'Keys',
            'url' => ['/SBBOL/key/index'],
            'permission' => 'admin',
        ],
        [
            'label' => 'Settings',
            'url' => ['/SBBOL/settings'],
            'permission' => 'admin',
        ],
    ]
];