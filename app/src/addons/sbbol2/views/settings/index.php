<?php

use common\widgets\AdvancedTabs;

$this->title = Yii::t('app/menu', 'Settings');

$data = [
    'action' => 'tabMode',
    'defaultTab' => isset($defaultTab) ? $defaultTab : 'tabCommon',
    'tabs' => [
        'tabCommon' => [
            'label' => Yii::t('app/menu', 'Common settings'),
            'content' => '@addons/sbbol2/views/settings/_common',
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
