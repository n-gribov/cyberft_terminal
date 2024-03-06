<?php

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['/autobot']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'Diagnostics'), 'url' => ['/diagnostic']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=
\yii\bootstrap\Nav::widget([
    'activateItems'	=> true,
    'options'	=> [
        'class'	=>'nav nav-pills',
    ],
    'items'		=> [
        ['label' => Yii::t('app/diagnostic', 'Web application'), 'url' => ['/diagnostic/index']],
        ['label' => Yii::t('app/diagnostic', 'Console application'), 'url' => ['/diagnostic/setup-console']],
        ['label' => Yii::t('app/diagnostic', 'Environment'), 'url' => ['/diagnostic/environments']],
    ]
])?>
