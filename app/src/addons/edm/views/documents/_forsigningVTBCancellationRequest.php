<?php

use addons\edm\models\VTBDocument\presenters\config\CancellationRequestPresenterConfig;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Url;
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
    'filter' => DatePicker::widget([
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
        ]
    ]),
];

$columns['cancelDocumentNum'] = [
    'attribute' => 'cancelDocumentNum',
    'format' => 'html'
];

$columns['cancelDocumentType'] = [
    'attribute' => 'cancelDocumentType',
    'format' => 'html',
    'value' => function($item){
        if (!isset($item->cancelDocumentType)) {
            return '';
		} else {
            return CancellationRequestPresenterConfig::cancelDocTypeIdMap()[$item->cancelDocumentType];
		}
    },
    'filter' => Select2::widget([
        'model' => $filterModel,
        'attribute' => 'cancelDocumentType',
        'data' => CancellationRequestPresenterConfig::cancelDocTypeIdMap(),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'prompt' => '',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft'
        ],
    ]),
];

$columns['cancelDocumentDate'] = [
    'attribute' => 'cancelDocumentDate',
    'format' => ['date', 'dd.MM.Y'],
    'filter' => DatePicker::widget(
        [
            'model' => $filterModel,
            'attribute' => 'cancelDocumentDate',
            'options' => [
                'class' => 'form-control',
            ]
        ]
    ),
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

$columns['actions'] =     [
    'class' => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'headerOptions' => [
        'style' => 'width: 50px;',
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'style' => 'width: 50px;',
    ],
    'urlCreator' => function ($action, $model, $key, $index) {
        if ($action === 'view') {
            return Url::to(['/edm/vtb-documents/view', 'id' => $model->id, 'backUrl' => Url::current()]);
        }
    },
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
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'columns' => $columnsEnabled
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $filterModel
    ]
);
?>
