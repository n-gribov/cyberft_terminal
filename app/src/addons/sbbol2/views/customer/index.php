<?php

use addons\sbbol2\models\Sbbol2Customer;
use common\helpers\Html;
use common\widgets\GridView;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\helpers\Url;

$this->title = Yii::t('app/sbbol', 'Organizations');
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions' => function ($model, $key, $index, $grid) {
        return ['ondblclick' => "window.location='" . Url::toRoute(['view', 'id' => $model->id]) . "'"];
    },
    'columns' => [
        ['class' => SerialColumn::class],
        'fullName',
        'inn',
        'terminalAddress',
        'attribute' => 'customerAccessToken.statusLabel',
        [
            'class' => ActionColumn::class,
            'template' => '{sendSettings} {update} {view}',
            'contentOptions' => ['class' => 'text-right'],
            'buttons' => [
                'sendSettings' => function ($url, $model, $key) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-share" title="' . Yii::t('app/sbbol', 'Send configuration') . '"></span>',
                        Url::to(['send-client-terminal-settings', 'inn' => $model->inn])
                    );
                },
            ],
            'visibleButtons' => [
                'view' => function (Sbbol2Customer $model, $key, $index) {
                    return true;
                },
                'sendSettings' => function (Sbbol2Customer $model, $key, $index) {
                    return !empty($model->terminalAddress);
                },
            ]
        ],
    ],
]);
