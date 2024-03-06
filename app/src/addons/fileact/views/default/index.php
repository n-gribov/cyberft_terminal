<?php

use addons\fileact\FileActModule;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\Html;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\documents\DeleteSelectedDocumentsButton;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = Yii::t('other', 'Document Register');
$this->params['breadcrumbs'][] = $this->title;
$userCanDeleteDocuments = Yii::$app->user->can(DocumentPermission::DELETE, ['serviceId' => FileActModule::SERVICE_ID]);
$deletableDocumentsIds = [];

if ($userCanDeleteDocuments) {
    $deletableDocumentsIds = array_reduce(
        $dataProvider->models,
        function ($carry, Document $document) {
            if ($document->isDeletable()) {
                $carry[] = $document->id;
            }
            return $carry;
        },
        []
    );

    echo DeleteSelectedDocumentsButton::widget(['checkboxesSelector' => '.delete-checkbox, .select-on-check-all']);
}

// Вывести форму поиска
echo $this->render('_search', [
    'model' => $searchModel,
    'filterStatus' => $filterStatus,
]);

if (Yii::$app->user->can('admin')) {
    $sender = 'sender';
    $receiver = 'receiver';
} else {
    $sender = 'senderParticipantName';
    $receiver = 'receiverParticipantName';
}

$columns['id'] = [
    'attribute' => 'id',
    'filterInputOptions' => [
        'style' => 'width: 50px;'
    ]
];

$columns['senderReference'] = [
    'attribute' => 'senderReference',
];

$columns['binFileName'] = [
    'attribute' => 'binFileName',
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

$columns[$sender] = [
    'attribute' => $sender,
    'filter' => true,
    'filterInputOptions' => [
        'maxLength' => 12,
        'style' => 'width: 100px'
    ],
];

$columns[$receiver] = [
    'attribute' => $receiver,
    'filter' => true,
    'filterInputOptions' => [
        'maxLength' => 12,
        'style' => 'width: 100px'
    ],
];

$columns['status'] = [
    'attribute'     => 'status',
    'format'        => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'attribute' => 'status',
        'data' => $searchModel->getStatusLabels(),
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
    },
];

$columns['dateCreate'] = [
    'attribute'          => 'dateCreate',
    'filterInputOptions' => [
        'maxLength' => 64,
    ],
    'filter' => false
];

$columnsEnabled = [];

// Колонка с чекбоксом удаления
if ($userCanDeleteDocuments && count($deletableDocumentsIds) > 0) {
    $columnsEnabled['delete'] = [
        'class'           => 'yii\grid\CheckboxColumn',
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
} else {
    $columnsEnabled['deletedEmpty'] = [];
}

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
        return Html::a('<span class="ic-eye"></span>',
            Url::toRoute(array_merge(['default/view', 'id' => $item->id, 'redirectUrl' => '/fileact/default'], $urlParams)));
    }
];
// Создать таблицу для вывода
$myGridWidget = InfiniteGridView::begin([
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'highlightsByStatus' => true,
    'rowOptions' => function ($model, $key, $index, $grid) use ($urlParams) {
        $options['ondblclick'] = "window.location='". Url::toRoute(array_merge(['view', 'id'=>$model->id, 'redirectUrl' => '/fileact/default'], $urlParams)) ."'";
        $options['data']['status'] = $model->status;
        $options['data']['document-type'] = $model->type;
        return $options;
    },
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'columns' => $columnsEnabled,
    'options' => ['class' => 'grid-view documents-journal-grid'],
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

echo ColumnsSettingsWidget::widget([
    'listType' => $listType,
    'columns' => array_keys($columns),
    'model' => $searchModel
]);

echo ToTopButtonWidget::widget();

$this->registerJS(<<<JS
    stickyTableHelperInit();
JS
);

$this->registerCss('#delete-selected-documents-button {display: none}');


