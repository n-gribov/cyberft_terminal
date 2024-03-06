<?php

use common\models\UserTerminal;
use common\models\User;
use common\widgets\AdvancedTabs;
use yii\helpers\Html;

$this->title = Yii::t('app/terminal', 'Terminals');

// Кнопка общей остановки работы всех терминалов
$this->beginBlock('pageAdditional');

$settings = Yii::$app->settings->get('app');
// Получить роль пользователя из активной сессии
$userIsMainAdmin = Yii::$app->user->identity->role == User::ROLE_ADMIN;
$userIsAdditionalAdmin = Yii::$app->user->identity->role == User::ROLE_ADDITIONAL_ADMIN;

// Кнопка управления процессом обмена
// доступна только главному администратору


if ($userIsMainAdmin) {
    echo Html::a(Yii::t('app/autobot', 'Create terminal'), ['/autobot/terminals/create'],
        [
            'class' => 'btn btn-success pull-left'
        ]
    );

    if ($settings->jobsEnabled) {
        echo Html::a(Yii::t('app/autobot', 'Stop automatic processes'), '#',
            [
                'class' => 'btn btn-danger',
                'data' => [
                    'toggle' => 'modal',
                    'target' => '#modal-stop-autoprocesses'
                ]
            ]
        );
    } else {
        echo Html::a(Yii::t('app/autobot', 'Start automatic processes'), '#',
            [
                'class' => 'btn btn-success',
                'data' => [
                    'toggle' => 'modal',
                    'target' => '#modal-start-autoprocesses'
                ]
            ]
        );
    }
    echo '&nbsp&nbsp';
}


// Не показываем кнопку остановки обмена,
// если у доп. администратора нет доступных терминалов

if ($userIsMainAdmin
    || ($userIsAdditionalAdmin && count(UserTerminal::getUserTerminalIndexes(Yii::$app->user->identity->id)))
) {
    echo Html::a(Yii::t('app/autobot', 'Stop all terminals exchange'), '#',
        [
            'class' => 'btn btn-danger',
            'data' => [
                'toggle' => 'modal',
                'target' => '#modal-stop-exchange'
            ]
        ]
    );
    echo '&nbsp&nbsp';
}

$this->endBlock('pageAdditional');

$data = [
    'action' => 'tabMode',
    'defaultTab' => isset($defaultTab) ? $defaultTab : 'tabProcessing',
    'tabs' => [
        'tabProcessing' => [
            'label'   => Yii::t('app/autobot', 'Terminals'),
            'content' => '@common/modules/autobot/views/settings/_processing',
        ],
        'tabExportXml' => [
            'label'   => Yii::t('app/autobot', 'Export documents'),
            'content' => '@common/modules/autobot/views/settings/_exportXml',
            'visible' => $userIsMainAdmin,
        ],
        'tabSecurity' => [
            'label'   => Yii::t('app/autobot', 'Security settings'),
            'content' => '@common/modules/autobot/views/settings/_security',
            'visible' => $userIsMainAdmin,
        ],
        'tabVerificationRule' => [
            'label'   => Yii::t('app/autobot', 'Verification rules'),
            'content' => '@common/modules/autobot/views/settings/_verification',
            'visible' => $userIsMainAdmin,
        ],
        'tabAdditionalSettings' => [
            'label'   => Yii::t('app/autobot', 'Additional settings'),
            'content' => '@common/modules/autobot/views/settings/_additional',
            'visible' => $userIsMainAdmin,
        ],
        'tabProcessingSettings' => [
            'label' => Yii::t('app/autobot', 'Processing exchange settings'),
            'content' => '@common/modules/autobot/views/settings/_processingSettings',
            'visible' => $userIsMainAdmin,
        ],
        'tabProxy' => [
            'label'   => Yii::t('app/settings', 'Proxy settings'),
            'content' => '@common/modules/autobot/views/settings/_proxySettings',
            'visible' => $userIsMainAdmin,
        ],
    ],
];

if ($userIsMainAdmin) {
    $data['tabs']['tabApiIntegration'] = [
        'label'   => Yii::t('app/autobot', 'API integration'),
        'content' => '@common/modules/autobot/views/settings/_apiIntegration',
    ];
}

echo AdvancedTabs::widget([
    'data' => $data,
    'notFoundTabContent' => '<div class="alert alert-danger" style="margin-top:20px">'.Yii::t('app/error', 'The requested page could not be found.').'</div>',
    'params' => [
        'data' => $params
    ]
]);

$this->registerCss('
    .nav-tabs {
        margin-bottom: 25px;
        padding-left: 20px;
    }

    .nav-tabs a {
        background-color: #eee;
        border-right: 1px solid #ddd !important;
        border-top: 1px solid #ddd !important;
        border-bottom: 1px solid #ddd !important;
        margin-right: 0 !important;
        padding: 5px 12px !important;
        color: #333 !important;
        border-radius: 0 !important;
    }

    .nav-tabs .active a {
        font-weight: bold;
        background-color: #fff !important;
        border-bottom-color: transparent !important;
    }
');

