<?php
return [
    'id'          => 'raiffeisen',
    'label'       =>  Yii::t('app/menu', 'Raiffeisen gateway'),
    'environment' => 'DEBUG',
    'url'         => ['/raiffesien/default'],
    'iconClass'   => 'ic-bank',
    'serviceID'   => 'raiffeisen',
    'before'      => 'Users',
    'items'       => [
        [
            'label' => 'Customers dictionary',
            'url' => ['/raiffeisen/customer'],
            'permission' => 'admin',
        ],
        [
            'label' => 'Settings',
            'url' => ['/raiffeisen/settings'],
            'permission' => 'admin',
        ],
    ]
];