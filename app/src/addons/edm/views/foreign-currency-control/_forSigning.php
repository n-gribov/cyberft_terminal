<?php

use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use common\helpers\vtb\VTBHelper;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use yii\helpers\Url;
use yii\web\JsExpression;

$columns = [];
$columns['number'] = 'number';

// Колонка с чекбоксом выделения
$columnsSettings['checkbox'] = [
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

$columnsSettings['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'style' => "width: 20px;",
    ],
];

$columns['number'] = [
    'attribute' => 'number',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
];

$columns['organizationId'] = [
    'attribute' => 'organizationId',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
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
];

$columns['accountId'] = [
    'attribute' => 'accountId',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'filter' => $accountFilter,
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '200px',
        'data-none-selected-text' => ''
    ],
    'value' => function($item) {
        return EdmPayerAccount::getNumberById($item->accountId);
    }
];

$columns['date'] = [
    'attribute' => 'date',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
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
            'style' => 'width: 130px'
        ]
    ]),
];


$columns['countryCode'] = 'countryCode';

$columns['correctionNumber'] = [
    'attribute' => 'correctionNumber',
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
];

$columns['signaturesCount'] = [
    'attribute' => 'signaturesCount',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
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
            $viewUrl = VTBHelper::isVTBDocument($model)
                ? '/edm/vtb-documents/view'
                : '/edm/foreign-currency-control/view';
            return Url::to(
                [
                    $viewUrl,
                    'id' => $model->id,
                    'backUrl' => Url::current()
                ]
            );
        }
    }
];
// Создать таблицу для вывода
echo InfiniteGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $viewUrl = VTBHelper::isVTBDocument($model)
            ? '/edm/vtb-documents/view'
            : '/edm/foreign-currency-control/view';
        $options['ondblclick'] = "window.location='". Url::toRoute([
            $viewUrl,
            'id' => $model->id,
            'backUrl' => Url::current()
        ]) ."'";

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
