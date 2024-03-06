<?php

use addons\swiftfin\SwiftfinModule;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\Currencies;
use common\helpers\DocumentHelper;
use common\helpers\Html;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\documents\DeleteSelectedDocumentsButton;
use common\widgets\documents\SelectedDocumentsCountLabel;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

/** @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('other', 'SwiftFin Document Register');

$userCanDeleteDocuments = Yii::$app->user->can(DocumentPermission::DELETE, ['serviceId' => SwiftfinModule::SERVICE_ID]);
$userCanCreateDocuments = Yii::$app->user->can(DocumentPermission::CREATE, ['serviceId' => SwiftfinModule::SERVICE_ID]);
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
    echo SelectedDocumentsCountLabel::widget(['checkboxesSelector' => '.delete-checkbox, .select-on-check-all']);
}

/**
 * Если текущий url, это страница ошибочных документов
 * то добавляем значение from для работы кнопки Назад
 */

$currentUrl = Yii::$app->request->url;

if (strpos($currentUrl, 'errors') !== false) {
    $urlParams['from'] = 'errors';
}

if (Yii::$app->user->can('admin') || Yii::$app->user->can('additionalAdmin')) {
    $sender = 'sender';
    $receiver = 'receiver';
} else {
    $sender = 'senderParticipantName';
    $receiver = 'receiverParticipantName';
}

if ($listType == 'swiftErrors') {
    $page = 'errors';
} else {
    $page = 'index';
}

$columns['id'] = [
    'attribute' => 'id',
    'filterInputOptions' => [
        'maxLength' => 12,
    ],
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
];

$columns['type'] = [
    'attribute' => 'type',
    'filter' =>  $searchModel->getDocTypeLabels(),
    'format' => 'html',
    'value' => function ($item, $params) {
        return "<span title=\"{$item->type}\">{$item->getDocTypeLabel()}</span>";
    },
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
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
        'data-none-selected-text' => ''
    ],
];

$columns[$sender] = [
    'attribute' => $sender,
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'theme' => Select2::THEME_BOOTSTRAP,
	'attribute' => $sender,
        'options' => [
            'prompt' => '',
	],
	'pluginOptions' => [
		'minimumInputLength' => 0,
		'ajax'               => [
		    'url'      => Url::to(['documents/list', 'type' => 'sender', 'page' => $page]),
		    'dataType' => 'json',
		    'delay'    => 250,
		    'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
		],
		'templateResult'     => new JsExpression('function(item) {
			    return item.name;
			}'),
		'templateSelection'  => new JsExpression('function(item) {
			    return item.name;
			}'),
		'allowClear' => true,
		'containerCssClass' => 'select2-cyberft',
	],
	'pluginEvents'  => [
	    'select2:select' => 'function(e) {
	        searchForField(e.params.data)
	    }',
            'select2:unselect' => 'function(e) {

	      }'
	],
    ]),
    'contentOptions' => [
        'style' => 'width: 160px'
    ],
    'filterOptions' => [
        'style' => 'width: 160px'
    ]
];

$columns[$receiver] = [
    'attribute' => $receiver,
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'theme' => Select2::THEME_BOOTSTRAP,
	'attribute' => $receiver,
        'options' => [
            'prompt' => '',
	],
	'pluginOptions' => [
		'minimumInputLength' => 0,
		'ajax'               => [
		    'url'      => Url::to(['documents/list', 'type' => 'receiver', 'page' => $page]),
		    'dataType' => 'json',
		    'delay'    => 250,
		    'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
		],
		'templateResult'     => new JsExpression('function(item) {
			    return item.name;
			}'),
		'templateSelection'  => new JsExpression('function(item) {
			    return item.name;
			}'),
		'allowClear' => true,
		'containerCssClass' => 'select2-cyberft',
	],
	'pluginEvents'  => [
	    'select2:select' => 'function(e) {
	        searchForField(e.params.data)
	    }',
	],
    ]),
    'contentOptions' => [
        'style' => 'width: 160px'
    ],
    'filterOptions' => [
        'style' => 'width: 160px'
    ]
];

$columns['status'] = [
    'attribute' => 'status',
    'format'        => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'attribute' => 'status',
        'data' => DocumentHelper::getStatusLabelsAll(),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'prompt' => '',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft'
        ],
    ]),
    'value' => function ($item, $params) {
        $status = DocumentHelper::getStatusLabel($item);
        return "<span title=\"Status: {$status['name']}\">{$status['label']}</span>";
    },
];

$columns['dateCreate'] =[
    'attribute'          => 'dateCreate',
    'filter' => kartik\widgets\DatePicker::widget(
        [
            'model' => $searchModel,
            'attribute' => 'dateCreate',
            'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                'orientation' => 'bottom'
            ],
            'options' => [
                'class' => 'form-control',
                'style' => 'width: 130px; text-align: right;'
            ],
        ]
    ),
];

