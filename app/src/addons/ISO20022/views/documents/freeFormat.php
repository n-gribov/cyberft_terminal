<?php

use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DateHelper;
use common\helpers\DocumentHelper;
use common\helpers\Html;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\documents\DeleteSelectedDocumentsButton;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = Yii::t('app/menu', 'Free Format');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'ISO20022'), 'url' => Url::toRoute(['index'])];
$this->params['breadcrumbs'][] = $this->title;

$userCanDeleteDocuments = Yii::$app->user->can(DocumentPermission::DELETE, ['serviceId' => ISO20022Module::SERVICE_ID]);
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

if (Yii::$app->user->can('admin') || Yii::$app->user->can('additionalAdmin')) {
    $senderColumnAttr = 'sender';
    $receiverColumnAttr = 'receiver';
} else {
    $senderColumnAttr = 'senderParticipantName';
    $receiverColumnAttr = 'receiverParticipantName';
}

$columns['id'] = [
    'attribute' => 'id',
    'filterInputOptions' => [
        'class' => 'text-right',
        'style' => 'width: 100%;',
    ],
    'headerOptions' => [
        'class' => 'text-right',
        'style' => 'width:1%;',
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'style' => 'width: 50px;'
    ],
];

$columns['type'] = [
    'attribute' => 'type',
    'filter' =>  ISO20022Helper::getFreeFormatDocTypeLabels(),
    'format' => 'html',
    'enableSorting' => false,
    'value' => function ($item, $params) {
        return "<span title=\"{$item->type}\">{$item->getDocTypeLabel()}</span>";
    },
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => '',
        'style' => 'width:100%',
    ],
    'headerOptions' => [
        'style' => 'width:1%;',
    ],
];

$columns['msgId'] = [
    'attribute' => 'msgId',
    'format'    => 'html',
];

$columns['direction'] = [
    'attribute' => 'direction',
    'format' => 'html',
    'filter' => Document::getDirectionLabels(),
    'enableSorting' => true,
    'value' => function ($item, $params) {
        return '<span title="' . $item->direction . '">' . Document::directionLabel($item->direction) . '</span>';
    },
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'style' => 'width:100%',
        'data-none-selected-text' => ''
    ],
    'headerOptions' => [
        'style' => 'width:1%;',
    ]
];

$columns[$senderColumnAttr] = [
    'attribute' => $senderColumnAttr,
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'theme' => Select2::THEME_BOOTSTRAP,
	'attribute' => $senderColumnAttr,
        'options' => [
            'prompt' => '',
	],
	'pluginOptions' => [
            'minimumInputLength' => 0,
            'ajax' => [
                'url' => Url::to(['documents/list', 'type' => 'sender', 'page' => 'freeFormat']),
                'dataType' => 'json',
                'delay' => 250,
                'data' => new JsExpression('function(params) { return {q:params.term}; }'),
            ],
            'templateResult'     => new JsExpression('function(item) { return item.name; }'),
            'templateSelection'  => new JsExpression('function(item) { return item.name; }'),
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft',
	],
	'pluginEvents'  => [
	    'select2:select' => 'function(e) { searchForField(e.params.data) }',
            'select2:unselect' => 'function(e) {}'
	],
    ]),
    'contentOptions' => [
        'style' => 'width: 160px'
    ],
    'filterOptions' => [
        'style' => 'width: 160px'
    ]
];

$columns[$receiverColumnAttr] = [
    'attribute' => $receiverColumnAttr,
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'theme' => Select2::THEME_BOOTSTRAP,
	'attribute' => $receiverColumnAttr,
        'options' => [
            'prompt' => '',
	],
	'pluginOptions' => [
            'minimumInputLength' => 0,
            'ajax'               => [
                'url'      => Url::to(['documents/list', 'type' => 'receiver', 'page' => 'freeFormat']),
                'dataType' => 'json',
                'delay'    => 250,
                'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
            ],
            'templateResult'     => new JsExpression('function(item) { return item.name; }'),
            'templateSelection'  => new JsExpression('function(item) { return item.name; }'),
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft',
	],
	'pluginEvents'  => [
	    'select2:select' => 'function(e) { searchForField(e.params.data) }',
	],
    ]),
    'contentOptions' => [
        'style' => 'width: 160px'
    ],
    'filterOptions' => [
        'style' => 'width: 160px'
    ]
];

