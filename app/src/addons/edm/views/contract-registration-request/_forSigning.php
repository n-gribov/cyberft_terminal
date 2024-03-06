<?php

use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestSearch;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictOrganization;
use addons\edm\models\VTBContractRequest\VTBContractRequestContract;
use addons\edm\models\VTBContractRequest\VTBContractRequestExt;
use common\document\Document;
use common\helpers\vtb\VTBHelper;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

function getViewUrl(Document $document)
{
    if ($document->type === \addons\edm\models\VTBCredReg\VTBCredRegType::TYPE) {
        return '/edm/loan-agreement-registration-request/view';
    } else if ($document->type === \addons\edm\models\VTBContractUnReg\VTBContractUnRegType::TYPE) {
        return '/edm/contract-unregistration-request/view';
    } else if (VTBHelper::isVTBDocument($document)) {
        return '/edm/vtb-documents/view';
    } else {
        return '/edm/contract-registration-request/view';
    }
}

$columns['number'] = [
    'attribute' => 'number',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'style' => 'width: 180px'
    ]
];

$columns['date'] = [
    'attribute' => 'date',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ],
    'label' => Yii::t('edm', 'Document date'),
    'format' => ['date', 'dd.MM.Y'],
    'filter' => kartik\widgets\DatePicker::widget(
        [
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
                'style' => 'width: 130px'
            ]
        ]
    ),
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

$columns['type'] = [
    'attribute' => 'type',
    'filter' => ContractRegistrationRequestSearch::getTypeFilter(),
    'value' => function(ContractRegistrationRequestSearch $item) {
        return $item->getTypeLabel();
    },
    'contentOptions' => [
        'style' => 'width: 180px'
    ],
];

$columns['amount'] = [
    'attribute' => 'amount',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right no-padding',
    ],
    'value' => function ($document, $params, $index, $column) {
        $extModel = $document->extModel;
        $values = [];
        if ($extModel instanceof ContractRegistrationRequestExt) {
            $values = [$extModel->amount];
        } else if ($extModel instanceof VTBContractRequestExt) {
            $values = array_map(
                function (VTBContractRequestContract $contract) {
                    return $contract->amount;
                },
                $extModel->contracts
            );
        }
        // Вывести страницу
        return $this->render('_valuesTable', compact('values'));
    },
    'format' => 'raw',
];

$columns['currencyId'] = [
    'attribute' => 'currencyId',
    'filter' => Select2::widget([
        'model' => $filterModel,
        'attribute' => 'currencyId',
        'data' => ArrayHelper::map(DictCurrency::getValues(), 'id', 'name'),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'prompt' => '',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft'
        ],
    ]),
    'contentOptions' => [
        'class' => 'no-padding',
    ],
    'value' => function ($document, $params, $index, $column) {
        $extModel = $document->extModel;
        $values = [];
        if ($extModel instanceof ContractRegistrationRequestExt) {
            $values = [$extModel->currency ? $extModel->currency->name : null];
        } else if ($extModel instanceof VTBContractRequestExt) {
            $values = array_map(
                function (VTBContractRequestContract $contract) {
                    return $contract->currency ? $contract->currency->name : null;
                },
                $extModel->contracts
            );
        }
        // Вывести страницу
        return $this->render('_valuesTable', compact('values'));
    },
    'format' => 'raw',
];

$columns['signaturesRequired'] = [
    'attribute' => 'signaturesRequired',
    'headerOptions' => [
        'class' => 'text-right'
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ]
];

$columns['signaturesCount'] = [
    'attribute' => 'signaturesCount',
    'headerOptions' => [
        'class' => 'text-right'
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ]
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
        'class' => 'text-right'
    ]
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
            return Url::to([getViewUrl($model), 'id' => $model->id, 'backUrl' => Url::current()]);
        }
    },
];
// Создать таблицу для вывода
echo InfiniteGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'highlightsByStatus' => true,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options['ondblclick'] = "window.location='". Url::toRoute([
                getViewUrl($model),
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

$this->registerJS(<<<JS
    $('#contractregistrationrequestsearch-date').inputmask('99.99.9999', { placeholder: 'дд.мм.гггг' });
JS);
?>

<style>
    table.values-table {
        background-color: transparent;
        width: 100%;
    }
    table.values-table td {
        padding: 8px 4px;
        white-space: nowrap;
    }
    table.values-table td {
        padding: 8px 4px;
        border-bottom: 1px solid #ddd;
    }
    table.values-table tr:last-child td {
        border-bottom: none;
    }
    .no-padding {
        padding: 0 !important;
    }
</style>
