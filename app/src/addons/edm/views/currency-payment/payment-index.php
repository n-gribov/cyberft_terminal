<?php

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\CurrencyPayment\CurrencyPaymentSearch;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\Currencies;
use common\helpers\DocumentHelper;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\documents\SelectedDocumentsCache;
use common\widgets\InfiniteGridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\MaskedInput;

/** @var View $this */
/** @var CurrencyPaymentSearch $filterModel */
/** @var ActiveDataProvider $dataProvider */
/** @var array $selectedDocumentsIds */

$this->title = Yii::t('edm', 'Currency payments');

$listType ='edmCurrencyPaymentOrder';
$orgFilter = EdmHelper::getOrgFilter();
$accountFilter = EdmHelper::getAccountFilter(Yii::$app->user->identity->id, $orgFilter);

$entriesSelectionCacheKey = 'currencyPayments';

// Вывести блок закладок
echo $this->render('_tabs');

$userCanCreateDocuments = Yii::$app->user->can(
    DocumentPermission::CREATE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => EdmDocumentTypeGroup::CURRENCY_PAYMENT,
    ]
);

$userCanDeleteDocuments = Yii::$app->user->can(
    DocumentPermission::DELETE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => EdmDocumentTypeGroup::CURRENCY_PAYMENT,
    ]
);

$deletableDocumentsIds = [];
if ($userCanDeleteDocuments) {
    $deletableDocumentsIds = array_reduce(
        $dataProvider->models,
        function ($carry, CurrencyPaymentSearch $payment) {
            if (!$payment->isInRegister && $payment->isDeletable()) {
                $carry[] = $payment->id;
            }
            return $carry;
        },
        []
    );
}

// Вывести блок управления
echo $this->render(
    '_index-controls',
    compact('filterModel', 'userCanCreateDocuments', 'userCanDeleteDocuments', 'entriesSelectionCacheKey')
);
?>

<?= SelectedDocumentsCache::widget([
    'saveUrl' => Url::to(['/edm/documents/select-entries', 'tabMode' => $entriesSelectionCacheKey]),
]) ?>

<?php
$columns = [];
if ($userCanDeleteDocuments && count($deletableDocumentsIds) > 0) {
    // Колонка с чекбоксом для удаления
    $columns['delete'] = [
        'class'           => 'yii\grid\CheckboxColumn',
        'checkboxOptions' => function ($model, $key, $index, $column) use (
            $selectedDocumentsIds,
            $deletableDocumentsIds
        ) {
            $hidden = !in_array($model->id, $deletableDocumentsIds);
            $checked = !$hidden && in_array($model->id, $selectedDocumentsIds);
            return [
                'style'    => "display: " . ($hidden ? 'none' : 'block'),
                'disabled' => $hidden,
                'value'    => $key,
                'class'    => 'delete-checkbox',
                'data-id'  => (string)$model->id,
                'checked'  => $checked,
            ];
        }
    ];
} else {
    $columns['deletedEmpty'] = [];
}

