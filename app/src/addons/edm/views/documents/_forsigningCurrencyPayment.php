<?php

use addons\edm\helpers\EdmHelper;
use addons\edm\models\CurrencyPayment\CurrencyPaymentDocumentSearch;
use common\helpers\Currencies;
use common\helpers\Html;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

$this->title = Yii::t('edm', 'Currency payments');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = $this->title;

$language = Yii::$app->language;
$differentAccountsMessage = Yii::t('edm', 'Document can only be created for single payer account');

$columns['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'class' => 'text-right',
        'width' => '55px',
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'width' => '55px',
    ],
    'filterInputOptions' => [
        'style' => 'float:right;',
        'width' => '55px',
    ],
];

$columns['documentKind'] = [
    'attribute' => 'documentKind',
    'filter' => CurrencyPaymentDocumentSearch::getDocumentKindFilter(),
    'format' => 'html',
    'enableSorting' => false,
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '90px',
        'data-none-selected-text' => ''
    ],
    'value' => function ($item, $params) {
        return $item->getDocumentKindLabel();
    },
];

$columns['numberDocument'] = [
    'attribute' => 'numberDocument',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'filterInputOptions' => [
        'style' => 'float:right;',
    ],
];

$columns['date'] = [
    'attribute' => 'date',
    'value'  => 'date',
    'format' => ['date', 'dd.MM.Y'],
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $filterModel,
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
            'style'     => 'width: 90px'
        ]
    ]),
    'headerOptions' => [
        'style' => 'width:1%;',
    ],
    'contentOptions' => [
        'class' => 'text-center'
    ]
];

$columns['payerOrganizationId'] = [
    'attribute' => 'payerOrganizationId',
    'filter' => $orgFilter,
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '150px',
        'data-none-selected-text' => ''
    ],
    'contentOptions' => [
        'style' => 'width: 150px'
    ],
    'headerOptions' => [
        'style' => 'width: 150px'
    ],
    'value' => function($model) {
        return $model->payerName;
    }
];

$columns['bankBik'] = [
    'attribute' => 'bankBik',
    'filter' => EdmHelper::getBankFilter(),
    'value' => function (CurrencyPaymentDocumentSearch $model) {
        return $model->bankName;
    },
];

$columns['debitAccount'] = [
    'attribute'     => 'debitAccount',
    'filter' => $accountFilter,
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '170px',
        'data-none-selected-text' => ''
    ],
    'contentOptions' => [
        'style' => 'width: 170px'
    ],
    'headerOptions' => [
        'style' => 'width: 170px'
    ]
];

