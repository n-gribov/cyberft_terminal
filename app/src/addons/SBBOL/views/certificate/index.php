<?php

use addons\SBBOL\models\SBBOLCertificate;
use common\helpers\Html;
use common\widgets\GridView;

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = Yii::t('app/menu', 'Certificates');

echo Html::a(
    Yii::t('app/sbbol', 'Request update'),
    ['request-update'],
    ['class' => 'btn btn-success']
);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => \yii\grid\SerialColumn::class],
        'commonName',
        'typeLabel',
        'fingerprint',
        'serial',
        'statusLabel',
        'validFrom',
        'validTo',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{download}',
            'buttons' => [
                'download' => function($model, $key, $index) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-download"></span>',
                        $model
                    );
                }
            ],
            'contentOptions' => ['class' => 'text-right', 'style' => 'white-space: nowrap;'],
        ],
    ],
]);
