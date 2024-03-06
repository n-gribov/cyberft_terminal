<?php

use addons\swiftfin\SwiftfinModule;
use common\document\Document;
use common\document\DocumentPermission;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = Yii::t('app/menu', 'Documents for modification');
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
        'style' => 'width: 110px',
    ],
    'filterInputOptions' => [
        'style' => 'text-align: right;',
    ],
];

$columns['type'] = [
    'attribute' => 'type',
    'filter' =>  $filterModel->getDocTypeLabels(),
    'format' => 'html',
    'value' => function ($item, $params) {
        return "<span title=\"{$item->type}\">{$item->getDocTypeLabel()}</span>";
    },
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
];

$columns['senderParticipantName'] = [
    'attribute' => 'senderParticipantName',
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $filterModel,
        'theme' => Select2::THEME_BOOTSTRAP,
	'attribute' => 'senderParticipantName',
        'options' => [
            'prompt' => '',
	],
	'pluginOptions' => [
		'minimumInputLength' => 0,
		'ajax'               => [
		    'url'      => Url::to(['documents/list', 'type' => 'sender', 'page' => 'correction-index']),
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
        'model' => $filterModel,
        'theme' => Select2::THEME_BOOTSTRAP,
	'attribute' => 'receiverParticipantName',
        'options' => [
            'prompt' => '',
	],
	'pluginOptions' => [
		'minimumInputLength' => 0,
		'ajax'               => [
		    'url'      => Url::to(['documents/list', 'type' => 'receiver', 'page' => 'correction-index']),
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
    'attribute' => 'dateCreate',
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $filterModel,
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
            'style' => 'text-align: right',
        ],
    ]),
];

$columns['correctionReason'] = [
    'attribute' => 'correctionReason',
    'label' => Yii::t('doc', 'Comment')
];

// Получение колонок, которые могут быть отображены
$columnsEnabled = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

// Обязательные колонки, которые должны отображаться в любом случае
$columnsEnabled['actions'] = [
    'class' => ActionColumn::className(),
    'buttons'=>[
        'update'=>function ($url, $model) {
            return Html::a( '<span class="ic-edit"></span>', Url::toRoute(['/swiftfin/wizard/edit', 'id' => $model->id]));
        }
    ],
    'template'=>'{update}',
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
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'highlightsByStatus' => true,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options['ondblclick'] = "window.location='". Url::toRoute(['/swiftfin/wizard/edit', 'id' => $model->id]) ."'";

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
        'model' => $filterModel
    ]
);

$this->registerJS(<<<JS
    stickyTableHelperInit();
JS
);

?>
