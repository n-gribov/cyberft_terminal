<?php
return [
    'translations' => [
        'app*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@common/messages',
            'fileMap' => [
                'app' => 'app.php',
                'app/autobot' => 'autobot.php',
                'app/cert' => 'cert.php',
                'app/country' => 'country.php',
                'app/diagnostic' => 'diagnostic.php',
                'app/error' => 'error.php',
                'app/fileact' => 'fileact.php',
                'app/finzip' => 'finzip.php',
                'app/iso20022' => 'iso20022.php',
                'app/menu' => 'menu.php',
                'app/message' => 'message.php',
                'app/participant' => 'participant.php',
                'app/profile' => 'profile.php',
                'app/raiffeisen' => 'raiffeisen.php',
                'app/sbbol' => 'sbbol.php',
                'app/sbbol2' => 'sbbol2.php',
                'app/settings' => 'settings.php',
                'app/swiftfin' => 'swiftfin.php',
                'app/terminal' => 'terminal.php',
                'app/user' => 'user.php',
                'app/validation' => 'validation.php',
                'app/vtb' => 'vtb.php',
            ],
        ],
        'edm*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@common/messages',
            'fileMap' => [
                'edm' => 'edm.php',
            ],
        ],
        'doc*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@common/messages',
            'fileMap' => [
                'doc' => 'document.php',
                'doc/mt' => 'document-mt.php',
                'doc/st' => 'statement.php',
                'doc/swiftfin' => 'swiftfin.php',
            ],
        ],
        'tour*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@common/messages',
            'fileMap' => [
                'tour' => 'tour.php',
            ],
        ],
        'other*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@common/messages',
            'fileMap' => [
                'other' => 'other.php',
            ],
        ],
        'monitor*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@common/modules/monitor/messages',
            'fileMap' => [
                'monitor/mailer' => 'mailer.php',
                'monitor/events' => 'events.php',
                'monitor' => 'other.php',
            ],
        ],
    ],
];