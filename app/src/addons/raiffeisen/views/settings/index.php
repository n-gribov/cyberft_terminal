<?php

use yii\helpers\Url;
use common\widgets\AdvancedTabs;

$this->title = Yii::t('app/menu', 'Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'ISO20022'), 'url' => Url::toRoute(['/ISO20022/documents'])];
$this->params['breadcrumbs'][] = $this->title;

$data = [
    'action' => 'tabMode',
    'defaultTab' => isset($defaultTab) ? $defaultTab : 'tabCommon',
    'tabs' => [
        'tabCommon' => [
            'label' => Yii::t('app/menu', 'Common settings'),
            'content' => '@addons/raiffeisen/views/settings/_common',
        ],
        'requestsSchedule' => [
            'label' => Yii::t('app/raiffeisen', 'Documents requests'),
            'content' => '@addons/raiffeisen/views/settings/_requestsSchedule',
        ],
    ],
];

?>

<?= AdvancedTabs::widget([
    'data' => $data,
    'notFoundTabContent' => '<div class="alert alert-danger" style="margin-top:20px">'.Yii::t('app/error', 'The requested page could not be found.').'</div>',
    'params' => [
        'settings' => $settings,
    ]
]) ?>
