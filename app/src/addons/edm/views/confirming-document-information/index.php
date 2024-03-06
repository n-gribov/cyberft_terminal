<?php

use addons\edm\EdmModule;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use common\helpers\Html;
use common\helpers\vtb\VTBHelper;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

$userCanDeleteDocuments = Yii::$app->user->can(
    DocumentPermission::DELETE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => EdmDocumentTypeGroup::CONFIRMING_DOCUMENTS_INQUIRY,
    ]
);
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
}

$userCanCreateDocuments = Yii::$app->user->can(
    DocumentPermission::CREATE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => EdmDocumentTypeGroup::CONFIRMING_DOCUMENTS_INQUIRY,
    ]
);

$createButtonOptions = [
    'label' => Yii::t('app', 'Create'),
    'url' => '/edm/confirming-document-information/create?clearWizardCache=1'
];

$deleteButtonOptions = [
    'url' => '/edm/confirming-document-information/delete-cdi'
];

echo $this->render('@addons/edm/views/documents/_fccIndexHeader', [
    'userCanCreateDocuments' => $userCanCreateDocuments,
    'userCanDeleteDocuments' => $userCanDeleteDocuments,
    'hasDeletableDocuments' => count($deletableDocumentsIds) > 0,
    'cachedEntries' => $cachedEntries,
    'tabMode' => $tabMode,
    'createButtonsOptions' => [$createButtonOptions],
    'deleteButtonOptions' => $deleteButtonOptions,
    'searchModel' => $searchModel
]);

// Колонка с чекбоксом удаления
if ($userCanDeleteDocuments) {
    $columnsSettings['deleted'] = [
        'class' => 'yii\grid\CheckboxColumn',
        'visible' => $userCanDeleteDocuments && count($deletableDocumentsIds) > 0,
        'checkboxOptions' => function($model, $key, $index, $column) use ($cachedEntries, $deletableDocumentsIds) {
            $checked = false;
            $hidden = false;
            if (!in_array($model->id, $deletableDocumentsIds)) {
                $hidden = true;
            } else if (!empty($cachedEntries['entries'])
                && array_key_exists($key, $cachedEntries['entries'])
            ) {
                $checked = true;
            }

            return [
                'class' => 'checkbox-selection',
                'style' => "display: " . ($hidden ? 'none': 'block'),

                'disabled' => $hidden,
                'value' => $key,
                'checked' => $checked,
                'data' => ['id' => $model->id],
            ];
        }
    ];
}

$columns['number'] = [
    'attribute' => 'number',
    'contentOptions' => [
        'class' => 'text-right',
        'style' => 'width: 180px'
    ]
];

$columns['organizationId'] = [
    'attribute' => 'organizationId',
    'value' => function($item) {
        return DictOrganization::getNameById($item->organizationId);
    },
    'filter' => Select2::widget([
        'model'         => $searchModel,
        'attribute'     => 'organizationId',
        'data'          => $orgFilter,
        'theme'         => Select2::THEME_BOOTSTRAP,
        'options'       => [
            'prompt' => '',
        ],
        'pluginOptions' => [
            'allowClear'        => true,
            'containerCssClass' => 'select2-cyberft',
        ],
    ]),
    'contentOptions' => [
        'style'  => 'min-width: 200px;',
    ],
];

$columns['bankBik'] = [
    'attribute' => 'bankBik',
    'label' => Yii::t('edm', 'Bank'),
    'value' => function($item) {
        return $item->bankName;
    },
    'filter' => Select2::widget([
        'model'         => $searchModel,
        'attribute'     => 'bankBik',
        'data'          => $bankFilter,
        'theme'         => Select2::THEME_BOOTSTRAP,
        'options'       => [
            'prompt' => '',
        ],
        'pluginOptions' => [
            'allowClear'        => true,
            'containerCssClass' => 'select2-cyberft',
        ],
    ]),
    'contentOptions' => [
        'style'  => 'min-width: 200px;',
    ],
];

$columns['date'] = [
    'attribute' => 'date',
    'headerOptions' => [
        'class' => 'text-right'
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ],
    'filter' => kartik\widgets\DatePicker::widget(
        [
            'model' => $searchModel,
            'attribute' => 'date',
            'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy',
                'todayHighlight' => true,
                'orientation' => 'bottom'
            ],
            'options' => [
                'class' => 'form-control',
                'style' => 'width: 130px'
            ]
        ]
    ),
];

$columns['contractPassport'] = 'contractPassport';

