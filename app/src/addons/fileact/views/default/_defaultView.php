<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$attributes = [
    'uuid',
    'dateCreate',
    'sender',
    'receiver',
    'signaturesRequired',
    'signaturesCount',
    $model->extModel->pduStoredFileId
    ? ([
        'attribute' => 'pduStoredFileId',
        'label' => Yii::t('app/fileact', 'PDU file'),
        'format' => 'html',
        'value' => Html::a(
            Yii::t('app', 'Download file'),
            ['/storage/download', 'id' => $model->extModel->pduStoredFileId],
            ['class' => 'btn btn-primary']
        )
    ])
    : ([
        'attribute' => 'pduStoredFileId',
        'label' => Yii::t('app/fileact', 'PDU file'),
        'value' => Yii::t('other', 'File yet unprocessed'),
    ]),
    $model->extModel->binStoredFileId
    ? ([
        'attribute' => 'binStorageId',
        'label' => Yii::t('app/fileact', 'BIN file'),
        'format' => 'html',
        'value' => Html::a(
            Yii::t('app', 'Download file'),
            ['/storage/download', 'id' => $model->extModel->binStoredFileId],
            ['class' => 'btn btn-primary']
        )
    ])
    : ([
        'attribute' => 'binStoredFileId',
        'label' => Yii::t('app/fileact', 'BIN file'),
        'value' => Yii::t('other', 'File yet unprocessed'),
    ]),
];
// Создать детализированное представление
echo DetailView::widget([
    'model' => $model,
    'attributes' => $attributes
]);
