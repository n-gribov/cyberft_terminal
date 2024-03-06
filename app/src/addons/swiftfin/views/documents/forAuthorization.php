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
use common\widgets\GridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use kop\y2sp\ScrollPager;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = Yii::t('app/menu', 'Documents for authorization');


$userCanCreateDocuments = Yii::$app->user->can(DocumentPermission::CREATE, ['serviceId' => SwiftfinModule::SERVICE_ID]);
?>

<?php

/**
 * Указание страницы для правильного формирования
 * ссылки для кнопки Назад
 */

$urlParams['from'] = 'forAuthorization';

$columns['id'] = [
    'attribute' => 'id',
    'label'  => Yii::t('doc', 'ID'),
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
];

$columns['type'] = [
    'attribute' => 'type',
    'label'  => Yii::t('doc', 'Type'),
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
    'label' => Yii::t('doc', 'Sender'),
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
		'ajax' => [
		    'url' => Url::to(['documents/list', 'type' => 'sender', 'page' => 'user-verification-index']),
		    'dataType' => 'json',
		    'delay' => 250,
		    'data' => new JsExpression('function(params) { return {q:params.term}; }'),
		],
		'templateResult' => new JsExpression('function(item) { return item.name; }'),
		'templateSelection' => new JsExpression('function(item) { return item.name; }'),
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

$columns['receiverParticipantName'] = [
    'attribute' => 'receiverParticipantName',
    'label' => Yii::t('doc', 'Receiver'),
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
		'templateResult' => new JsExpression('function(item) { return item.name; }'),
		'templateSelection'  => new JsExpression('function(item) { return item.name; }'),
		'allowClear' => true,
		'containerCssClass' => 'select2-cyberft',
	],
	'pluginEvents'  => [
	    'select2:select' => 'function(e) { searchForField(e.params.data); }',
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
    'label'         => Yii::t('doc', 'Status'),
    'format'        => 'html',
    'filter'		=> SwiftFinSearch::getAuthorizableStatusLabels(),
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
    'value' => function ($item, $params) {
        $status = DocumentHelper::getStatusLabel($item);
        return "<span title=\"Status: {$status['name']}\">{$status['label']}</span>";
    }
];

$columns['dateCreate'] = [
    'attribute' => 'dateCreate',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'label' => Yii::t('doc', 'Date'),
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
            'style' => 'width: 130px',
        ],
    ]),
];

$columns['operationReference'] = [
    'attribute' => 'operationReference',
    'label'     => Yii::t('doc', 'Operation reference'),
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'attribute' => 'operationReference',
        'data' => ArrayHelper::map($dataProvider->getModels(), 'operationReference', 'operationReference'),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'prompt' => '',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft',
            'width' => '180px'
        ],
    ]),
];

$columns['currency'] = [
    'attribute' => 'currency',
    'value' => 'documentExtSwiftFin.currency',
    'label' => Yii::t('doc', 'Currency'),
    'filter' => Currencies::getCodeLabels(),
    'filterInputOptions' =>  [
        'maxLength' => 3,
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
];

$columns['sum'] = [
    'attribute' => 'sum',
    'value' => 'documentExtSwiftFin.sum',
    'format'        => 'decimal',
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
];

$columns['valueDate'] = [
    'attribute' => 'valueDate',
    'format' => [
        'date', 'php: Y-m-d'
    ],
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'label' => Yii::t('doc', 'Value date'),
    'value' => 'documentExtSwiftFin.valueDate',
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

// Получение колонок, которые могут быть отображены
$columnsEnabled = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

// Обязательные колонки, которые должны отображаться в любом случае
$columnsEnabled['actions'] = [
    'class'    => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'buttons'  => [
        'view' => function ($url, $model, $key) use ($urlParams) {
            return Html::a('<span class="ic-eye"></span>',
                Url::toRoute(array_merge(['view', 'id' => $model->id], $urlParams)),
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
// Создать таблицу для вывода
$myGridWidget = GridView::begin([
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary' => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'layout' => '<div class="row">
        <div class="col-xs-6">{summary}</div>
        <div class="col-xs-6 text-right">'. Html::a(
            Yii::t('app/swiftfin',
            'Download XLS'), ArrayHelper::merge([''],
            ['mode' => 'xls'], Yii::$app->request->queryParams),
            ['class' => 'btn btn-default btn-xs btn-success']) ."</div>
        </div>
        {items}\n{pager}",
    'dataProvider' => $dataProvider,
    'highlightsByStatus' => true,
    'filterModel'  => $searchModel,
    'rowOptions' => function ($model, $key, $index, $grid) use ($urlParams) {
        $options['ondblclick'] = "window.location='". Url::toRoute(array_merge(['view', 'id' => $model->id], $urlParams)) ."'";
        return $options;
    },
    'pager' => [
        'class' => ScrollPager::className(),
        'container' => '.grid-view tbody',
        'item' => 'tr',
        'noneLeftText' => '',
        'triggerOffset' => 99999,
        'triggerTemplate' => '<tr class="ias-trigger"><td colspan="100%" style="text-align: center"><a style="cursor: pointer">{text}</a></td></tr>',
    ],
    'columns' => $columnsEnabled,
    'options' => ['class' => 'grid-view documents-journal-grid'],
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

echo ToTopButtonWidget::widget();

echo ColumnsSettingsWidget::widget([
    'listType' => $listType,
    'columns' => array_keys($columns),
    'model' => $searchModel
]);

$this->registerJS(<<<JS
    stickyTableHelperInit();
JS
);
