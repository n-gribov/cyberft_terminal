<?php

use addons\swiftfin\models\SwiftFinSearch;
use addons\swiftfin\SwiftfinModule;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\Currencies;
use common\helpers\DocumentHelper;
use common\helpers\Html;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = Yii::t('app/menu', 'Documents for user verification');

$userCanCreateDocuments = Yii::$app->user->can(DocumentPermission::CREATE, ['serviceId' => SwiftfinModule::SERVICE_ID]);
?>

<?php

$columns['id'] = [
    'attribute' => 'id',
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'headerOptions' => [
        'class' => 'text-right',
        'style' => 'width: 50px;'
    ],
    'filterInputOptions' => [
        'style' => 'text-align: right;',
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
        'data-none-selected-text' => ""
    ],
];

$columns['senderParticipantName'] = [
    'attribute' => 'senderParticipantName',
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'theme' => Select2::THEME_BOOTSTRAP,
	'attribute' => 'senderParticipantName',
        'options' => [
            'prompt' => '',
	],
	'pluginOptions' => [
		'minimumInputLength' => 0,
		'ajax'               => [
		    'url'      => Url::to(['documents/list', 'type' => 'sender', 'page' => 'user-verification-index']),
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

$columns['receiverParticipantName'] = [
    'attribute' => 'receiverParticipantName',
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'theme' => Select2::THEME_BOOTSTRAP,
	'attribute' => 'receiverParticipantName',
        'options' => [
            'prompt' => '',
	],
	'pluginOptions' => [
		'minimumInputLength' => 0,
		'ajax'               => [
		    'url'      => Url::to(['documents/list', 'type' => 'receiver', 'page' => 'user-verification-index']),
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
    'attribute'     => 'status',
    'format'        => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'attribute' => 'status',
        'data' => SwiftFinSearch::filterStatusLabels(SwiftFinSearch::getUserVerifiableStatus()),
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
    }
];

$columns['dateCreate'] = [
    'attribute' => 'dateCreate',
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
        'options' => [
            'class' => 'form-control',
            'style' => 'text-align: right;'
        ],
    ]),
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'headerOptions' => [
        'class' => 'text-right',
    ],
];

$columns['operationReference'] = [
    'attribute' => 'operationReference',
    'format' => 'html',
];

$columns['currency'] = [
    'attribute' => 'currency',
    'label' => Yii::t('doc', 'Currency'),
    'filter' => Currencies::getCodeLabels(),
    'filterInputOptions' =>  [
        'maxLength' => 3,
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ""
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

$columns['sum'] = [
    'attribute' => 'sum',
    'format'        => 'decimal',
    'label' => Yii::t('doc', 'Total sum'),
    'filterInputOptions' => [
        'maxLength' => 64,
        'style' => 'text-align: right;',
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
    'attribute' => 'valueDate',
    'value' => 'documentExtSwiftFin.valueDate',
    'format' => [
        'date', 'php:Y-m-d'
    ],
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
            'style' => 'text-align: right;',
        ],
    ]),
];

if ($searchModel->highlights) {
    $columns['body'] = [
        'label'     => Yii::t('doc', 'Document body'),
        'format'        => 'html',
        'value'         => function($item, $params) use($searchModel) {
            if (isset($searchModel->highlights[$item->id]['body'])) {
                return $searchModel->highlights[$item->id]['body'][0];
            } else {
                return '';
            }
        }
    ];
}

// Получение колонок, которые могут быть отображены
$columnsEnabled = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

$columnsEnabled['actions'] = [
    'class'    => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'buttons'  => [
        'view' => function ($url, $model, $key){
            return Html::a('<span class="ic-eye"></span>',
                Url::toRoute(['view', 'id' => $model->id, 'redirectUrl' => '/swiftfin/documents/user-verification-index']),
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
                        Url::to(['/swiftfin/wizard/create-from-existing-document', 'id' => $model->id])
                    );
                } else {
                    return '';
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
    'highlightsByStatus' => true,
    'filterModel'  => $searchModel,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options['ondblclick'] = "window.location='". Url::toRoute(['user-verify', 'id'=>$model->id]) ."'";

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

$this->registerJS(<<<JS
    stickyTableHelperInit();
JS
);

?>