$optionalColumns = [
    'numberDocument' => [
        'attribute' => 'numberDocument',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 75px'
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 75px'
        ],
        'filterInputOptions' => [
            'style' => 'float:right;',
        ],
    ],
    'date' => [
        'attribute' => 'date',
        'format' => ['date', 'dd.MM.Y'],
        'filter' => kartik\widgets\DatePicker::widget([
            'model' => $filterModel,
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
                'style'     => 'width: 90px'
            ]
        ]),
        'headerOptions' => [
            'style' => 'width:1%;',
        ],
        'contentOptions' => [
            'class' => 'text-center'
        ]
    ],
    'payerOrganizationId' => [
        'attribute' => 'payerOrganizationId',
        'filter' => $orgFilter,
        'value' => function (CurrencyPaymentSearch $model) {
            return $model->payerName;
        },
    ],
    'bankBik' => [
        'attribute' => 'bankBik',
        'filter' => EdmHelper::getBankFilter(),
        'value' => function (CurrencyPaymentSearch $model) {
            return $model->bankName;
        },
    ],
    'debitAccount' => [
        'attribute' => 'debitAccount',
        'filter' => $accountFilter,
    ],
    'sum' => [
        'attribute' => 'sum',
//        'format' => ['decimal', 2],
        'filter' => MaskedInput::widget([
            'attribute'     => 'sum',
            'model' => $filterModel,
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
        'contentOptions' => [
            'class' => 'text-right'
        ],
        'value' => function($model) {
           return Yii::$app->formatter->asDecimal($model->sum, 2);
        }
    ],
    'currency' => [
        'attribute' => 'currency',
        'filter' => Currencies::getCodeLabels(),
        'headerOptions' => [
            'class' => 'text-right',
        ],
        'contentOptions' => [
            'class' => 'text-right',
        ],
        'filterInputOptions' => [
            'data-width' => '60px',
            'class' => 'form-control selectpicker',
            'data-none-selected-text' => ''
        ],
    ],
    'beneficiary' => [
        'attribute' => 'beneficiary',
    ],
    'paymentPurpose' => [
        'attribute' => 'paymentPurpose',
    ],
    'businessStatus' => [
        'attribute' => 'businessStatus',
        'filter' => DocumentHelper::getBusinessStatusesList(),
        'headerOptions' => [
            'style' => 'width: 130px',
        ],
        'value' => function(CurrencyPaymentSearch $model) {
            return PaymentRegisterDocumentExt::translateBusinessStatus($model) ?: '';
        },
    ],
];

$enabledColumns = UserColumnsSettings::getEnabledColumnsByType($optionalColumns, $listType, Yii::$app->user->id);
foreach ($enabledColumns as $column => $value) {
    $columns[$column] = $value;
}

$columns['viewRegister'] = [
    'class'    => 'yii\grid\ActionColumn',
    'template' => '{viewRegister}',
    'buttons' => [
        'viewRegister' => function ($url, CurrencyPaymentSearch $model) {
            if ($model->isInRegister) {
                return Html::a(
                    '<span class="glyphicon glyphicon-check"></span>',
                    ['/edm/currency-payment/view-register', 'id' => $model->id],
                    ['title' => Yii::t('edm', 'View payment register #{id}', ['id' => $model->id])]
                );
            } else {
                return '';
            }
        },
    ],
    'headerOptions' => [
        'style' => 'width: 1%;'
    ],
    'contentOptions' => [
        'style' => 'width: 1%;'
    ],
];

$columns['view'] = [
    'class'    => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'buttons' => [
        'view' => function ($url, CurrencyPaymentSearch $model) {
            return Html::a(
                '<span class="glyphicon glyphicon-eye-open">',
                '#',
                [
                    'title' => Yii::t('app', 'View'),
                    'class' => 'view-payment-button',
                ]
            );
        },
        'create' => function ($url, CurrencyPaymentSearch $model) {
            if ($model->type === 'MT103' && $model->direction === Document::DIRECTION_OUT) {
                return Html::a(
                    '<span class="glyphicon glyphicon-plus">',
                    '#',
                    [
                        'title' => Yii::t('app', 'Create'),
                        'class' => 'create-modal-btn',
                        'data' => [
                            'id' => $model->id,
                            'type' => $model->type
                        ]
                    ]
                );
            } else {
                return '';
            }
        }
    ],
    'headerOptions' => [
        'style' => 'width: 1%;'
    ],
    'contentOptions' => [
        'style' => 'width: 1%;'
    ],
];

if ($userCanCreateDocuments) {
    $columns['create'] = [
        'class'          => 'yii\grid\ActionColumn',
        'template'       => '{create}',
        'buttons'        => [
            'create' => function ($url, CurrencyPaymentSearch $model) {
                if ($model->type === 'MT103' && $model->direction === Document::DIRECTION_OUT) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-plus">',
                        '#',
                        [
                            'title' => Yii::t('app', 'Create'),
                            'class' => 'create-modal-btn',
                            'data'  => [
                                'id'   => $model->id,
                                'type' => $model->type
                            ]
                        ]
                    );
                } else {
                    return '';
                }
            }
        ],
        'headerOptions'  => [
            'style' => 'width: 1%;'
        ],
        'contentOptions' => [
            'style' => 'width: 1%;'
        ],
    ];
}

