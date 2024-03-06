<?php

use common\document\Document;
use common\document\DocumentSearch;
use yii\data\ActiveDataProvider;
use common\widgets\GridView;
use yii\helpers\Url;
use common\helpers\Html;
use kartik\widgets\Select2;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;

/* @var $this View */
/* @var $searchModel DocumentSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Documents with errors');
$this->params['breadcrumbs'][] = $this->title;

$isAdmin = true;

if (Yii::$app->user->can('admin')) {
    $sender = 'sender';
    $receiver = 'receiver';
} else {
    $sender = 'senderParticipantName';
    $receiver = 'receiverParticipantName';
}

$columns = [];

$columns['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'style' => 'width: 5%'
    ]
];

$columns['type'] = 'type';

$columns['direction'] = [
    'attribute' => 'direction',
    'format' => 'html',
    'filter' => Document::getDirectionLabels(),
    'enableSorting' => true,
    'value' => function ($item, $params) {
        return '<span title="' . $item->direction . '">' . Document::directionLabel($item->direction) . '</span>';
    },
    'filterInputOptions' => [
        'style' => 'width: 100%',
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ""
    ],
];

$columns['sender'] = [
    'attribute' => $sender,
    'filter' => true,
    'filterInputOptions' => [
        'maxLength' => 12,
    ],
    'format' => 'html',
];

$columns['receiver'] = [
    'attribute' => $receiver,
    'filter' => true,
    'filterInputOptions' => [
        'maxLength' => 12,
    ],
    'format' => 'html'
];

$columns['status'] = [
    'attribute'     => 'status',
    'format'        => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'attribute' => 'status',
        'data' => DocumentSearch::getErrorStatusLabels(),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'prompt' => '',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft'
        ],
    ]),
    'headerOptions' => [
        'style' => 'width: 15%',
    ],
    'value' => function ($item, $params) {
        return "<span title=\"Status: {$item->status}\">{$item->getStatusLabel()}</span>";
    }
];

$columns['dateCreate'] = [
    'attribute'          => 'dateCreate',
    'filterInputOptions' => [
        'maxLength' => 64,
    ],
    'filter'             => false
];

echo $this->render('_search', [
    'model' => $searchModel,
    'filterStatus' => $filterStatus,
]);

// Получение колонок, которые могут быть отображены
$columnsEnabled = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

// Обязательные колонки, которые должны отображаться в любом случае
$columnsEnabled['view'] = [
    'attribute' => '',
    'format' => 'html',
    'filterInputOptions' => [
        'style'     => 'width: 20px'
    ],
    'value'	=> function ($item, $params) use ($urlParams) {
        return Html::a('<span class="ic-eye"></span>',
            Url::toRoute(array_merge(['view', 'id' => $item->id, 'redirectUrl' => '/document/index'], $urlParams)), ['title' => 'Просмотр']);
    }
];

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function ($model, $key, $index, $grid) use ($urlParams) {
        $options['ondblclick'] = "window.location='". Url::toRoute(array_merge(['view', 'id' => $model->id, 'redirectUrl' => '/document/index'], $urlParams)) ."'";

        if (in_array($model->status, array_merge(Document::getErrorStatus(),['']))) {
            $options['class'] = 'bg-alert-danger';
        } elseif (in_array($model->status, Document::getProcessingStatus())) {
            $options['class'] = 'bg-alert-warning';
        }
        return $options;
    },
    'columns' => $columnsEnabled
]);

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $searchModel
    ]
);

// Модальное окно формы поиска
echo $this->render('@addons/edm/views/documents/_searchModal', ['model' => $searchModel]);

?>