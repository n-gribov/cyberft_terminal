<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

// Создать детализированное представление
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'pduStorageId',
            'label' => Yii::t('app/fileact', 'PDU file'),
            'format' => 'html',
            'value' => Html::a(Yii::t('app', 'Download file'), [
                '/storage/download', 'id' => $model->extModel->pduStoredFileId], ['class' => 'btn btn-primary'])
        ],
        [
            'attribute' => 'binStorageId',
            'label' => Yii::t('app/fileact', 'BIN file'),
            'format' => 'html',
            'value' => Html::a(Yii::t('app', 'Download file'), [
                '/storage/download', 'id' => $model->extModel->binStoredFileId], ['class' => 'btn btn-primary'])
        ]
    ]
]);
