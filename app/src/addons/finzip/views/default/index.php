<?php

use addons\finzip\FinZipModule;
use addons\finzip\models\FinZipDocumentExt;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\Html;
use common\models\User;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\documents\DeleteSelectedDocumentsButton;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = Yii::t('other', 'Document Register');

$userCanDeleteDocuments = \Yii::$app->user->can(DocumentPermission::DELETE, ['serviceId' => FinZipModule::SERVICE_ID]);
$userCanCreateDocuments = Yii::$app->user->can(DocumentPermission::CREATE, ['serviceId' => FinZipModule::SERVICE_ID]);
$deletableDocumentsIds = [];

$isAdmin = in_array(Yii::$app->user->identity->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN]);

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

$columns['id'] = [
    'attribute' => 'id',
    'width' => 'narrow',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
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
        'data-width' => "110px",
        'data-none-selected-text' => ""
    ],
    'width' => 'middle',
];

if ($isAdmin) {
    $sender = 'sender';
    $receiver = 'receiver';
} else {
    $sender = 'senderParticipantName';
    $receiver = 'receiverParticipantName';
}

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
		    'url'      => Url::to(['documents/list', 'type' => 'sender', 'page' => 'index']),
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
		    'url'      => Url::to(['documents/list', 'type' => 'receiver', 'page' => 'index']),
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
    'attribute' => 'dateCreate',
    'filter' => kartik\widgets\DatePicker::widget(
        [
            'model' => $searchModel,
            'attribute' => 'dateCreate',
            'type' => kartik\widgets\DatePicker::TYPE_INPUT,
            'pluginOptions' => [
               'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                'orientation' => 'bottom'
            ],
            'options' => [
                'class' => 'form-control',
                'style' => 'width: 130px; text-align: right;',
            ],
        ]
    ),
];

$columns['subject'] = [
    'attribute' => 'subject',
    'value' => function($model) use ($isAdmin) {
        if (!$isAdmin && $model->documentExtFinZip) {
            if ($model->isEncrypted) {
                Yii::$app->terminals->setCurrentTerminalId($model->originTerminalId);
                return $model->documentExtFinZip->getEncryptedSubject();
            } else {
                return $model->documentExtFinZip->subject;
            }
        }
    },
    'filter' => false,
    'width' => 'middle',
];


$columns['fileCount'] = [
    'attribute' => 'fileCount',
    'value' => 'documentExtFinZip.attachmentsCount',
    'filter' => false,
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'textAlign' => 'right',
];

$columns['businessStatus'] = [
    'attribute' => 'businessStatus',
    'filter'    => FinZipDocumentExt::businessStatusLabels(),
    'headerOptions' => [
        'style' => 'width: 220px'
    ],
    'contentOptions' => [
        'style' => 'width: 220px'
    ],
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
    'value' => function($item) {
        $extModel = $item->extModel;
        return $extModel
                ? FinZipDocumentExt::getBusinessStatusTranslation($extModel->businessStatus)
                : null;
    }
];

$columnsEnabled = [];

if ($userCanDeleteDocuments && count($deletableDocumentsIds) > 0) {
    // Колонка с чекбоксом удаления
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

if ($userCanCreateDocuments) {
    $columnsEnabled['actions3'] = [
        'class'    => 'yii\grid\ActionColumn',
        'template' => '{create}',
        'buttons'  => [
            'create' => function ($url, $model, $key) {
                if ($model->direction != Document::DIRECTION_OUT) {
                    return "";
                }

                return Html::a('<span class="glyphicon glyphicon-plus"></span>',
                    Url::toRoute(['/finzip/wizard/step2', 'fromId' => $key]),
                    ['title' => Yii::t('app', 'Create')]
                );
            }
        ],
    ];
}

$isAdmin = Yii::$app->user->can('admin');

$myGridWidget = InfiniteGridView::begin([
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'highlightsByStatus' => true,
    'filterModel'  => $searchModel,
    'rowOptions' => function ($model, $index, $widget, $grid) use ($isAdmin) {
       $options = [];

       if ($model->documentExtFinZip && $model->documentExtFinZip->businessStatus == 'RJCT') {
           $options['class'] = 'danger';
       }

        // Выделение непросмотренных
        // документов за сегодняшний день
        if (!$isAdmin) {
            $date = new DateTime($model->dateCreate);
            $dateFormat = $date->format('Y-m-d');

            if ($model->viewed == 0) {
                $options['style'] = 'font-weight: bold;';
            }
        }

        $options['ondblclick'] = "window.location='" . Url::toRoute(['default/view', 'id' => $model->id]) . "'";
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

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $searchModel
    ]
);

echo ToTopButtonWidget::widget();

// Модальное окно формы поиска
echo $this->render('@addons/edm/views/documents/_searchModal', ['model' => $searchModel]);

$this->registerCss('#delete-selected-documents-button {display: none;}');

$this->registerJS(<<<JS
    stickyTableHelperInit();
JS
);

?>