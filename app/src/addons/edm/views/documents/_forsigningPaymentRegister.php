<?php

use addons\edm\helpers\EdmHelper;
use common\helpers\Currencies;
use common\helpers\Html;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

$urlParams['from'] = 'forSigning';

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
    ],
];

$columns['payer'] = [
    'attribute'     => 'payer',
//    'filter' => $payers,
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $filterModel,
        'attribute' => 'payer',
        'data' => $orgFilter,
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'prompt' => '',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft',
        ],
    ]),
    'headerOptions' => [
        'style' => 'width: 180px;'
    ],
    'value' => function($model) {
        return $model->getPayerName();
    }
];

$columns['bankBik'] = [
    'attribute' => 'bankBik',
    'filter' => EdmHelper::getBankFilter(),
    'value' => function ($model) {
        return $model->bankName;
    },
];

$columns['accountNumber'] = [
    'attribute'     => 'accountNumber',
    //'filter' => $accounts,
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $filterModel,
        'attribute' => 'accountNumber',
        'data' => $accountFilter,
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
        'data-width' => '70px',
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
];

$columns['count'] = [
    'attribute'          => 'count',
    'filter' => MaskedInput::widget([
        'attribute'     => 'count',
        'model' => $filterModel,
        'mask' => '999999',
        'clientOptions' => [
            'removeMaskOnSubmit' => true,
            'placeholder' => ''
        ]
    ]),
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
    'filter' => true,
];

$columns['signaturesCount'] = [
    'attribute' => 'signaturesCount',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'filter' => true,
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
        'class' => 'text-right',
        'style' => 'width:1%;',
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ]
];

$columns['dateUpdate'] = [
    'attribute' => 'dateUpdate',
    'value' => function ($model, $key, $index, $column) {
        return $model->dateUpdate != '0000-00-00 00:00:00' ? $model->dateUpdate : '-';
    },
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $filterModel,
        'attribute' => 'dateUpdate',
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
    'visible' => $dataProvider->count > 0,
    'checkboxOptions' => function($model, $key, $index, $column) use ($cachedEntries) {
        $hidden = false;
        $checked = !empty($cachedEntries['entries']) && array_key_exists($key, $cachedEntries['entries']);

        return [
            'class' => 'checkbox-selection',
            'style' => 'display: ' . ($hidden ? 'none' : 'block'),
            'checked' => $checked,
            'value'   => $key,
            'data' => ['id' => $model->id],
        ];
    },
    'visible' => $userCanSignDocuments,
];

// Получение колонок, которые могут быть отображены
$columnsSettings = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

foreach($columnsSettings as $setting => $value) {
    $columnsEnabled[$setting] = $value;
}

$columnsEnabled['actions'] = [
    'class'    => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'buttons'  => [
        'view' => function ($url, $model, $key) use ($urlParams) {
            return Html::a(
                '<span class="glyphicon glyphicon-eye-open"></span>',
                Url::toRoute(['/edm/payment-register/view', 'id' => $model->id, 'redirectUrl' => '/edm/documents/signing-index'])
            );
        }
    ],
];

$myGridWidget = InfiniteGridView::begin([
    'id' => 'forSigning',
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'rowOptions' => function ($model){
        $options['ondblclick'] = "window.location='" .
            Url::toRoute(['/edm/payment-register/view', 'id' => $model->id, 'redirectUrl' => '/edm/documents/signing-index']) ."'";

        return $options;
    },
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'columns' => $columnsEnabled,
    'options' => ['class' => 'grid-view documents-journal-grid'],
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

// Формат поля выбора дат
$this->registerJS(<<<JS
    $('#paymentregistersearch-datecreate').datepicker('option', 'dateFormat', 'dd.mm.yy');
    $('#paymentregistersearch-datecreate').inputmask('99.99.9999', {placeholder:"дд.мм.гггг"});
    $('#paymentregistersearch-dateupdate').datepicker('option', 'dateFormat', 'dd.mm.yy');
    $('#paymentregistersearch-dateupdate').inputmask('99.99.9999', {placeholder:"дд.мм.гггг"});
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
?>