//$columns['person'] = 'person';
//$columns['contactNumber'] = 'contactNumber';
//
//$columns['direction'] = [
//    'attribute' => 'direction',
//    'format' => 'html',
//    'value' => function ($item, $params) {
//        return Html::tag('span', Document::directionLabel($direction), ['title' => $item->direction]);
//    }
//];

$columns['status'] = [
    'attribute' => 'status',
    'format' => 'html',
    'contentOptions' => [
        'style' => 'width: 100px',
    ],
    'value' => function($item) {
        $status = DocumentHelper::getStatusLabel($item);

        return Html::tag('span', $status['label'], ['title' => 'Status: ' . $status['name']]);
    },
];

$columns['dateCreate'] = [
    'attribute' => 'dateCreate',
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
            ],
        ]),
];

$columns['businessStatus'] = [
    'attribute' => 'businessStatus',
    'filter' => DocumentHelper::getBusinessStatusesList(),
    'value' => function($model) {
        return PaymentRegisterDocumentExt::translateBusinessStatus($model) ?: '';
    },
];

$columnsSettings['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'style' => 'width: 20px',
    ],
];

$columns['signaturesRequired'] = [
    'attribute' => 'signaturesRequired',
    'headerOptions' => [
        'class' => 'text-right'
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ],
];

$columns['signaturesCount'] = [
    'attribute' => 'signaturesCount',
    'headerOptions' => [
        'class' => 'text-right'
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ],
];

// Получение колонок, которые могут быть отображены
$columnsSettings = array_merge(
    $columnsSettings,
    UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id)
);

$columnsSettings['actions'] = [
    'class' => 'yii\grid\ActionColumn',
    'template' => '{view} {create}',
    'headerOptions' => [
        'style' => 'width: 116px;'
    ],
    'contentOptions' => [
        'style' => 'min-width: 116px;',
        'class' => 'text-right',
    ],
    'urlCreator' => function ($action, $model, $key, $index) {
        if ($action === 'view') {
            $url = VTBHelper::isVTBDocument($model)
                ? '/edm/vtb-documents/view'
                : '/edm/confirming-document-information/view';
            return Url::to([$url, 'id' => $model->id]);
        } else if ($action === 'update') {
            return Url::to(['/edm/confirming-document-information/update', 'id' => $model->id]);
        }
    },
    'buttons' => [
        'create' => function($model, $key, $index) {
            return Html::a(
                '<span class="glyphicon glyphicon-plus">',
                Url::to(['/edm/documents/create-fcc-from-existing-document', 'type' => 'CDI', 'id' => $key->id]),
                ['title' => Yii::t('app', 'Create')]
            );
        }
    ],
    'visibleButtons' => [
        'view' => function ($model, $key, $index) {
            return true;
        },

        'update' => function ($model, $key, $index) use ($userCanDeleteDocuments) {
            return !VTBHelper::isVTBDocument($model) && $model->isModifiable() && $userCanDeleteDocuments;
        },

        'create' => function ($model, $key, $index) use ($userCanCreateDocuments) {
            return !VTBHelper::isVTBDocument($model) && $userCanCreateDocuments && $model->direction == Document::DIRECTION_OUT;
        },
    ]
];

echo InfiniteGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'rowOptions' => function ($model, $key, $index, $grid) {
        if (in_array($model->status, [
            Document::STATUS_REJECTED,
            Document::STATUS_SENDING_FAIL,
            Document::STATUS_PROCESSING_ERROR,
            Document::STATUS_SIGNING_ERROR,
            Document::STATUS_SIGNING_REJECTED,
            Document::STATUS_CONTROLLER_VERIFICATION_FAIL,
            Document::STATUS_SIGNING_ERROR,
            Document::STATUS_VERIFICATION_FAILED,
            Document::STATUS_REGISTERING_ERROR,
        ])) {
            $options['class'] = 'danger';
        } else if (in_array($model->status, [
            Document::STATUS_UNDELIVERED,
        ])) {
            $options['class'] = 'warning';
        }
        $viewUrl = VTBHelper::isVTBDocument($model)
            ? '/edm/vtb-documents/view'
            : '/edm/confirming-document-information/view';
        $options['ondblclick'] = "window.location='". Url::toRoute([$viewUrl, 'id' => $model->id]) ."'";
        $options['data']['status'] = $model->status;
        $options['data']['document-type'] = $model->type;

        return $options;
    },
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'columns' => $columnsSettings,
    'options' => ['class' => 'grid-view documents-journal-grid'],
]);

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $searchModel
    ]
);

$this->registerJS(<<<JS
    $('#confirmingdocumentinformationsearch-date').inputmask('99.99.9999', { placeholder: 'дд.мм.гггг' });
    stickyTableHelperInit();
JS
);

echo ToTopButtonWidget::widget();