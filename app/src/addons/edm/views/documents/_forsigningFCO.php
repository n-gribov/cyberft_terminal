<?php

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationSearch;
use common\helpers\Currencies;
use common\helpers\Html;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

$this->title = Yii::t('edm', 'Currency operations');
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

$columns['types'] = [
    'attribute' => 'types',
    'filter' =>  ForeignCurrencyOperationSearch::getDocTypesFilter(),
    'format' => 'html',
    'enableSorting' => false,
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '130px',
        'data-none-selected-text' => ''
    ],
    'value' => function ($item, $params) {
        return Html::tag('span', $item->getDocTypeLabel(), ['title' => $item->type]);
    },
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

$columns['payer'] = [
    'attribute' => 'payer',
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
        /**
         * Получение наименования плательщика по счету дебета
         */
        $account = EdmPayerAccount::findOne(['number' => $model->debitAccount]);
        if ($account) {
            return $account->getPayerName();
        } else {
            return $model->payerAccount;
        }
    }
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

$columns['currencySum'] = [
    'attribute' => 'currencySum',
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

$columns['sum'] = [
    'format' => 'raw',
    'attribute' => 'sum',
    'format' => ['decimal', 2],
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
        ]
    ),
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
    'visible' => $dataProvider->count > 0,
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
$columnsEnabled = array_merge(
    $columnsEnabled,
    UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id)
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
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'rowOptions' => function ($model, $key) {
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
            'document-id' => $key,
            'document-type' => $model->type,
            'document-ext-type' => @$model->extModel->documentType,
        ];

        return $options;
    },
    'columns' => $columnsEnabled,
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

// Формат поля выбора дат
$this->registerJS(<<<JS
    $('#fco-date').inputmask('99.99.9999', { placeholder : 'дд.мм.гггг'});
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
    function onViewDocumentClick(event) {
        event.preventDefault();

        var row = $(this).closest('tr');
        if (row.length === 0) {
            return;
        }

        var id = row.data('document-id');
        var type = row.data('document-type');
        var extType = row.data('document-ext-type');

        $('#fcoViewModal .modal-body').html('');

        var isModalView = true;
        var viewUrl = null;

        if (type.indexOf('VTB') === 0) {
            isModalView = false;
            viewUrl = '/edm/vtb-documents/view?id=' + id;
        } else if (extType === 'ForeignCurrencyPurchaseRequest' || extType === 'ForeignCurrencySellRequest') {
            viewUrl = '/edm/documents/foreign-currency-operation-view?id=' + id + '&ajax=1';
        } else if (extType === 'ForeignCurrencySellTransitAccount') {
            viewUrl = '/edm/documents/foreign-currency-sell-transit-view?id=' + id + '&ajax=1';
        } else if (extType === 'ForeignCurrencyConversion') {
            viewUrl = '/edm/documents/foreign-currency-conversion-view?id=' + id + '&ajax=1';
        }

        if (viewUrl === null) {
            return;
        }

        if (isModalView) {
            $.ajax({
                url: viewUrl,
                type: 'get',
                success: function(answer) {
                    // Добавляем html содержимое на страницу формы
                    $("#fcoViewModal .modal-body").html(answer);
                }
            });

            $('#fcoViewModal').modal('show');
        } else {
            location.href = viewUrl;
        }
    }

    $('body').on('click', '.view-modal-btn', onViewDocumentClick);
    $('body').on('dblclick', '#documents-grid tbody tr', onViewDocumentClick);
JS);
?>

<style>
    .grid-view thead .dropdown-menu > li > a {
        padding: 3px 5px;
    }
</style>