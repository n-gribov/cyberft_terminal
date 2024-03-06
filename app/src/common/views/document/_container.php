<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

// Создать детализированное представление
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'dateUpdate',
        [
            'attribute' => 'actualStoredFileId',
            'lable' => '',
            'format' => 'html',
            'value' => Html::a(
                Yii::t('app', 'Download'),
                Url::toRoute(['/storage/download', 'id' => $model->actualStoredFileId]),
                ['class' => 'btn btn-primary']
            ),
            'visible' => !is_null($model->actualStoredFileId),
        ],
        [
            'attribute' => 'encryptedStoredFileId',
            'lable' => '',
            'format' => 'html',
            'value' => Html::a(
                Yii::t('app', 'Download'),
                Url::toRoute(['/storage/download', 'id' => $model->encryptedStoredFileId]),
                ['class' => 'btn btn-primary']
            ),
            'visible' => !is_null($model->encryptedStoredFileId),
        ],
    ]
]);
