<?php
return [
    'roles' => [
        'user',
        'admin',
        'controller',
        'lso',
        'rso',
        'additionalAdmin'
    ],
    'permissions' => [
        'access' => [
            'service' => [
                'rule' => '\common\rbac\rules\AccessServiceRule',
                'description' => 'Access to addon',
                'access' => [
                    'admin', 'user', 'lso', 'rso', 'controller', 'additionalAdmin'
                ],
            ],
        ],
        'document' => [
            'view' => [
                'rule' => '\common\rbac\rules\document\ViewRule',
                'description' => 'Document view',
                'access' => [
                    'admin', 'user', 'lso', 'rso', 'controller', 'additionalAdmin'
                ]
            ],
            'create' => [
                'rule' => '\common\rbac\rules\document\CreateRule',
                'description' => 'Document create',
                'access' => [
                    'user'
                ]
            ],
            'delete' => [
                'rule' => '\common\rbac\rules\document\DeleteRule',
                'description' => 'Document delete',
                'access' => [
                    'user'
                ]
            ],
            'sign' => [
                'rule' => '\common\rbac\rules\document\SignRule',
                'description' => 'Document sign',
                'access' => [
                    'user'
                ]
            ],
            'importErrors' => [
                'rule' => '\common\rbac\rules\ImportErrorsPermissionRule',
                'description' => 'Document delete',
                'access' => [
                    'admin', 'user'
                ]
            ],
            'manage' => [
                'description' => 'Document control',
                'access' => [
                    'admin', 'additionalAdmin'
                ]
            ],
            'controllerVerification' => [
                'description' => 'Documents for controller verification',
                'access' => [
                    'controller'
                ]
            ],
            'vtbDocuments' => [
                'rule' => '\common\rbac\rules\VTBDocumentsRule',
                'description' => 'Access VTB documents',
                'access' => [
                    'user'
                ]
            ],
        ],
        'page' => [
            'view' => [
                'description' => 'Wiki page view',
                'access' => [
                    'admin', 'user', 'lso', 'rso','controller', 'additionalAdmin'
                ]
            ],
            'manage' => [
                'rule' => '\common\rbac\rules\PageManagementPermissionRule',
                'description' => 'Document delete',
                'access' => [
                    'admin', 'user', 'additionalAdmin'
                ]
            ],
        ],
        'common' => [
            'document' => [
                'description' => 'Documents for all',
                'access' => [
                    'admin',
                    'user',
                    'additionalAdmin'
                ],
            ],
            'documentAdmin' => [
                'description' => 'Documents for menu',
                'access' => [
                    'admin',
                    'additionalAdmin',
                    'controller'
                ],
            ],
            'admins' => [
                'description' => 'All admins access',
                'access' => [
                    'admin',
                    'additionalAdmin',
                ],
            ],
            'accessMainAdmin' => [
                'rule' => '\common\rbac\rules\MainAdminPermissionRule',
                'description' => 'Main admin access',
                'access' => [
                    'admin',
                    'additionalAdmin'
                ],
            ],
            'settings' => [
                'description' => 'System settings',
                'access' => [
                    'admin',
                    'additionalAdmin'
                ]
            ],
            'report' => [
                'description' => 'System report',
                'access' => [
                    'admin',
                    'additionalAdmin'
                ],
            ],
            'approve' => [
                'description' => 'Approve',
                'access' => [
                    'admin',
                    'additionalAdmin',
                    'user',
                ],
            ],
            'users' => [
                'description' => 'System users',
                'access' => [
                    'admin',
                    'additionalAdmin',
                    'lso',
                    'rso',
                ]
            ],
            'eventlog' => [
                'description' => 'Transport document log',
                'access' => [
                    'user',
                    'admin',
                    'additionalAdmin',
                    'lso',
                    'rso'
                ]
            ],
            'certificates' => [
                'rule' => '\common\rbac\rules\CertsViewPermissionRule',
                'description' => 'Certificates',
                'access' => [
                    'admin', 'user', 'additionalAdmin'
                ]
            ],
            'certificatesStatusManagement' => [
                'rule' => '\common\rbac\rules\CertStatusManagementPermissionRule',
                'description' => 'Manage certificates status',
                'access' => [
                    'admin', 'user', 'additionalAdmin'
                ]
            ],
            'participants' => [
                'description' => 'Participants',
                'access' => [
                    'admin',
                    'additionalAdmin',
                    'user',
                ]
            ],
            'myKeysCertificates' => [
                'description' => 'My keys and certificates',
                'access' => [
                    'user',
                ]
            ],
            'tour' => [
                'description' => 'First start system tour',
                'access' => [
                    'admin',
                    'additionalAdmin'
                ]
            ],
            'statistic' => [
                'description' => 'Statistics page',
                'access' => [
                    'user',
                    'admin',
                    'additionalAdmin',
                    'lso',
                    'rso',
                ]
            ],
            'help' => [
                'description' => 'Help',
                'access' => [
                    'user',
                    'admin',
                    'additionalAdmin'
                ]
            ],
        ],
        'action' => [
            'accept' => [
                'description' => 'Command accepting',
                'access' => [
                    'lso',
                    'rso',
                ],
            ],
        ],
    ]
];