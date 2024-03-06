<?php

use common\widgets\AdvancedTabs;
use common\models\Terminal;
use common\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var Terminal $terminal */

$this->title = $terminal->screenName;

// Кнопка для возврата в журнал настроек всех терминалов

// Кнопка общей остановки работы всех терминалов
$this->beginBlock('pageAdditional');
echo Html::a(
    Yii::t('app', 'Back'),
    ['/autobot/multiprocesses/index'],
    [
        'class' => 'btn btn-default pull-left',
        'style' => 'margin-right: 5px'
    ]
);

echo Html::a(
    Yii::t('app', 'Delete'),
    ['/autobot/terminals/delete', 'id' => $terminal->id],
    [
        'class' => 'btn btn-danger pull-left',
        'data' => [
            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]
);

if (Yii::$app->exchange->isRunning($terminal->terminalId)) {
    $title = Yii::t('app/autobot', 'Stop exchange');
    echo Html::a(
        $title,
        ['/autobot/terminals/terminal-control'],
        [
            'class' => 'btn btn-danger pull-right terminal-status-button',
            'data' => [
                'loading-text' => "<i class='fa fa-spinner fa-spin'></i>{$title}",
                'confirm-message' => Yii::t('app/autobot', 'Do you want to stop the CyberFT network exchange for this terminal?'),
                'method' => 'post',
                'params' => [
                    'action' => 'stop',
                    'terminalId' => $terminal->terminalId,
                ],
            ],
        ]
    );
} else {
    $title = Yii::t('app/autobot', 'Start exchange');
    echo Html::a(
        $title,
        ['/autobot/terminals/terminal-control'],
        [
            'class' => 'btn btn-success pull-right terminal-status-button',
            'data' => [
                'loading-text' => "<i class='fa fa-spinner fa-spin'></i>{$title}",
                'method' => 'post',
                'params' => [
                    'action' => 'start',
                    'terminalId' => $terminal->terminalId,
                ],
            ],
        ]
    );
}
$this->endBlock();

// Если терминал неактивен, не отображаем лишние вкладки
$tabs = [
    'tabTerminal' => [
        'label' => Yii::t('app/autobot', 'Terminal'),
        'content' => '@common/modules/autobot/views/terminals/_terminal',
    ]
];

if ($terminal->status == Terminal::STATUS_INACTIVE) {
    // Поместить в сессию флаг сообщения о неактивном терминале
    Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Terminal {terminal} is inactive. Can\'t modify settings', [
        'terminal' => $terminal->terminalId
    ]));
} else {
    $tabs['tabAutobot'] = [
        'label' => Yii::t('app/autobot', 'Controller'),
        'content' => '@common/modules/autobot/views/terminals/_autobot',
    ];

    $tabs['tabSigning'] = [
        'label' => Yii::t('app/autobot', 'Signing settings'),
        'content' => '@common/modules/autobot/views/terminals/_signing',
    ];

    $tabs['tabExportXml'] = [
        'label' => Yii::t('app/autobot', 'Export documents'),
        'content' => '@common/modules/autobot/views/terminals/_exportXml',
    ];

    $tabs['tabUsers'] = [
        'label' => Yii::t('app/autobot', 'Users'),
        'content' => '@common/modules/autobot/views/terminals/_users',
    ];

    $tabs['tabAdditionalSettings'] = [
        'label' => Yii::t('app/autobot', 'Additional settings'),
        'content' => '@common/modules/autobot/views/terminals/_additional',
    ];

    $tabs['tabNotifications'] = [
        'label' => Yii::t('app/menu', 'Notifications'),
        'content' => '@common/modules/monitor/views/notifications/_notificationsSettings',
    ];

    $tabs['tabApiIntegration'] = [
        'label'   => Yii::t('app/autobot', 'API integration'),
        'content' => '@common/modules/autobot/views/terminals/_apiIntegration',
    ];

    $tabs['tabVTBIntegration'] = [
        'label'   => Yii::t('app/autobot', 'VTB integration'),
        'content' => '@common/modules/autobot/views/terminals/_vtbIntegration',
    ];

    if (getenv('SHOW_SBBOL2_INTEGRATION_SETTINGS') === 'true') {
        $tabs['tabSbbol2Integration'] = [
            'label' => Yii::t('app/autobot', 'Sberbank integration'),
            'content' => '@common/modules/autobot/views/terminals/_sbbol2Integration',
        ];
    }
}

$data = [
    'action' => 'tabMode',
    'defaultTab' => isset($defaultTab) ? $defaultTab : 'tabTerminal',
    'tabs' => $tabs,
];

echo AdvancedTabs::widget([
    'data' => $data,
    'notFoundTabContent' => '<div class="alert alert-danger" style="margin-top:20px">'.Yii::t('app/error', 'The requested page could not be found.').'</div>',
    'params' => [
        'data' => $params,
        'terminal' => $terminal,
    ]
]);

$this->registerJs(<<<JS
$('.terminal-status-button').click(function () {
    var confirmMessage = $(this).data('confirm-message');
    if (confirmMessage && !confirm(confirmMessage)) {
        return false;
    }
    $(this).button('loading');
})
JS
);

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
    
    .terminal-status-button {
        padding-left: 19px;
        padding-right: 19px;
        position: relative
    }
    
    .terminal-status-button .fa.fa-spinner {
        position: absolute;
        left: 3px;
        top: 25%;
    }
');
