<?php

use common\widgets\AdvancedTabs;

$this->title = Yii::t('app/menu', 'Настройки оповещений');
$this->params['breadcrumbs'][] = $this->title;

$data = [
    'action' => 'tabMode',
    'defaultTab' => isset($defaultTab) ? $defaultTab : 'tabNotificationsSettings',
    'tabs' => [
        'tabNotificationsSettings' => [
            'label' => Yii::t('app/menu', 'Notification Settings'),
            'content' => '@common/modules/monitor/views/notifications/_notificationsSettings',
        ],
        'tabMailNotificationsSettings' => [
            'label' => Yii::t('app/autobot', 'Параметры рассылки оповещений'),
            'content' => '@common/modules/monitor/views/notifications/_mailNotificationsSettings',
        ],
        'tabFinZipEmailImportSettings' => [
            'label' => Yii::t('app/finzip', 'Email import settings'),
            'content' => '@common/modules/monitor/views/notifications/_finZipEmailImportSettings',
        ],
    ],
];

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

