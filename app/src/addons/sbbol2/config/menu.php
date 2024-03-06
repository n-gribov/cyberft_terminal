<?php
return [
    'id'        => 'sbbol2',
    'label'     =>  Yii::t('app/menu', 'SBBOL gateway (new)'),
    'iconClass' => 'ic-bank',
    'serviceID' => 'sbbol2',
    'before'    => 'Users',
    'items'     => [
        [
            'label' => 'Customers dictionary',
            'url' => ['/sbbol2/customer/index'],
            'permission' => 'admin',
        ],
        [
            'label' => 'Settings',
            'url' => ['/sbbol2/settings'],
            'permission' => 'admin',
        ],
    ]
];
