<?php

use Yii;
use common\widgets\GridView;
use yii\helpers\Html;

$filterInputOptions = [
    'maxLength' => 64,
];


$myGridWidget = GridView::begin([
        'emptyText'    => Yii::t('app', 'No approve matched your query'),
        'summary'      => Yii::t('other',
            'Shown from {begin} to {end} out of {totalCount} found'),
        'dataProvider' => $dataProvider,
        'filterModel'  => $filterModel,
        'columns'      => [
            [
                'attribute' => 'id',
                'options'   => [
                    'width' => 50
                ],
            ],
            [
                'attribute' => 'userId',
                'label'     => Yii::t('app', 'The creator of command'),
            ],
            [
                'attribute' => 'code',
                'options'   => [
                    'width' => 120
                ],
            ],
            [
                'attribute' => 'entity',
            ],
            [
                'attribute' => 'entityId',
            ],
            [
                'attribute' => 'acceptsCount',
            ],
            [
                'attribute'          => 'dateCreate',
                'filterInputOptions' => $filterInputOptions,
                'filter'             => FALSE,
                'options'            => [
                    'width' => 180
                ],
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons'  => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                $url);
                    }
                ],
                'options' => [
                    'width' => 30
                ]
            ],
        ],
    ]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();
