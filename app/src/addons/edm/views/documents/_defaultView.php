<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var $model Document */

$attributes = [
    'id',
    [
        'attribute' => 'direction',
        'value' => $model->getDirectionLabel(),
    ],
    'statusLabel',
    'sender',
    'receiver',
    'uuid',
    'uuidReference',
    'uuidRemote',
    'dateCreate',
    'dateUpdate',
];

if (!empty($model->dateDue)) {
    $attributes[] = [
        'attribute' => 'dateDue',
        'value' => Yii::$app->formatter->asDatetime($model->dateDue, 'php:d.m.Y H:i'),
        'label' => Yii::t('doc', 'Due'),
    ];
}

$attributes = ArrayHelper::merge($attributes, [
    [
        'attribute' => 'sourceId',
        'label' => Yii::t('other', 'Original message'),
        'format' => 'html',
        'value' => '<span class=" glyphicon glyphicon-download-alt"></span>&nbsp;'
            . Html::a(
                Yii::t('other', 'Download'),
                Url::to([
                    '/storage/download',
                    'id' => $model->getValidStoredFileId(),
                    'name' => "{$model->uuid}.src"
                ])
            ),
    ],
    'signaturesRequired',
    'signaturesCount'
]);
// Создать детализированное представление
echo DetailView::widget([
    'model' => $model,
    'attributes' => $attributes
]);
