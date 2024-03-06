<?php

use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

$urlParams['from'] = 'forSigning';

$columns['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'style' => 'width:1%;',
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'filterInputOptions' => [
        'style' => 'float:right;',
    ],
];

$columns['accountNumber'] = [
    'attribute' => 'accountNumber',
    'value' => function (\addons\edm\models\StatementRequest\StatementRequestSearch $model) {
        return $model->documentExtEdmStatementRequest->accountNumber;
    },
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
            'width' => '230px'
        ],
    ]),
];

$columns['startDate'] = [
    'attribute' => 'startDate',
    'value' => function (\addons\edm\models\StatementRequest\StatementRequestSearch $model) {
        return $model->documentExtEdmStatementRequest->startDate;
    },
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $filterModel,
        'attribute' => 'startDate',
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
    'contentOptions' => [
        'class' => 'text-center'
    ]
];

$columns['endDate'] = [
    'attribute' => 'endDate',
    'value' => function (\addons\edm\models\StatementRequest\StatementRequestSearch $model) {
        return $model->documentExtEdmStatementRequest->endDate;
    },
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $filterModel,
        'attribute' => 'endDate',
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
    'contentOptions' => [
        'class' => 'text-center'
    ]
];

$columns['signaturesRequired'] = [
    'attribute' => 'signaturesRequired',
    'filter' => MaskedInput::widget([
        'attribute'     => 'signaturesRequired',
        'model' => $filterModel,
        'mask' => '99',
        'clientOptions' => [
            'removeMaskOnSubmit' => true,
            'placeholder' => ''
        ]
    ]),
];

$columns['signaturesCount'] = [
    'attribute' => 'signaturesCount',
    'filter' => MaskedInput::widget([
        'attribute'     => 'signaturesCount',
        'model' => $filterModel,
        'mask' => '99',
        'clientOptions' => [
            'removeMaskOnSubmit' => true,
            'placeholder' => ''
        ]
    ]),
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
    //'header' => false,
    'checkboxOptions' => function($model, $key, $index, $column) use ($cachedEntries) {
        $hidden = false;
        $checked = !empty($cachedEntries['entries']) && array_key_exists($key, $cachedEntries['entries']);

        return [
            'class' => 'checkbox-selection',
            'style'   => "display: " . ($hidden ? 'none': 'block'),
            'checked' => $checked,
            'value'   => $key,
            'data' => ['id' => $model->id],
        ];
    }
];

// Получение колонок, которые могут быть отображены
$columnsSettings = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

foreach($columnsSettings as $setting => $value) {
    $columnsEnabled[$setting] = $value;
}
// Создать таблицу для вывода
$myGridWidget = InfiniteGridView::begin([
    'id' => 'forSigning',
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'columns' => $columnsEnabled,
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

// Формат поля выбора дат
$this->registerJS(<<<JS
    $('#paymentregistersearch-datecreate').datepicker('option', 'dateFormat', 'dd.mm.yy');
    $('#paymentregistersearch-datecreate').inputmask("99.99.9999", {placeholder:"дд.мм.гггг"});
    $('#paymentregistersearch-dateupdate').datepicker('option', 'dateFormat', 'dd.mm.yy');
    $('#paymentregistersearch-dateupdate').inputmask("99.99.9999", {placeholder:"дд.мм.гггг"});
JS
);

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $filterModel
    ]
);
?>
