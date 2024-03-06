<?php

use addons\fileact\models\FileActSearch;
use common\document\Document;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

$columns['id'] = [
    'attribute' => 'id',
    'width' => 'narrow',
    'filterInputOptions' => [
        'style' => 'margin: 0 auto;'
    ]
];

$columns['direction'] = [
    'attribute' => 'direction',
    'format' => 'html',
    'filter' => Document::getDirectionLabels(),
    'enableSorting' => true,
    'value' => function ($item, $params) {
        return "<span title=\"{$item->direction}\">" . Document::directionLabel($item->direction) . '</span>';
    },
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '100px',
        'data-none-selected-text' => ''
    ],
];

$columns['senderParticipantName'] = [
    'attribute' => 'senderParticipantName',
    'filterInputOptions' => [
        'maxLength' => 12,
        'style'     => 'width: 120px',
    ],
];

$columns['receiverParticipantName'] = [
    'attribute' => 'receiverParticipantName',
    'filterInputOptions' => [
        'maxLength' => 12,
        'style'     => 'width: 120px',
    ],
];

$columns['status'] = [
    'attribute'     => 'status',
    'format'        => 'html',
    'filter' => Select2::widget([
        'model' => $filterModel,
        'attribute' => 'status',
        'data' => $filterModel->getStatusLabels(),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'prompt' => '',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '170px',
            'containerCssClass' => 'select2-cyberft'
        ],
    ]),
    'value' => function ($item, $params) {
        return "<span title=\"Status: {$item->status}\">{$item->getStatusLabel()}</span>";
    }
];

$columns['dateCreate'] = [
    'attribute' => 'dateCreate',
    'filter' => false,
];

$columnsEnabled = [];

// Колонка с чекбоксом удаления
$columnsEnabled['delete'] = [
    'class'           => 'yii\grid\CheckboxColumn',
    'visible'         => $userCanDeleteDocuments && count($deletableDocumentsIds) > 0,
    'checkboxOptions' => function ($model, $key, $index, $column) use ($deletableDocumentsIds) {
        $hidden = !in_array($model->id, $deletableDocumentsIds);
        return [
            'style'   => 'display: ' . ($hidden ? 'none' : 'block'),
            'disabled' => $hidden,
            'class'   => 'delete-checkbox',
            'value'   => $key,
            'data-id' => (string) $model->id
        ];
    }
];

// Получение колонок, которые могут быть отображены
$columnsSettings = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

foreach($columnsSettings as $setting => $value) {
    $columnsEnabled[$setting] = $value;
}

// Обязательные колонки, которые должны отображаться в любом случае
$columnsEnabled['actions'] = [
    'attribute' => '',
    'format' => 'html',
    'filterInputOptions' => [
        'style' => 'width: 20px'
    ],
    'value' => function ($item, $params) use ($urlParams) {
        return Html::a('<span class="ic-eye"></span>', Url::toRoute(array_merge(['default/view', 'id' => $item->id], $urlParams)));
    }
];
// Создать таблицу для вывода
$myGridWidget = InfiniteGridView::begin([
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options['ondblclick'] = "window.location='" . Url::toRoute(['default/view', 'id' => $model->id]) . "'";

        if (in_array($model->status, array_merge(FileActSearch::getErrorStatus(),['']))) {
            $options['class'] = 'bg-alert-danger';
        } else if (in_array($model->status, FileActSearch::getProcessingStatus())) {
            $options['class'] = 'bg-alert-warning';
        }
        return $options;
    },
    'columns' => $columnsEnabled
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

echo ColumnsSettingsWidget::widget([
    'listType' => $listType,
    'columns' => array_keys($columns),
    'model' => $filterModel
]);