// Создать таблицу для вывода
echo InfiniteGridView::widget([
    'id'                 => 'payments-grid',
    'emptyText'          => Yii::t('other', 'No documents matched your query'),
    'summary'            => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider'       => $dataProvider,
    'filterModel'        => $filterModel,
    'formatter'          => [
        'class'            => 'yii\i18n\Formatter',
        'decimalSeparator' => '.',
        'nullDisplay'      => '',
    ],
    'highlightsByStatus' => true,
    'columns'            => $columns,
    'rowOptions'         => function (CurrencyPaymentSearch $model, $key, $index, $grid) {
        $options = ['data-document-id' => $model->id];

        $shouldAlert = is_null($model->extModel)
            || $model->status === Document::STATUS_DELETED
            || $model->status === Document::STATUS_SIGNING_REJECTED
            || $model->businessStatus === 'RJCT';
        if ($shouldAlert) {
            $options['class'] = 'danger';
        }

        $options['data']['status'] = $model->status;
        $options['data']['document-type'] = $model->type;
        $options['data']['document-id'] = $model->id;
        $options['data']['payment-id'] = $model->isInRegister ? $model->extId : '';

        return $options;
    },
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'options'            => ['class' => 'grid-view documents-journal-grid'],
]);

// Вывести модальное окно с формой поиска
echo $this->render('@addons/edm/views/documents/_searchModal', ['model' => $filterModel]);
// Вывести модальное окно с формой создания
echo $this->render('@addons/edm/views/documents/_fcoCreateModal');
// Вывести модальное окно с формой редактирования 
echo $this->render('@addons/edm/views/documents/_fcoUpdateModal');
// Вывести модальное окно с формой просмотра
echo $this->render('@addons/edm/views/documents/_fcoViewModal');

echo ColumnsSettingsWidget::widget([
    'listType' => $listType,
    'columns' => array_keys($optionalColumns),
    'model' => $filterModel
]);

$this->registerJs(<<<JS
    function onViewPaymentClick(event) {
        event.preventDefault();

        var row = $(this).closest('tr');
        if (row.length === 0) {
            return;
        }

        var documentId = row.data('document-id');
        var paymentId = row.data('payment-id');

        fcpModalView(documentId, paymentId);
    }
    function fcpModalView(documentId, paymentId) {
        var isRegister = !!paymentId;

        var url = isRegister
            ? '/edm/currency-payment/view-register-payment?id=' + documentId + '&paymentId=' + paymentId
            : '/edm/currency-payment/view-payment?id=' + documentId;

        $('#fcoViewModal .modal-body').html('');
        $.ajax({
            url: url,
            type: 'get',
            success: function (answer) {
                $('#fcoViewModal').modal('show');
                $('#fcoViewModal .modal-body').html(answer);
            }
        });
    }
    $('body').on('click', '.view-payment-button', onViewPaymentClick);
    $('body').on('dblclick', '#payments-grid tbody tr', onViewPaymentClick);

    $('body').on('click', '.create-modal-btn', function(e) {
        e.preventDefault();

        $('#fcoCreateModalTitle').html('Создание валютной операции');
        $('#fcoCreateModalButtons').hide();
        $('#fcoCreateModal .modal-body').html('');
        $('#fcoUpdateModal .modal-body').html('');

        var id = $(this).data('id');
        var type = $(this).data('type');

        $.get('/edm/foreign-currency-operation-wizard/create-from-existing-document', {id: id, type: type}).done(function(result) {
            $('#fcoCreateModal .modal-body').html(result);
            $('#fcoCreateModal').modal('show');
        });
    });
JS);

if ($documentId = Yii::$app->session->getFlash('fcpCU')) {
    $this->registerJs("fcpModalView('$documentId', null);", View::POS_READY);
}

// Создание документа из шаблона
$templateId = Yii::$app->request->get('template');

if ($templateId) {
    $this->registerJS(<<<JS
        var type = 'ForeignCurrencyPayment';

        $.ajax({
            url: '/edm/foreign-currency-operation-wizard/create?type=' + type + '&templateId=' + $templateId,
            type: 'get',
            success: function(answer) {
                // Добавляем html содержимое на страницу формы
                $('#fcoCreateModalTitle').html('Создание валютной операции');
                $('#fcoCreateModal .modal-body').html(answer);
                $('#fcoCreateModalButtons').show();
                $('#fcoCreateModal').modal('show');
            }
        });
    JS);
}
