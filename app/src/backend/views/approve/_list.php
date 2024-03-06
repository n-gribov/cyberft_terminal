<?php

use Yii;
use common\widgets\GridView;

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
                'attribute' => 'commandId',
                'label'     => Yii::t('app', 'Command ID'),
                'options'   => [
                    'width' => 180
                ],
            ],
            [
                'attribute' => 'acceptResult',
                'label'     => Yii::t('app', 'Accept result'),
                'format'    => 'html',
                'filter'    => $filterModel->getAcceptResultLabels(),
                'value'     => function ($item, $params) {
                    return "<span title=\"Accept result: {$item->acceptResult}\">{$item->getAcceptResultLabel()}</span>";
                },
                'options' => [
                    'width' => 180
                ],
            ],
            [
                'attribute'     => 'data',
                'label'         => Yii::t('app', 'Data'),
                'filter'        => FALSE,
                'enableSorting' => FALSE,
            ],
            [
                'attribute'          => 'dateCreate',
                'filterInputOptions' => $filterInputOptions,
                'filter'             => FALSE,
                'options'            => [
                    'width' => 180
                ],
            ],
        ],
    ]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();