$columns['dateCreate'] = [
    'attribute' => 'dateCreate',
    'label'  => Yii::t('app/iso20022', 'Registered'),
    'value' => function($model) {
        return DateHelper::formatDate($model->dateCreate, 'datetime');
    },
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $searchModel,
        'attribute' => 'dateCreate',
        'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'orientation' => 'bottom'
        ],
    ]),
    'filterInputOptions' => [
        'style' => 'width:100%',
    ],
    'headerOptions' => [
        'style' => 'width:1%;',
    ],
];

$columns['subject'] = [
    'attribute' => 'subject',
    'value' => 'documentExtISO20022.subject',
    'filter' => true,
    'filterInputOptions' => [
        'style' => 'width:100%',
    ],
    'headerOptions' => [
        'style' => 'width:1%;',
    ],
];

$columns['descr'] = [
    'attribute' => 'descr',
    'value' => 'documentExtISO20022.descr',
    'filter' => true,
    'filterInputOptions' => [
        'style' => 'width:100%',
    ],
    'headerOptions' => [
        'style' => 'width:1%;',
    ]
];

$columns['typeCode'] = [
    'attribute' => 'typeCode',
    'format' => 'html',
    'filter' => ISO20022Helper::getTypeCodesLabels(),
    'value' => function ($model) use ($settingsTypeCodes) {
        if (!empty($model->extModel) && $model->extModel instanceof ISO20022DocumentExt) {
            $typeCode = $model->extModel->typeCode;
            if (isset($settingsTypeCodes[$typeCode])) {
                return $settingsTypeCodes[$typeCode][Yii::$app->language];
            } else {
                return $typeCode;
            }
        }
    },
    'filterInputOptions' => [
        'style' => 'width:100%',
    ],
    'headerOptions' => [
        'style' => 'width:1%;',
    ],
];

$columns['fileName'] = [
    'value' => 'documentExtISO20022.fileName',
    'attribute' => 'fileName',
    'filter' => true,
    'contentOptions' => [
        'style' => 'max-width: 130px; word-wrap: break-word;',
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
    'value'         => function ($item, $params) {
        return "<span title=\"Status: {$item->status}\">{$item->getStatusLabel()}</span>";
    },
];

$columns['statusCode'] = [
    'filter' => DocumentHelper::getBusinessStatusesList(),
    'attribute' => 'statusCode',
    'filterInputOptions' => [
        'style' => 'width:100%',
    ],
    'headerOptions' => [
        'style' => 'width:1%;',
    ],
    'value' => function($item) {
        return $item->getBusinessStatusTranslation();
    }
];

$columnsEnabled = [];

if ($userCanDeleteDocuments && count($deletableDocumentsIds) > 0) {
    // Колонка с чекбоксом удаления
    $columnsEnabled['delete'] = [
        'class' => 'yii\grid\CheckboxColumn',
        'checkboxOptions' => function ($model, $key, $index, $column) use ($deletableDocumentsIds) {
            $hidden = !in_array($model->id, $deletableDocumentsIds);
            return [
                'style'    => 'display: ' . ($hidden ? 'none' : 'block'),
                'disabled' => $hidden,
                'class'    => 'delete-checkbox',
                'value'    => $key,
                'data-id'  => (string) $model->id
            ];
        },
        'headerOptions' => [
            'style' => 'width:1%;',
        ],
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
    'class'    => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'buttons'  => [
        'view' => function ($url, $model, $key) use ($urlParams) {
            return Html::a('<span class="ic-eye" title="' . Yii::t("app", "View") . '"></span>',
                Url::toRoute(array_merge(['view', 'id' => $model->id, 'isoSubtype' => 'free-format'], $urlParams))
            );
        }
    ],
];
// Создать таблицу для вывода
$myGridWidget = InfiniteGridView::begin([
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'highlightsByStatus' => true,
    'rowOptions' => function ($model, $key, $index, $grid) use ($urlParams) {
        $options['ondblclick'] = "window.location='". Url::toRoute(array_merge(['view', 'id' => $model->id, 'isoSubtype' => 'free-format'], $urlParams)) ."'";
        $options['data']['status'] = $model->status;
        $options['data']['document-type'] = $model->type;
        return $options;
    },
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'columns'   => $columnsEnabled,
    'options' => ['class' => 'grid-view documents-journal-grid'],
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

echo ColumnsSettingsWidget::widget([
    'listType' => $listType,
    'columns' => array_keys($columns),
    'model' => $searchModel
]);

$this->registerCss('#delete-selected-documents-button {display: none}');

echo ToTopButtonWidget::widget();

$this->registerJS('stickyTableHelperInit();');
