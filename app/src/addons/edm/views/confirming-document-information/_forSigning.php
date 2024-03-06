<?php
use addons\edm\models\DictOrganization;
use common\helpers\vtb\VTBHelper;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use yii\helpers\Url;
use yii\web\JsExpression;

$columns['number'] = [
    'attribute' => 'number',
    'contentOptions' => [
        'class' => 'text-right',
        'style' => 'width: 180px'
    ]
];

$columns['organizationId'] = [
    'attribute' => 'organizationId',
    'filter' => $orgFilter,
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '200px',
        'data-none-selected-text' => ''
    ],
    'value' => function($item) {
        return DictOrganization::getNameById($item->organizationId);
    }
];

$columns['date'] = [
    'attribute' => 'date',
    'contentOptions' => [
        'class' => 'text-right',
        'style' => 'width: 180px'
    ],
    'filter' => kartik\widgets\DatePicker::widget(
        [
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
                'class' => 'form-control'
            ]
        ]
    ),
];

$columns['contractPassport'] = 'contractPassport';
$columns['person'] = 'person';
$columns['contactNumber'] = 'contactNumber';

$columns['signaturesRequired'] = [
    'attribute' => 'signaturesRequired',
    'contentOptions' => [
        'class' => 'text-right'
    ],
];

$columns['signaturesCount'] = [
    'attribute' => 'signaturesCount',
    'contentOptions' => [
        'class' => 'text-right'
    ],
];

// Колонка с чекбоксом выделения
$columnsSettings['checkbox'] = [
    'class' => 'yii\grid\CheckboxColumn',
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

$columnsSettings['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'class' => 'text-right',
        'style' => "width: 20px;",
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'style' => "width: 20px;",
    ],
];

// Получение колонок, которые могут быть отображены
$columnsSettings = array_merge(
    $columnsSettings,
    UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id)
);

$columnsSettings['actions'] =     [
    'class' => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'headerOptions' => [
        'style' => 'width: 50px;'
    ],
    'contentOptions' => [
        'style' => 'min-width: 50px;'
    ],
    'urlCreator' => function ($action, $model, $key, $index) {
        if ($action === 'view') {
            $url = VTBHelper::isVTBDocument($model)
                ? '/edm/vtb-documents/view'
                : '/edm/confirming-document-information/view';
            return Url::to([$url, 'id' => $model->id, 'backUrl' => Url::current()]);
        }
    },
];

echo InfiniteGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'highlightsByStatus' => true,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $viewUrl = VTBHelper::isVTBDocument($model)
            ? '/edm/vtb-documents/view'
            : '/edm/confirming-document-information/view';
        $options['ondblclick'] = "window.location='". Url::toRoute(
                [
                    $viewUrl,
                    'id' => $model->id,
                    'backUrl' => Url::current()
                ]
            ) ."'";

        return $options;
    },
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'columns'      => $columnsSettings,
]);

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $filterModel
    ]
);

$this->registerJS(<<<JS
    $('#confirmingdocumentinformationsearch-date').inputmask('99.99.9999', { placeholder: 'дд.мм.гггг' });
JS
);