$columns['currencySum'] = [
    'attribute' => 'currencySum',
    'label' => Yii::t('edm', 'Sum'),
    'format' => ['decimal', 2],
    'filter' => MaskedInput::widget([
        'attribute'     => 'currencySum',
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
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'nowrap' => 'nowrap'
    ],
    'filterInputOptions' => [
        'style' => 'float:right;',
    ],
];

$columns['currency'] = [
    'attribute' => 'currency',
    'filter' => Currencies::getCodeLabels(),
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'filterInputOptions' => [
        'data-width' => '55px',
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
];

$columns['paymentsCount'] = [
    'attribute' => 'paymentsCount',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
];

$columns['signaturesRequired'] = [
    'attribute' => 'signaturesRequired',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'filter' => false,
];

$columns['signaturesCount'] = [
    'attribute' => 'signaturesCount',
    'headerOptions' => [
        'class' => 'text-right',
        'width' => '55px',
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'width' => '55px',
    ],
    'filter' => false,
];

$columns['dateCreate'] = [
    'attribute' => 'dateCreate',
    'value'     => 'dateCreate',
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $filterModel,
        'attribute' => 'dateCreate',
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
    'headerOptions' => [
        'style' => 'width:1%;',
    ],
    'contentOptions' => [
        'class' => 'text-center'
    ]
];

// Колонка с чекбоксом выделения
$columnsEnabled['checkbox'] = [
    'class' => 'yii\grid\CheckboxColumn',
    'checkboxOptions' => function($model, $key, $index, $column) use ($cachedEntries) {
        $hidden = false;
        $checked = !empty($cachedEntries['entries']) && array_key_exists($key, $cachedEntries['entries']);

        return [
            'class' => 'checkbox-selection',
            'style'   => 'display: ' . ($hidden ? 'none': 'block'),
            'checked' => $checked,
            'value'   => $key,
            'data' => ['id' => $model->id],
        ];
    },
    'visible' => $userCanSignDocuments,
];

// Получение колонок, которые могут быть отображены
$columnsDisabledByDefault = ['id'];
$columnsEnabled = array_merge(
    $columnsEnabled,
    UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id, $columnsDisabledByDefault)
);

// Обязательные колонки, которые должны отображаться в любом случае
$columnsEnabled['actions'] = [
    'format' => 'raw',
    'filterInputOptions' => [
        'style'     => 'width: 20px'
    ],
    'value'	=> function ($item, $params) {
        if (is_null($item) || is_null($item->extModel)) {
            return "Invalid document!";
        }

        return Html::a(
            '<span class="glyphicon glyphicon-eye-open">',
            '#',
            [
                'title' => Yii::t('app', 'View'),
                'class' => 'view-modal-btn',
            ]
        );
    }
];
// Создать таблицу для вывода
$myGridWidget = InfiniteGridView::begin([
    'id' => 'documents-grid',
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'rowOptions' => function (CurrencyPaymentDocumentSearch $model, $key) {
        $type = $model->type;

        if (in_array($model->status, array_merge($model->getErrorStatus(),['']))) {
            $options['class'] = 'bg-alert-danger';
        } else if (in_array($model->status, $model->getProcessingStatus())) {
            $options['class'] = 'bg-alert-warning';
        }

        if (is_null($model->extModel)) {
            $options['class'] = 'bg-alert-danger';
        }

        $options['data'] = [
            'document-id' => $model->id,
            'document-kind' => $model->documentKind,
        ];

        return $options;
    },
    'columns' => $columnsEnabled,
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

// Формат поля выбора дат
$this->registerJS(<<<JS
    $('#fco-date').datepicker('option', 'dateFormat', 'dd.mm.yy');
    $('#fco-date').inputmask('99.99.9999', { placeholder : 'дд.мм.гггг'});
    $('#fco-datecreate').datepicker('option', 'dateFormat', 'dd.mm.yy');
    $('#fco-datecreate').inputmask('99.99.9999', { placeholder : 'дд.мм.гггг'});
JS
);

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $filterModel
    ]
);

echo ToTopButtonWidget::widget();
// Вывести модальное окно с формой редактирования
echo $this->render('_fcoUpdateModal');

// Вывести модальное окно с просмотром
echo $this->render('_fcoViewModal');

$this->registerJs(<<<JS
    function onViewPaymentClick(event) {
        event.preventDefault();

        var row = $(this).closest('tr');
        if (row.length === 0) {
            return;
        }

        var documentId = row.data('document-id');
        var documentKind = row.data('document-kind');
        var isRegister = documentKind === 'register';

        if (isRegister) {
            location.href = '/edm/currency-payment/view-register?id=' + documentId + '&backUrl=' + location.href;
        } else {
            $('#fcoViewModal .modal-body').html('');
            $.ajax({
                url: '/edm/currency-payment/view-payment?id=' + documentId,
                type: 'get',
                success: function (answer) {
                    $('#fcoViewModal').modal('show');
                    $('#fcoViewModal .modal-body').html(answer);
                }
            });
        }
    }
    $('body').on('click', '.view-modal-btn', onViewPaymentClick);
    $('body').on('dblclick', '#documents-grid tbody tr', onViewPaymentClick);
JS);
?>

<style>
    .grid-view thead .dropdown-menu > li > a {
        padding: 3px 5px;
    }
</style>