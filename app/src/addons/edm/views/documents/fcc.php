<?php

use common\widgets\AdvancedTabs;

$this->title = Yii::t('app/menu', 'Foreign currency control');

$data = [
    'action' => 'tabMode',
    'defaultTab' => isset($defaultTab) ? $defaultTab : $availableTabs[0],
    'tabs' => [
        'tabFCI' => [
            'label' => Yii::t('edm', 'Information'),
            'content' => '@addons/edm/views/foreign-currency-control/index',
            'visible' => in_array('tabFCI', $availableTabs),
        ],
        'tabCDI' => [
            'label' => Yii::t('edm', 'Confirmation'),
            'content' => '@addons/edm/views/confirming-document-information/index',
            'visible' => in_array('tabCDI', $availableTabs),
        ],
        'tabCRR' => [
            'label' => Yii::t('edm', 'Contract requests'),
            'content' => '@addons/edm/views/contract-registration-request/index',
            'visible' => in_array('tabCRR', $availableTabs),
        ],
    ],
];

echo AdvancedTabs::widget([
    'data' => $data,
    'notFoundTabContent' => '<div class="alert alert-danger" style="margin-top:20px">'
        . Yii::t('app/error', 'The requested page could not be found.')
        . '</div>',
    'params' => [
        'dataProvider' => $dataProvider,
        'searchModel'  => $searchModel,
        'cachedEntries' => $cachedEntries,
        'listType' => $listType,
        'orgFilter' => $orgFilter,
        'accountFilter' => $accountFilter,
        'bankFilter' => $bankFilter,
        'bankNameFilter' => $bankNameFilter,
        'tabMode' => $tabMode
    ]
]);

$this->registerCss('
    .nav-tabs {
        margin-bottom: 15px;
    }
');