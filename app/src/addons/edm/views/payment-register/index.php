<?php

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use addons\edm\models\PaymentRegister\PaymentRegisterSearch;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\Currencies;
use common\helpers\Html;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\documents\SelectedDocumentsCountLabel;
use common\widgets\documents\ShowDeletedDocumentsCheckbox;
use common\widgets\InfiniteGridView;
use common\widgets\InlineHelp\InlineHelp;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

abstract class localCachedModel
{
    private static $_id;
    private static $_model;

    public static function getModel(PaymentRegisterSearch $item)
    {
        if (static::$_id != $item->id) {
            static::$_id = $item->id;
            $paymentOrder = $item->getPaymentOrders()->one();
            $model = new PaymentOrderType();
            if ($paymentOrder) {
                $model->loadFromString($paymentOrder->body);
            }
            static::$_model = $model;
        }

        return static::$_model;
    }
}

// Вывести блок закладок
echo $this->render('_tabs');

$this->title = Yii::t('edm', 'Rouble payments');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = $this->title;

$userCanDeleteDocuments = Yii::$app->user->can(
    DocumentPermission::DELETE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
    ]
);
$userCanCreateDocument = Yii::$app->user->can(
    DocumentPermission::CREATE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
    ]
);

if ($userCanCreateDocument) {
    echo Html::a(
        Yii::t('app', 'Create'),
        Url::toRoute(['/edm/wizard/step2', 'type' => 'PaymentOrder', 'clearWizardCache' => 1]),
        [
            'class' => 'btn btn-success',
            'style' => 'margin-right: 10px'
        ]
    );

    // Вывести форму загрузки
    echo $this->render('_uploadPaymentRegisterForm');
}

if ($userCanDeleteDocuments) {
    $language = Yii::$app->language;
    $saveEntriesUrl = Url::toRoute(['select-payment-registers']);

    if (!empty($cachedEntries['entries'])) {
        $selectedDocumentsCount = count($cachedEntries['entries']);
    } else {
        $selectedDocumentsCount = 0;
    }

    $checkboxJS = <<<JS
    var documentsCount = Number($selectedDocumentsCount);
    function sendSaveEntriesRequest(entries) {
        $.post(
            '$saveEntriesUrl',
            {
                entries: entries
            },
            function(data) {
                var selectedIds = JSON.parse(data);
                $('#btnDelete').toggleClass('disabled', selectedIds.length == 0);
                documentsCount = selectedIds.length;
                SelectedDocumentsCountLabel.updateCount(documentsCount);
                console.log('data', data);
            }
        );
    };

    $('.select-on-check-all').click(function(e) {

        // костыль для ie
        $('[name="selection[]"]:visible:enabled').prop('checked', $(this).is(':checked'));

        var entries = $('[name="selection[]"]').map(
            function(index, element) {
                return {
                    id: element.value,
                    checked: $(element).is(':checked')
                };
            }
        ).get();
        sendSaveEntriesRequest(entries);
    });

    $('[name="selection[]"]').click(function(e) {
        var entries = [
            {
                id: this.value,
                checked: $(this).is(':checked')
            },
        ];
        sendSaveEntriesRequest(entries);
    });

    $('#btnDelete').click(function() {
        var confirmMessage = createDeleteDocumentsConfirmationMessage(documentsCount, '$language');

        return confirm(confirmMessage);
    });
JS;

    $this->registerJs($checkboxJS, View::POS_READY);

    $deletableDocumentsIds = array_reduce(
        $dataProvider->models,
        function ($carry, Document $document) {
            $isDeletable = in_array(
                $document->status,
                [
                    Document::STATUS_FORSIGNING, Document::STATUS_SIGNED,
                    Document::STATUS_ACCEPTED, Document::STATUS_SIGNING_REJECTED,
                ]
            );
            if ($isDeletable) {
                $carry[] = $document->id;
            }

            return $carry;
        },
        []
    );

    $disabledDelete = empty($cachedEntries['entries']) ? ' disabled' : '';
    if (!empty($cachedEntries['entries']) || count($deletableDocumentsIds) > 0) {
        echo Html::a(
            Yii::t('app', 'Delete selected'),
            Url::toRoute(['delete-payment-registers']),
            [
                'id' => 'btnDelete',
                'class' => 'btn btn-danger' . $disabledDelete,
            ]
        );
    }
    echo SelectedDocumentsCountLabel::widget(['initialCount' => $selectedDocumentsCount]);
}
?>
<div class="pull-right">
<?= Html::a('',
    '#',
    [
        'class' => 'btn-columns-settings glyphicon glyphicon-cog',
        'title' => Yii::t('app', 'Columns settings')
    ]
) ?>
<?= InlineHelp::widget(['widgetId' => 5, 'setClassList' => ['edm-journal-wiki-widget']]) ?>
</div>
<?= ShowDeletedDocumentsCheckbox::widget(['filterModel' => $model]) ?>
<?php
$columns['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'class' => 'text-right',
        'style' => 'width:125px;',
    ],
    'filterInputOptions' => [
        'style' => 'float:right;width:100%',
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'nowrap' => 'nowrap',
        'width' => '55px',
    ],
];

$columns['type'] = [
    'attribute' => 'type',
];

