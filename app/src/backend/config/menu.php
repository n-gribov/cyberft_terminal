<?php
return [
    'class' => 'common\components\RbacMenu',
    'items' => [
        [
            'id' => 'Certificates',
            'label' => 'Certificates',
            'url' => ['/certManager/cert/index'],
            'permission'	=> 'commonCertificates',
            'iconClass'	=> 'ic-key',
        ],
        [
            'id' => 'MyKeysCertificates',
            'label' => 'My keys',
            'url' => ['/certManager/cert/userkeys'],
            'permission'	=> 'commonMyKeysCertificates',
            'iconClass'	=> 'ic-key',
        ],
        [
            'id' => 'Documents',
            'label' => 'Documents',
            'url' => ['/document/index'],
            'menuId' => 'document',
            'permission' => 'commonAdmins',
            'iconClass' => 'ic-case',
        ],
        [
            'id' => 'Users',
            'label' => Yii::t('app', 'Users'),
            'url' => ['/user/index'],
            'menuId' => 'user',
            'permission' => 'commonUsers',
            'iconClass' => 'ic-user',
        ],
        [
            'id' => 'Terminals',
            'label' => 'Terminals',
            'url' => ['/autobot/multiprocesses/index'],
            'permission' => 'commonSettings',
            'iconClass'	=> 'ic-cog',
        ],
        [
            'id' => 'Notifications',
            'label' => 'Notifications',
            'url' => ['/monitor/notifications/index'],
            'permission' => 'admin',
            'iconClass'	=> 'ic-cog',
        ],
        [
            'id' => 'Members',
            'label' => 'CyberFT Members',
            'url' => ['/participant'],
            'permission'	=> 'commonParticipants',
            'iconClass'	=> 'ic-list',
        ],
        [
            'id' => 'MonitorLog',
            'label' => 'Events registry',
            'url' => ['/monitor/log'],
            'permission'	=> 'commonSettings',
            'iconClass'	=> 'ic-list',
        ],
        [
            'id' => 'ImportErrors',
            'label' => 'Import errors',
            'url' => ['/document/import-errors'],
            'permission'	=> 'documentImportErrors',
            'iconClass'	=> 'ic-list',
            'extData' => 'importErrorsCount'
        ],
        [
            'id' => 'Help',
            'label' => 'Help',
            'url' => ['/help'],
            'menuId' => 'help',
            'iconClass' => 'ic-attention',
            'items' => [
                [
                    'label' => 'About',
                    'url' => ['/help/about'],
                ],
                [
                    'label' => 'Documentation',
                    'url' => ['/help/wiki'],
                ],
            ],
        ],
    ],
];
