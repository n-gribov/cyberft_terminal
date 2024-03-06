<?php

use addons\edm\controllers\helpers\FCCHelper;
use addons\edm\EdmModule;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestSearch;
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
use yii\helpers\Url;
use yii\web\JsExpression;

function getViewUrl(Document $document)
{
    if ($document->type === \addons\edm\models\VTBCredReg\VTBCredRegType::TYPE) {
        return '/edm/loan-agreement-registration-request/view';
    } elseif ($document->type === \addons\edm\models\VTBContractUnReg\VTBContractUnRegType::TYPE) {
        return '/edm/contract-unregistration-request/view';
    } elseif (VTBHelper::isVTBDocument($document)) {
        return '/edm/vtb-documents/view';
    } else {
        return '/edm/contract-registration-request/view';
    }
}

$userCanDeleteDocuments = Yii::$app->user->can(
    DocumentPermission::DELETE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => [
            EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
            EdmDocumentTypeGroup::CONTRACT_REGISTRATION_REQUEST,
            EdmDocumentTypeGroup::CONTRACT_CHANGE_REQUEST,
            EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
        ],
    ]
);
$deletableDocumentsIds = [];
if ($userCanDeleteDocuments) {
    $deletableDocumentsIds = array_reduce(
        $dataProvider->models,
        function ($carry, Document $document) {
            $isDeletable = FCCHelper::isDocumentCanModify($document);
            $userCanDelete = Yii::$app->user->can(
                DocumentPermission::DELETE,
                [
                    'serviceId' => EdmModule::SERVICE_ID,
                    'document' => $document,
                ]
            );
            if ($isDeletable && $userCanDelete) {
                $carry[] = $document->id;
            }

            return $carry;
        },
        []
    );
}

$userCanCreate = function ($documentTypeGroup) {
    return Yii::$app->user->can(
        DocumentPermission::CREATE,
        [
            'serviceId' => EdmModule::SERVICE_ID,
            'documentTypeGroup' => $documentTypeGroup
        ]
    );
};

$createButtonOptions = [];
if ($userCanCreate(EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST)) {
    $createButtonOptions[] = [
        'label' => Yii::t('edm', 'Loan agreement registration request'),
        'url' => '/edm/loan-agreement-registration-request/create'
    ];
}
if ($userCanCreate(EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST)) {
    $createButtonOptions[] = [
        'label' => Yii::t('edm', 'Contract unregistration request'),
        'url' => '/edm/contract-unregistration-request/create'
    ];
}
$userCanCreateDocuments = count($createButtonOptions) > 0;

$deleteButtonOptions = [
    'url' => '/edm/contract-registration-request/delete-crr'
];

echo $this->render('@addons/edm/views/documents/_fccIndexHeader', [
    'userCanCreateDocuments' => $userCanCreateDocuments && Yii::$app->user->can('vtbDocuments'),
    'userCanDeleteDocuments' => $userCanDeleteDocuments,
    'hasDeletableDocuments' => count($deletableDocumentsIds) > 0,
    'cachedEntries' => $cachedEntries,
    'tabMode' => $tabMode,
    'createButtonsOptions' => $createButtonOptions,
    'deleteButtonOptions' => $deleteButtonOptions,
    'searchModel' => $searchModel
]);

$columnsSettings = [];

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
        'style' => 'width: 75px;'
    ],
];

$columns['date'] = [
    'attribute' => 'date',
    'label' => Yii::t('edm', 'Document date'),
    'contentOptions' => [
        'style' => 'width: 150px;'
    ],
    'filter' => kartik\widgets\DatePicker::widget(
        [
            'model' => $searchModel,
            'attribute' => 'date',
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
        ]
    ),
];

$columns['organizationId'] = [
    'attribute' => 'organizationId',
    'filter' => $orgFilter,
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '150px',
        'data-none-selected-text' => ''
    ],
    'value' => function($item) {
        return DictOrganization::getNameById($item->organizationId);
    }
];

$columns['bankBik'] = [
    'attribute' => 'bankBik',
    'label' => Yii::t('edm', 'Bank'),
    'filter' => $bankFilter,
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '200px',
        'data-none-selected-text' => ''
    ],
    'value' => function($item) {
        return $item->bankName;
    }
];

$columns['type'] = [
    'attribute' => 'type',
    'filter' => ContractRegistrationRequestSearch::getTypeFilter(),
    'value' => function(ContractRegistrationRequestSearch $item) {
        return $item->getTypeLabel();
    },
    'contentOptions' => [
        'style' => 'width: 180px'
    ],
];

$columns['status'] = [
    'attribute' => 'status',
    'filter' => DocumentHelper::getStatusLabelsAll(),
    'format' => 'html',
    'contentOptions' => [
        'style' => 'width: 100px',
    ],
    'value' => function($item) {
        $status = DocumentHelper::getStatusLabel($item);
        return "<span title=\"Status: {$status['name']}\">{$status['label']}</span>";
    }
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
        ]
    ),
];

$columns['businessStatus'] = [
    'attribute' => 'businessStatus',
    'filter' => DocumentHelper::getBusinessStatusesList(),
    'value' => function($model) {
        return PaymentRegisterDocumentExt::translateBusinessStatus($model) ?: '';
    },
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

$columnsSettings['actions'] =     [
    'class' => 'yii\grid\ActionColumn',
    'template' => '{view} {create}',
    'headerOptions' => [
        'style' => 'width: 50px;'
    ],
    'contentOptions' => [
        'style' => 'min-width: 50;',
        'class' => 'text-right'
    ],
    'urlCreator' => function ($action, $model, $key, $index) {
        if ($action === 'view') {
            return Url::to([getViewUrl($model), 'id' => $model->id]);
        } else if ($action === 'update') {
            return Url::to(['/edm/contract-registration-request/update', 'id' => $model->id]);
        }
    },
    'buttons' => [
        'create' => function($model, $key, $index) {
            return Html::a(
                '<span class="glyphicon glyphicon-plus">',
                Url::to(['/edm/documents/create-fcc-from-existing-document', 'type' => 'CRR', 'id' => $key->id]),
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
            return !VTBHelper::isVTBDocument($model) && $userCanCreateDocuments  && $model->direction == Document::DIRECTION_OUT;
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
        $options['ondblclick'] = "window.location='". Url::to([getViewUrl($model), 'id' => $model->id]) ."'";
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
    $('#contractregistrationrequestsearch-date').inputmask('99.99.9999', { placeholder: 'дд.мм.гггг' });
    $('#contractregistrationrequestsearch-datecreate').inputmask('99.99.9999', { placeholder: 'дд.мм.гггг' });
    stickyTableHelperInit();
JS
);

echo ToTopButtonWidget::widget();
?>

<style>
    table.values-table {
        background-color: transparent;
        width: 100%;
    }
    table.values-table td {
        padding: 8px 4px;
        white-space: nowrap;
    }
    table.values-table td {
        padding: 8px 4px;
        border-bottom: 1px solid #ddd;
    }
    table.values-table tr:last-child td {
        border-bottom: none;
    }
    .no-padding {
        padding: 0 !important;
    }
</style>
