<?php
return [
    'id'          => 'VTB',
    'label'       => Yii::t('app/menu', 'VTB gateway'),
    'environment' => 'DEBUG',
    'url'         => ['/VTB/default'],
    'iconClass'   => 'ic-bank',
    'serviceID'   => 'VTB',
    'before'      => 'Users',
    'items'       => [
        [
            'label' => 'Settings',
            'url' => ['/VTB/settings'],
            'permission' => 'documentManage',
        ],
        [
            'label' => 'Customers dictionary',
            'url' => ['/VTB/customer/index'],
            'permission' => 'admin',
        ],
    ]
];