$columns['payer'] = [
    'attribute'     => 'payer',
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $model,
        'attribute' => 'payer',
        'data' => $payers,
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
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $model,
        'attribute' => 'accountNumber',
        'data' => $accounts, //DocumentHelper::getStatusLabelsAll(),
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

$columns['status'] = [
    'attribute'     => 'status',
    'format'        => 'html',
    'filter'		=> $model->getStatusLabels(),
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
    'value'         => function ($item) {
        return Html::tag('span', $item->getStatusLabel(), ['title' => 'Status: ' . $item->status]);
    },
];

$columns['sum'] = [
    'attribute' => 'sum',
    'headerOptions' => [
        'class' => 'text-right',
        'style' => 'width:1%;',
    ],
    'filterInputOptions' => [
        'style' => 'float:right;width:100%',
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'nowrap' => 'nowrap',
    ],
    'value' => function($model) {
        return Yii::$app->formatter->asDecimal($model->sum, 2);
    }
];

$columns['currency'] = [
    'attribute'          => 'currency',
    'filter'             => Currencies::getCodeLabels(),
    'headerOptions' => [
        'class' => 'text-right',
        'style' => 'width:1%;',
    ],
    'filterInputOptions' => [
        'style' => 'float:right;width:100%',
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
];

$columns['count'] = [
    'attribute' => 'count',
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
    'filterOptions' => [
        'style' => 'text-align: right;'
    ]
];

$columns['signaturesCount'] = [
    'attribute' => 'signaturesCount',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'filterOptions' => [
        'style' => 'text-align: right;'
    ]
];

$columns['dateCreate'] = [
    'attribute' => 'dateCreate',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $model,
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
            'style' => 'text-align: right',
        ],
    ]),
];

$columns['dateUpdate'] = [
    'attribute' => 'dateUpdate',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $model,
        'attribute' => 'dateUpdate',
        'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'orientation' => 'bottom'
        ],
        'options' => [
            'class' => 'form-control',
            'style' => 'text-align: right;',
        ],
    ]),
];

$columns['businessStatus'] = [
    'attribute' => 'businessStatus',
    'filter'    => PaymentRegisterDocumentExt::getBusinessStatusLabels(),
    'headerOptions' => [
        'style' => 'width: 220px'
    ],
    'contentOptions' => [
        'style' => 'width: 220px'
    ],
    'value' => function($item) {
        return PaymentRegisterDocumentExt::translateBusinessStatus($item);
    }
];

$columns['beneficiaryName'] = [
    'attribute' => 'beneficiaryName',
    'headerOptions' => [
        'style' => 'width: 220px'
    ],
    'contentOptions' => [
        'style' => 'width: 220px'
    ],
    'value' => function($item) {
        if ($item->count > 1) {
            return '';
        }
        return localCachedModel::getModel($item)->beneficiaryName;
    }
];

$columns['paymentPurpose'] = [
    'attribute' => 'paymentPurpose',

    'headerOptions' => [
        'style' => 'width: 220px'
    ],
    'contentOptions' => [
        'style' => 'width: 220px'
    ],
    'value' => function($item) {
        if ($item->count > 1) {
            return '';
        }

        return localCachedModel::getModel($item)->paymentPurpose;
    }
];

$columnsEnabled = [];

// Колонка с чекбоксом удаления
if ($userCanDeleteDocuments) {
    if (count($deletableDocumentsIds) > 0) {
        $columnsEnabled['deleted'] = [
            'class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function($model, $key, $index, $column) use ($cachedEntries, $deletableDocumentsIds) {
                $checked = false;
                $hidden = false;
                if (!in_array($model->id, $deletableDocumentsIds)) {
                    $hidden = true;
                } else if (
                    !empty($cachedEntries['entries'])
                    && array_key_exists($key, $cachedEntries['entries'])
                ) {
                    $checked = true;
                }

                return [
                    'style' => 'display: ' . ($hidden ? 'none': 'block'),
                    'disabled' => $hidden,
                    'value' => $key,
                    'checked' => $checked
                ];
            }
        ];
    } else {
        $columnsEnabled['deletedEmpty'] = [];
    }
}

// Получение колонок, которые могут быть отображены
$columnsSettings = UserColumnsSettings::getEnabledColumnsByType(
    $columns, $listType, Yii::$app->user->id,
    ['beneficiaryName', 'paymentPurpose']
);

foreach($columnsSettings as $setting => $value) {
    $columnsEnabled[$setting] = $value;
}

// Обязательные колонки, которые должны отображаться в любом случае
$columnsEnabled['view'] = [
    'attribute' => '',
    'format' => 'html',
    'filterInputOptions' => [
        'style' => 'width: 20px'
    ],
    'value' => function ($item, $params) {
        return Html::a('<span class="ic-eye"></span>',
            Url::toRoute(['view', 'id' => $item->id, 'redirectUrl' => 'index']), ['title' => 'Просмотр']);
    }
];
// Создать таблицу для вывода
$myGridWidget = InfiniteGridView::begin([
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $model,
    'highlightsByStatus' => true,
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options['ondblclick'] = "window.location='" . Url::toRoute(['view', 'id' => $model->id, 'redirectUrl' => 'index']) . "'";

        if ($model->hasAlertAttributes()) {
            $options['class'] = 'danger';
        } else if ($model->hasPaymentOrdersWithErrors()) {
            $options['class'] = 'payment-orders-with-errors';
        }

        $options['data']['status'] = $model->status;
        $options['data']['document-type'] = $model->type;

        return $options;
    },
    'columns' => $columnsEnabled,
    'options' => ['class' => 'grid-view documents-journal-grid'],
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

echo ColumnsSettingsWidget::widget([
    'listType' => $listType,
    'columns' => array_keys($columns),
    'model' => $model,
    'columnsDisabledByDefault' => ['beneficiaryName', 'paymentPurpose']
]);

$this->registerCss(<<<CSS
    .edm-payment-order-journal {
        margin-left: 5px;
    }
CSS);

$this->registerJS('stickyTableHelperInit()');

echo ToTopButtonWidget::widget();
