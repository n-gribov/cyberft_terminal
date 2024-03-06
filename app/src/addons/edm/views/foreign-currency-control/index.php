<?php

use addons\edm\controllers\helpers\FCCHelper;
use addons\edm\EdmModule;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use common\helpers\vtb\VTBHelper;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = Yii::t('app/menu', 'Foreign currency control');

$userCan = function (string $permissionCode) {
    return Yii::$app->user->can(
        $permissionCode,
        [
            'serviceId' => EdmModule::SERVICE_ID,
            'documentTypeGroup' => EdmDocumentTypeGroup::CURRENCY_DEAL_INQUIRY,
        ]
    );
};

$userCanCreateDocuments = $userCan(DocumentPermission::CREATE);
$userCanDeleteDocuments = $userCan(DocumentPermission::DELETE);
$deletableDocumentsIds = [];
if ($userCanDeleteDocuments) {
    $deletableDocumentsIds = array_reduce(
        $dataProvider->models,
        function ($carry, Document $document) {
            if ($document->isModifiable()) {
                $carry[] = $document->id;
            }

            return $carry;
        },
        []
    );
}

$createButtonOptions = [
    'label' => Yii::t('app', 'Create'), //  'edm', 'Add foreign currency information'
    'url' => '/edm/foreign-currency-control/create?clearWizardCache=1'
];

$deleteButtonOptions = [
    'url' => '/edm/foreign-currency-control/delete-foreign-currency-informations'
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

$columns = [
    'number' => [
        'attribute' => 'number',
        'contentOptions' => [
            'class' => 'text-right'
        ]
    ],

    'organizationId' => [
        'attribute' => 'organizationId',
        'filter' => $orgFilter,
        'filterInputOptions' => [
            'class' => 'form-control selectpicker',
            'data-width' => '200px',
            'data-none-selected-text' => ''
        ],
        'value' => function($item) {
            $account = EdmPayerAccount::findOne($item->accountId);
            if ($account) {
                return $account->getPayerName();
            } else {
                return DictOrganization::getNameById($item->organizationId);
            }
        }
    ],

    'accountId' => [
        'attribute' => 'accountId',
        'contentOptions' => [
            'class' => 'text-right'
        ],
        'filter' => $accountFilter,
        'filterInputOptions' => [
            'class' => 'form-control selectpicker',
            'data-width' => '200px',
            'data-none-selected-text' => ''
        ],
        'value' => function($item) {
            return $item->accountNumber;
        }
    ],

//    'bankBik' => [
//        'attribute' => 'bankBik',
//        'label' => Yii::t('edm', 'Bank BIK'),
//        'filter' => $bankFilter,
//        'filterInputOptions' => [
//            'class' => 'form-control selectpicker',
//            'data-width' => '200px',
//            'data-none-selected-text' => ''
//        ],
//    ],

    'bankName' => [
        'attribute' => 'bankName',
        'filter' => $bankNameFilter,
        'filterInputOptions' => [
            'class' => 'form-control selectpicker',
            'data-width' => '200px',
            'data-none-selected-text' => ''
        ],
    ],

    'date' => [
        'attribute' => 'date',
        'headerOptions' => [
            'class' => 'text-right'
        ],
        'contentOptions' => [
            'class' => 'text-right'
        ],
        'format' => ['date', 'dd.MM.Y'],
        'filter' => kartik\widgets\DatePicker::widget([
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
        ]),
    ],

    'status' => [
        'attribute' => 'status',
        'format' => 'html',
        'filter' => Document::getStatusLabels(),
        'value' => function($item) {
            $status = DocumentHelper::getStatusLabel($item);
            return "<span title=\"Status: {$status['name']}\">{$status['label']}</span>";
        },
    ],

    'dateCreate' => [
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
            ],
        ]),
    ],

    'businessStatus' => [
        'attribute' => 'businessStatus',
        'filter' => DocumentHelper::getBusinessStatusesList(),
        'value' => function($model) {
            return PaymentRegisterDocumentExt::translateBusinessStatus($model) ?: '';
        },
    ]
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
            } else if (!empty($cachedEntries['entries']) && array_key_exists($key, $cachedEntries['entries'])) {
                $checked = true;
            }

            return [
                'class' => 'checkbox-selection',
                'style' => 'display: ' . ($hidden ? 'none': 'block'),
                'disabled' => $hidden,
                'value' => $key,
                'checked' => $checked,
                'data' => ['id' => $model->id],
            ];
        }
    ];
}

$columnsSettings['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'style' => 'width: 20px;',
    ],
];

// Получение колонок, которые могут быть отображены
$columnsSettings = array_merge(
    $columnsSettings,
    UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id)
);

$columnsSettings['actions'] = [
    'class' => 'yii\grid\ActionColumn',
    'template' => '{view} {update} {create}',
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
                : '/edm/foreign-currency-control/view';
            return Url::to([$url, 'id' => $model->id]);
        } elseif ($action === 'update') {
            return Url::to(['/edm/foreign-currency-control/update', 'id' => $model->id]);
        }
    },
    'buttons' => [
        'create' => function($model, $key, $index) {
            return Html::a(
                '<span class="glyphicon glyphicon-plus">',
                Url::to(['/edm/documents/create-fcc-from-existing-document', 'type' => 'FCC', 'id' => $key->id]),
                ['title' => Yii::t('app', 'Create')]
            );
        }
    ],
    'visibleButtons' => [
        'view' => function ($model, $key, $index) {
            return true;
        },

        'update' => function ($model, $key, $index) use ($userCanCreateDocuments) {
            return !VTBHelper::isVTBDocument($model) && $model->isModifiable() && $userCanCreateDocuments;
        },
        'create' => function ($model, $key, $index) use ($userCanCreateDocuments) {
            return !VTBHelper::isVTBDocument($model) && $userCanCreateDocuments  && $model->direction == Document::DIRECTION_OUT;
        },
    ]
];
?>
<?= InfiniteGridView::widget([
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
            : '/edm/foreign-currency-control/view';
        $options['ondblclick'] = "window.location='". Url::toRoute([$viewUrl, 'id' => $model->id]) ."'";
        $options['data']['status'] = $model->status;
        $options['data']['document-type'] = $model->type;

        if ($model->businessStatus == 'RJCT') {
            $options['class'] = 'danger';
        }

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
    $('#foreigncurrencycontrolsearch-date').inputmask('99.99.9999', { placeholder: 'дд.мм.гггг' });
    stickyTableHelperInit();
JS
);

echo ToTopButtonWidget::widget();
?>