$columns['operationReference'] = [
    'attribute' => 'operationReference',
    'label'     => Yii::t('doc', 'Operation reference'),
    'format' => 'html',
];

$columns['currency'] = [
    'attribute' => 'currency',
    'label' => Yii::t('doc', 'Currency'),
    'filter' => Currencies::getCodeLabels(),
    'filterInputOptions' =>  [
        'maxLength' => 3,
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
    'value' => function($item, $params) use ($searchModel) {
        if ($item->typeGroup == 'edm') {
            if ($item->documentExtForeignCurrencyOperation) {
                return $item->documentExtForeignCurrencyOperation->currency;
            }
        } else {
            if ($item->documentExtSwiftFin) {
                return $item->documentExtSwiftFin->currency;
            }
        }
    }
];

$columns['sum'] =[
    'attribute' => 'sum',
    'format' => ['decimal', 2],
    'label' => Yii::t('doc', 'Total sum'),
    'filterInputOptions' => [
        'maxLength' => 64,
    ],
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'value' => function($item, $params) use ($searchModel) {
        if ($item->typeGroup == 'edm') {
            if ($item->documentExtForeignCurrencyOperation) {
                return $item->documentExtForeignCurrencyOperation->currencySum;
            }
        } else {
            if ($item->documentExtSwiftFin) {
                return $item->documentExtSwiftFin->sum;
            }
        }
    }
];

$columns['valueDate'] = [
    'attribute'          => 'documentExtSwiftFin.valueDate',
    'format'            => ['date', 'php:Y-m-d'],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $searchModel,
        'attribute' => 'valueDate',
        'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'orientation' => 'bottom'
        ],
        'options' => [
            'class' => 'form-control',
        ]
    ]),
];

if ($searchModel->highlights) {
    $columns['body'] = [
        'label' => Yii::t('doc', 'Document body'),
        'format' => 'html',
        'value' => function($item, $params) use($searchModel) {
            if (isset($searchModel->highlights[$item->id]['body'])) {
                return $searchModel->highlights[$item->id]['body'][0];
            } else {
                return '';
            }
        }
    ];
}

$columnsEnabled = [];

if ($userCanDeleteDocuments && count($deletableDocumentsIds) > 0) {
    // Колонка с чекбоксом для удаления
    $columnsEnabled['delete'] = [
        'class'   => 'yii\grid\CheckboxColumn',
        'checkboxOptions' => function($model, $key, $index, $column) use ($deletableDocumentsIds) {
            $hidden = !in_array($model->id, $deletableDocumentsIds);
            return [
                'style'    => "display: " . ($hidden ? 'none': 'block'),
                'disabled' => $hidden,
                'value'    => $key,
                'class'    => 'delete-checkbox',
                'data-id'  => (string) $model->id
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
    'class'    => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'buttons'  => [
        'view' => function ($url, $model, $key) use ($urlParams) {
            return Html::a('<span class="ic-eye"></span>',
                Url::toRoute(
                    array_merge(['view', 'id' => $model->id, 'redirectUrl' => '/swiftfin/documents/index'], $urlParams)),
                    ['title' => Yii::t('app', 'View')]
                );
        }
    ],
];

if ($userCanCreateDocuments) {
    $columnsEnabled['create'] = [
        'class'    => 'yii\grid\ActionColumn',
        'template' => '{create}',
        'buttons'  => [
            'create' => function ($url, $model, $key) {
                if ($model->direction == Document::DIRECTION_OUT) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-plus">',
                        Url::to(['/swiftfin/wizard/create-from-existing-document', 'id' => $model->id]),
                        ['title' => Yii::t('app', 'Create')]
                    );
                }
            }
        ],
    ];
}

$myGridWidget = InfiniteGridView::begin([
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary' => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'layout' => '<div class="row">
                <div class="col-xs-6">{summary}</div>
                <div class="col-xs-6 text-right">'. Html::a(Yii::t('app/swiftfin', 'Download XLS'), ArrayHelper::merge([''], ['mode' => 'xls'], Yii::$app->request->queryParams), ['class' => 'btn btn-default btn-xs btn-success']) ."</div>
                </div>
                {items}\n{pager}",
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'id' => 'swiftfin-index',
    'highlightsByStatus' => true,
    'rowOptions' => function ($model, $key, $index, $grid) use ($urlParams) {
        $options['ondblclick'] = "window.location='" . Url::toRoute(array_merge(['view', 'id' => $model->id, 'redirectUrl' => '/swiftfin/documents/index'], $urlParams)) ."'";
        $options['data']['status'] = $model->status;
        $options['data']['document-type'] = $model->type;
        return $options;
    },
    'columns' => $columnsEnabled,
    'options' => ['class' => 'grid-view documents-journal-grid'],
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

echo ToTopButtonWidget::widget();

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $searchModel
    ]
);

$this->registerCss('#delete-selected-documents-button {display: none;}');

$this->registerJS(<<<JS
    stickyTableHelperInit();
JS
);

?>
