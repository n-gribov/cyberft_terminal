<?php

use addons\edm\models\DictCurrency;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\ISO20022Module;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DateHelper;
use common\helpers\Html;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\documents\DeleteSelectedDocumentsButton;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\MaskedInput;

$this->title = Yii::t('app/menu', 'Payment Documents');
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

echo $this->render('_search', [
    'model' => $searchModel,
    'filterStatus' => $filterStatus,
]);

// Вывод колонок отправителя/получателя
// Для администратора - технические название терминала
// Для пользователей - наименование участника обмена

if (Yii::$app->user->can('admin') || Yii::$app->user->can('additionalAdmin')) {
    $senderColumnAttr = 'sender';
    $receiverColumnAttr = 'receiver';
} else {
    $senderColumnAttr = 'senderParticipantName';
    $receiverColumnAttr = 'receiverParticipantName';
}

$columns['id'] = [
    'attribute' => 'id',
    'width' => 'narrow',
    'headerOptions' => [
        'class' => 'text-right',
        'style' => 'width:5%;',
    ],
    'filterInputOptions' => [
        'style' => 'width: 100%;',
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'nowrap' => 'nowrap'
    ],
];

$columns['type'] = [
    'attribute' => 'type',
    'filter' =>  ISO20022Helper::getPaymentsDocTypeLabels(),
    'format' => 'html',
    'enableSorting' => false,
    'value' => function ($item, $params) {
        return "<span title=\"{$item->type}\">{$item->getDocTypeLabel()}</span>";
    },
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'style' => 'width: 100%;',
        'data-none-selected-text' => ''
    ],
    'headerOptions' => [
        'style' => 'width:5%;',
    ],
    'contentOptions' => [
        'nowrap' => 'nowrap'
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
        'style' => 'width: 100%;',
        'data-none-selected-text' => ''
    ],
    'headerOptions' => [
        'style' => 'width:5%;',
    ],
    'contentOptions' => [
        'nowrap' => 'nowrap'
    ],
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
		'ajax'               => [
		    'url'      => Url::to(['documents/list', 'type' => 'sender', 'page' => 'payments']),
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
		    'url'      => Url::to(['documents/list', 'type' => 'receiver', 'page' => 'payments']),
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

$columns['dateCreate'] = [
    'attribute'          => 'dateCreate',
    'label'  => Yii::t('app/iso20022', 'Registered'),
    'value' => function($model) {
        return DateHelper::formatDate($model->dateCreate, 'datetime');
    },
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
        ]
    ),
    'filterInputOptions' => [
        'style' => 'width: 100%;',
    ],
    'headerOptions' => [
        'style' => 'width:5%;',
    ],
    'contentOptions' => [
        'nowrap' => 'nowrap'
    ],
];

$columns['count'] = [
    'attribute' => 'count',
    'value' => 'documentExtISO20022.count',
    'filterInputOptions' => [
        'style' => 'width: 100%;'
    ],
    'headerOptions' => [
        'style' => 'width:5%;',
    ],
    'contentOptions' => [
        'nowrap' => 'nowrap',
        'class' => 'text-right'
    ],
];

$columns['sum'] = [
    'attribute' => 'sum',
    'value' => 'documentExtISO20022.sum',
    'format' => ['decimal', 2],
    'filter' => MaskedInput::widget([
        'attribute' => 'sum',
        'model' => $searchModel,
        'clientOptions' => [
            'alias' => 'decimal',
            'digits' => 2,
            'digitsOptional' => false,
            'radixPoint' => '.',
            'autoGroup' => true,
            'removeMaskOnSubmit' => true,
            'placeholder' => '0.00',
            'groupSeparator' => ' '
        ]
    ]),
    'filterInputOptions' => [
        'style' => 'width: 100%;',
    ],
    'headerOptions' => [
        'style' => 'width:5%;',
    ],
    'contentOptions' => [
        'nowrap' => 'nowrap',
        'class' => 'text-right'
    ],
];

$columns['currency'] = [
    'attribute' => 'currency',
    'filter' => DictCurrency::getList(),
    'value' => 'documentExtISO20022.currency',
    'filterInputOptions' => [
        'style' => 'width: 100%;',
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
    'headerOptions' => [
        'style' => 'width:5%;',
    ],
    'contentOptions' => [
        'nowrap' => 'nowrap'
    ],
];

$columns['originalFilename'] = [
    'attribute' => 'originalFilename',
    'value' => 'documentExtISO20022.originalFilename',
    'filterInputOptions' => [
        'style' => 'width: 100%;',
    ],
    'headerOptions' => [
        'style' => 'width:15%;',
    ],
    'contentOptions' => [
        'nowrap' => 'nowrap'
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
            'containerCssClass' => 'select2-cyberft'
        ],
    ]),
    'headerOptions' => [
        'style' => 'width:15%;',
    ],
    'contentOptions' => [
        'nowrap' => 'nowrap',
    ],
    'value'         => function ($item, $params) {
        return "<span title=\"Status: {$item->status}\">{$item->getStatusLabel()}</span>";
    }
];

$columns['statusCode'] = [
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
        'class'           => 'yii\grid\CheckboxColumn',
        'headerOptions' => [
            'style' => 'width:1%;',
        ],
        'checkboxOptions' => function ($model, $key, $index, $column) use ($deletableDocumentsIds) {
            $hidden = !in_array($model->id, $deletableDocumentsIds);
            return [
                'style'    => 'display: ' . ($hidden ? 'none' : 'block'),
                'disabled' => $hidden,
                'class'    => 'delete-checkbox',
                'value'    => $key,
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
            return Html::a('<span class="ic-eye"title="' . Yii::t("app", "View") . '"></span>',
                Url::toRoute(array_merge(['view', 'id' => $model->id, 'isoSubtype' => 'free-format'], $urlParams))
            );
        }
    ],
];

$myGridWidget = InfiniteGridView::begin([
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => '.'
    ],
    'highlightsByStatus' => true,
    'rowOptions' => function ($model, $key, $index, $grid) use ($urlParams) {
        $options['ondblclick'] = "window.location='"
                . Url::toRoute(array_merge(['view', 'id' => $model->id, 'isoSubtype' => 'payments'], $urlParams))
                . "'";
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

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $searchModel
    ]
);

?>

<?php

$script = <<< JS
    // Возвращает символ нажатой клавиши
    function getChar(event) {
        return String.fromCharCode(event.keyCode || event.charCode);
    }

    // Паттерны для ввода значений фильтров

    // Для количества
    $('#iso20022search-count').on('keypress', function(e){
        // спец. сочетания - не обрабатываем
        if (e.ctrlKey || e.altKey || e.metaKey) {
            return true;
        }

        var reg = new RegExp('^[0-9]', 'g');
        var char = getChar(e);

        if (!char.match(reg)){
            e.preventDefault();
        }
    });

    // Для суммы
    $('#iso20022search-sum').on('keypress', function(e){
        // спец. сочетания - не обрабатываем
        if (e.ctrlKey || e.altKey || e.metaKey) {
            return true;
        }

        var reg = new RegExp('^[0-9.]', 'g');
        var char = getChar(e);

        if (!char.match(reg)){
            e.preventDefault();
        }
    });

    stickyTableHelperInit();
JS;

echo ToTopButtonWidget::widget();

$this->registerJs($script, View::POS_READY);

$this->registerCss('#delete-selected-documents-button {display: none;}');

?>

