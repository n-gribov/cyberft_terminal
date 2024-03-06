<?php

use common\helpers\Html;
use common\widgets\GridView;
use yii\helpers\Url;

/** @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app/menu', 'Customers dictionary');

echo Html::a(Yii::t('app/vtb', 'Request update'), ['request-update'], ['class' => 'btn btn-success', 'style' => 'margin-bottom: 15px']);
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions' => function ($model, $key, $index, $grid) {
        return ['ondblclick' => "window.location='" . Url::toRoute(['view', 'id' => $model->id]) . "'"];
    },
    'columns' => [
        ['class' => \yii\grid\SerialColumn::class],
        'customerId',
        'fullName',
        'inn',
        'terminalId',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{sendSettings} {update} {view}',
            'buttons' => [
                'sendSettings' => function ($url, $model, $key) {
                    if (empty($model->terminalId)) {
                        return '';
                    }

                    return Html::a(
                        '<span class="glyphicon glyphicon-share" title="' . Yii::t('app/vtb', 'Send configuration') . '"></span>',
                        Url::to(['send-client-terminal-settings', 'customerId' => $model->customerId])
                    );
                },
            ],
            'contentOptions' => ['class' => 'text-right'],
        ],
    ],
]);
