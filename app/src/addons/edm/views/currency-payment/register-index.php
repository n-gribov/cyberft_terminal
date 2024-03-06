<?php

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\CurrencyPayment\CurrencyPaymentDocumentSearch;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use common\document\Document;
use common\document\DocumentPermission;
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

/** @var View $this */
/** @var CurrencyPaymentDocumentSearch $filterModel */
/** @var ActiveDataProvider $dataProvider */
/** @var array $selectedDocumentsIds */

$this->title = Yii::t('edm', 'Currency payments');

$listType ='edmCurrencyPaymentRegister';
$orgFilter = EdmHelper::getOrgFilter();
$accountFilter = EdmHelper::getAccountFilter(Yii::$app->user->identity->id, $orgFilter);

$entriesSelectionCacheKey = 'currencyRegisters';

$viewUrlCreator = function (CurrencyPaymentDocumentSearch $model) {
    return ['view-register', 'id' => $model->id];
};

?>

<?= $this->render('_tabs') ?>

<?php
$userCanCreateDocuments = Yii::$app->user->can(
    DocumentPermission::CREATE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => EdmDocumentTypeGroup::CURRENCY_PAYMENT
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
        function ($carry, Document $document) {
            if ($document->isDeletable()) {
                $carry[] = $document->id;
            }
            return $carry;
        },
        []
    );
}
?>

<?= $this->render(
    '_index-controls',
    compact('filterModel', 'userCanCreateDocuments', 'userCanDeleteDocuments', 'entriesSelectionCacheKey')
) ?>

<?= SelectedDocumentsCache::widget([
    'saveUrl' => Url::to(['/edm/documents/select-entries', 'tabMode' => $entriesSelectionCacheKey]),
]) ?>

<?php
$columns = [];
if ($userCanDeleteDocuments && count($deletableDocumentsIds) > 0) {
    // Колонка с чекбоксом для удаления
    $columns['delete'] = [
        'class'   => 'yii\grid\CheckboxColumn',
        'checkboxOptions' => function($model, $key, $index, $column) use ($selectedDocumentsIds, $deletableDocumentsIds) {
            $hidden = !in_array($model->id, $deletableDocumentsIds);
            $checked = !$hidden && in_array($model->id, $selectedDocumentsIds);
            return [
                'style'    => 'display: ' . ($hidden ? 'none': 'block'),
                'disabled' => $hidden,
                'value'    => $key,
                'class'    => 'delete-checkbox',
                'data-id'  => (string) $model->id,
                'checked'  => $checked,
            ];
        }
    ];
} else {
    $columns['deletedEmpty'] = [];
}

$optionalColumns = [
    'id' => [
        'attribute' => 'id',
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
    'type' => [
        'attribute' => 'type',
        'filter' => CurrencyPaymentDocumentSearch::getTypeFilter(),
    ],
    'payerOrganizationId' => [
        'attribute' => 'payerOrganizationId',
        'filter' => $orgFilter,
        'value' => function (CurrencyPaymentDocumentSearch $model) {
            return $model->payerName;
        },
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
//                'format' => 'dd.mm.yyyy',
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                'orientation' => 'bottom'
            ],
            'options' => [
                'class' => 'form-control',
                'style' => 'width: 90px'
            ]
        ]),
        'headerOptions' => [
            'style' => 'width:1%;',
        ],
        'contentOptions' => [
            'class' => 'text-center'
        ]
    ],
    'debitAccount' => [
        'attribute' => 'debitAccount',
        'filter' => $accountFilter,
    ],
    'currency' => [
        'attribute' => 'currency',
        'filter' => false,
        'value' => function($model) {
            return $model->getCurrency();
        }
    ],
    'totalSum' => [
        'attribute' => 'totalSum',
        'filter' => false,
        'contentOptions' => [
            'nowrap' => 'true',
        ],
        'value' => function($model) {
           return Yii::$app->formatter->asDecimal($model->totalSum, 2);
        }

    ],
    'bankBik' => [
        'attribute' => 'bankBik',
        'filter' => EdmHelper::getBankFilter(),
        'value' => function (CurrencyPaymentDocumentSearch $model) {
            return $model->bankName;
        },
    ],
    'paymentsCount' => [
        'attribute' => 'paymentsCount',
        'headerOptions' => [
            'class' => 'text-right'
        ],
        'contentOptions' => [
            'class' => 'text-right'
        ],
    ],
    'signaturesRequired' => [
        'attribute' => 'signaturesRequired',
        'headerOptions' => [
            'class' => 'text-right'
        ],
        'contentOptions' => [
            'class' => 'text-right'
        ],
    ],
    'signaturesCount' => [
        'attribute' => 'signaturesCount',
        'headerOptions' => [
            'class' => 'text-right'
        ],
        'contentOptions' => [
            'class' => 'text-right'
        ],
    ],
    'dateCreate' => [
        'attribute' => 'dateCreate',
        'filter' => kartik\widgets\DatePicker::widget([
            'model' => $filterModel,
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
                'style' => 'width: 130px'
            ]
        ]),
        'headerOptions' => [
            'style' => 'width:1%;',
        ],
        'contentOptions' => [
            'class' => 'text-center'
        ]
    ],
    'status' => [
        'attribute'     => 'status',
        'format'        => 'html',
        'filter' => Document::getStatusLabels(),
        'headerOptions' => [
            'style' => 'width: 130px',
        ],
        'value' => function (CurrencyPaymentDocumentSearch $model) {
            return Html::tag('span', $model->getStatusLabel(), ['title' => 'Status: ' . $model->status]);
        },
    ],
    'businessStatus' => [
        'attribute' => 'businessStatus',
        'filter' => DocumentHelper::getBusinessStatusesList(),
        'headerOptions' => [
            'style' => 'width: 130px',
        ],
        'value' => function(CurrencyPaymentDocumentSearch $model) {
            return PaymentRegisterDocumentExt::translateBusinessStatus($model) ?: '';
        },
    ],
];

$enabledColumns = UserColumnsSettings::getEnabledColumnsByType($optionalColumns, $listType, Yii::$app->user->id);
foreach ($enabledColumns as $column => $value) {
    $columns[$column] = $value;
}

$columns['actions'] = [
    'class'    => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'urlCreator' => function (string $action, CurrencyPaymentDocumentSearch $model) use ($viewUrlCreator) {
        if ($action === 'view') {
            return $viewUrlCreator($model);
        }
    },
];
?>

<?= InfiniteGridView::widget([
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
    'rowOptions'         => function (CurrencyPaymentDocumentSearch $model, $key, $index, $grid) use ($viewUrlCreator) {
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

        $options['ondblclick'] = "window.location='". Url::to($viewUrlCreator($model)) ."'";

        return $options;
    },
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'options'            => ['class' => 'grid-view documents-journal-grid'],
]);
?>

<?= $this->render('@addons/edm/views/documents/_searchModal', ['model' => $filterModel]) ?>

<?= ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($optionalColumns),
        'model' => $filterModel
    ]
);
?>

<?= $this->render('@addons/edm/views/documents/_fcoCreateModal'); ?>
