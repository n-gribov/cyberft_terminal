<?php

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrderSearch;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\Currencies;
use common\helpers\DocumentHelper;
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

$this->title = Yii::t('edm', 'Rouble payments');


$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app/menu', 'Banking'),
    'url' => ['/edm']
];

$this->params['breadcrumbs'][] = $this->title;

$userCan = function ($permissionCode) {
    return Yii::$app->user->can(
        $permissionCode,
        [
            'serviceId' => EdmModule::SERVICE_ID,
            'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
        ]
    );
};

$userCanDeleteDocuments = $userCan(DocumentPermission::DELETE);
$userCanCreatePaymentOrders = $userCan(DocumentPermission::CREATE);
$userCanSignDocuments = $userCan(DocumentPermission::SIGN);
$userCanCreatePaymentRegisters = $userCanCreatePaymentOrders || $userCanSignDocuments;

$selectedDocumentsCount = count($paymentOrders['entries']);

$selectableDocumentsIds = [];
if ($userCanCreatePaymentRegisters || $userCanDeleteDocuments) {
    $selectableDocumentsIds = array_reduce(
        $dataProvider->models,
        function ($carry, PaymentRegisterPaymentOrderSearch $document) {
            if (is_null($document->registerId) && $document->status !== Document::STATUS_DELETED) {
                $carry[] = $document->id;
            }
            return $carry;
        },
        []
    );
}

$this->registerJs(
    "var documentsCount = Number($selectedDocumentsCount)",
    View::POS_READY
);

$checkboxesJs = <<<JS
    function sendSaveEntriesRequest(entries) {
        $.post(
            'select-payment-orders',
            {
                entries: entries
            },
            function(data) {
                var selectedIds = JSON.parse(data);
                $('#btnCreate, #btnDelete').toggleClass('disabled', selectedIds.length == 0);
                documentsCount = selectedIds.length;
                SelectedDocumentsCountLabel.updateCount(documentsCount);
            }
        );
    };

    $('.select-on-check-all').click(function(e) {
        // костыль для ie
        var checkbox = $(e.target);
        if (checkbox.hasClass('select-on-check-all')) {
            $("[name='selection[]']").filter(':visible:enabled').prop('checked', checkbox.is(':checked'));
        }
        var entries = $("[name='selection[]']").map(
            function(index, element) {
                return {
                    id: element.value,
                    checked: $(element).is(':checked')
                };
            }
        ).get();
        sendSaveEntriesRequest(entries);
    });

    $('body').on('click', "[name='selection[]']", function(e) {
        var entries = [
            {
                id: this.value,
                checked: $(this).is(':checked')
            },
        ];
        sendSaveEntriesRequest(entries);
    });
JS;

$language = Yii::$app->language;
$differentAccountsMessage = Yii::t('edm', 'Payment register can only be created for single payer account');
$buttonsJs = <<<JS
    $('#btnCreate, #btnDelete').hover(
        function() {
            if ($(this).hasClass('disabled')) {
                $(this).popover('show');
            }
        },
        function() {
            $(this).popover('hide');
        }
    );

    $('body').on('click', '#btnCreate.disabled, #btnDelete.disabled', function(e) {
        e.preventDefault();
    });

    $('body').on('click', '#btnDelete:not(.disabled)', function(e) {
        var confirmMessage = createDeleteDocumentsConfirmationMessage(documentsCount, '$language');

        return confirm(confirmMessage);
    });

    $('body').on('click', '#btnCreate:not(.disabled)', function(e) {
        return validatePayerAccount();
    });

    function validatePayerAccount()
    {
        var firstAccount = null;
        var isValid = true;
        $("[name='selection[]']:checked").map(
            function(index, element) {
                var tr = $(element).closest('tr');
                var account = tr.data('payer-account');
                if (firstAccount == null) {
                    firstAccount = account;
                    return;
                }
                var accountIsDifferent = account != firstAccount;
                tr.toggleClass('warning', accountIsDifferent);
                if (accountIsDifferent) {
                    isValid = false;
                }
            }
        );
        if (!isValid) {
            showAlertBox('$differentAccountsMessage');
        }
        return isValid;
    }

    $('.btn-find-modal').on('click', function(e) {
        e.preventDefault();
        $('#searchModal').modal('show');
    });

    function showAlertBox(message)
    {
        $('.well .alert.alert-danger').remove();
        var alert = $('<div class="alert-danger alert-dismissible alert fade in"/>');
        alert.text(message);
        $('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>').prependTo(alert);
        alert.prependTo('.well-content');
    }
JS;

if ($userCanDeleteDocuments || $userCanCreatePaymentRegisters) {
    $this->registerJs($checkboxesJs, View::POS_READY);
}
$this->registerJs($buttonsJs, View::POS_READY);
?>

<style>
    #btnCreate.disabled, #btnDelete.disabled {
        pointer-events: auto !important;
    }
</style>

<?php
    // Вывести блок закладок
    echo $this->render('_tabs');
    $disabledClass = empty($paymentOrders['entries']) ? ' disabled' : '';
    if ($userCanCreatePaymentOrders) {
        echo Html::a(
            Yii::t('app', 'Create'),
            Url::toRoute(['/edm/wizard/step2', 'type' => 'PaymentOrder', 'clearWizardCache' => 1]),
            [
                'class' => 'btn btn-success',
                'style' => 'margin-right: 10px'
            ]
        );

        // Вывести форму загрузки реестра
        echo $this->render('_uploadPaymentRegisterForm');
    }

    if ($userCanCreatePaymentRegisters) {
        if ($selectedDocumentsCount > 0 || count($selectableDocumentsIds) > 0) {
            echo Html::a(
                Yii::t('doc', 'Sign and send'),
                Url::toRoute(['create', 'redirectUrl' => '/edm/payment-register/payment-order']),
                [
                    'id'             => 'btnCreate',
                    'class'          => 'btn btn-success' . $disabledClass,
                    'style'          => 'margin-right: 10px',
                    'data-content'   => Yii::t('edm', 'Select payment order documents'),
                    'data-placement' => 'bottom',
                    'rel'            => 'popover'
                ]
            );
        }
    }

    if ($userCanDeleteDocuments && ($selectedDocumentsCount > 0 || count($selectableDocumentsIds) > 0)) {
        echo Html::a(
            Yii::t('app', 'Delete selected'),
            Url::toRoute(['delete-payment-orders']),
            [
                'id' => 'btnDelete',
                'class' => 'btn btn-danger' . $disabledClass,
                'data-content' => Yii::t('edm', 'Select payment order documents'),
                'data-placement' => 'bottom',
                'rel' => 'popover'
            ]
        );
    }
    echo SelectedDocumentsCountLabel::widget(['initialCount' => $selectedDocumentsCount]);
?>
<div class="pull-right">
<?php
    echo Html::a('',
        '#',
        [
            'class' => 'btn-find-modal glyphicon glyphicon-search',
            'title' => Yii::t('edm', 'Find')
        ]
    );
    echo Html::a('',
        '#',
        [
            'class' => 'btn-columns-settings glyphicon glyphicon-cog',
            'title' => Yii::t('app', 'Columns settings')
        ]
    );
    echo InlineHelp::widget(['widgetId' => 4, 'setClassList' => ['edm-journal-wiki-widget']]);
    ?>
</div>

<?= ShowDeletedDocumentsCheckbox::widget(['filterModel' => $model]) ?>

<?php
$columns['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
];

$columns['number'] = [
    'attribute'  => 'number',
    'headerOptions' => [
        'class' => 'text-right',
        'style' => 'width:1%;',
    ],
    'filterInputOptions' => [
        'style' => 'float:right;width:100%',
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'nowrap'=> 'nowrap',
    ],
];

$columns['date'] = [
    'attribute' => 'date',
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $model,
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
            'style' => 'width:100px'
        ]
    ]),
    'contentOptions' => [
        'class' => 'text-right'
    ]
];

$columns['payer'] = [
    'attribute' => 'payer',
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
];

$columns['bankBik'] = [
    'attribute' => 'bankBik',
    'filter' => EdmHelper::getBankFilter(),
    'value' => function ($model) {
        return $model->bankName;
    },
];

$columns['accountNumber'] = [
    'attribute' => 'payerAccount',
    //'filter' => $accounts,
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

$columns['beneficiaryName'] = [
    'attribute' => 'beneficiaryName',
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
        'nowrap' => 'nowrap'
    ],
    'value' => function($model) {
        return Yii::$app->formatter->asDecimal($model->sum, 2);
    }
];

$columns['currency'] = [
    'attribute' => 'currency',
    'filter' => Currencies::getCodeLabels(),
    'headerOptions' => [
        'class' => 'text-right',
        'style' => 'width:1%;',
    ],
    'filterInputOptions' => [
        'maxLength' => 3,
        //'style' => 'float:right;width:100%',
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
];

$columns['paymentPurpose'] = [
    'attribute'          => 'paymentPurpose',
    'filterInputOptions' => [
        'style'     => 'width: 100%'
    ],
];

$columns['businessStatus'] = [
    'attribute' => 'businessStatus',
    'filter'    => DocumentHelper::getBusinessStatusesList(),
    'headerOptions' => [
        'class' => 'text-right',
        'style' => 'width: 220px'
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'style' => 'width: 220px'
    ],
    'value' => function($item) {
        return $item->getBusinessStatusTranslation();
    }
];

$columnsEnabled = [];

// Колонка с чекбоксом удаления
if ($userCanDeleteDocuments || $userCanCreatePaymentRegisters) {
    if (count($selectableDocumentsIds) > 0) {
        $columnsEnabled['deleted'] = [
            'class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function($model, $key, $index, $column) use ($paymentOrders, $selectableDocumentsIds) {
                $checked = false;
                $hidden = false;
                if (!in_array($model->id, $selectableDocumentsIds)) {
                    $hidden = true;
                } else if (array_key_exists($key, $paymentOrders['entries'])) {
                    $checked = true;
                }

                return [
                    'style'   => 'display: ' . ($hidden ? 'none': 'block'),
                    'disabled' => $hidden,
                    'value'   => $key,
                    'checked' => $checked
                ];
            }
        ];
    } else {
        $columnsEnabled['deletedEmpty'] = [];
    }
}

// Получение колонок, которые могут быть отображены
$columnsSettings = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

foreach($columnsSettings as $setting => $value) {
    $columnsEnabled[$setting] = $value;
}

$columnsEnabled['actions'] = [
    'class'    => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'buttons'  => [
        'view' => function ($url, $model, $key) {
            if ($model->status == Document::STATUS_DELETED) {
                return '<span style="color:red" class="glyphicon glyphicon-remove"></span>';
            } else if (!is_null($model->registerId)) {
                return Html::a(
                    '<span class="glyphicon glyphicon-check"></span>', [
                        'view',
                        'id' => $model->registerId
                    ],
                    ['title' => Yii::t('edm', 'View payment register #{id}' , ['id' => $model->registerId])]
                );
            } else {
                return Html::tag('span', '', ['class' => 'glyphicon glyphicon-unchecked']);
            }
        }
    ],
];

$columnsEnabled['actions2'] = [
    'class'    => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'buttons'  => [
        'view' => function ($url, $model, $key) {
            return Html::a(
                '<span class="ic-eye"></span>', [
                'payment-order-view',
                'id' => $model->id
            ]);
        }
    ],
];

if ($userCanCreatePaymentOrders) {
    $columnsEnabled['actions3'] = [
        'class'    => 'yii\grid\ActionColumn',
        'template' => '{create}',
        'buttons'  => [
            'create' => function ($url, $model, $key) {
                $paymentRegister = $model->paymentRegister;

                if ($paymentRegister && $paymentRegister->direction != Document::DIRECTION_OUT) {
                    return '';
                }

                return Html::a('<span class="glyphicon glyphicon-plus"></span>',
                    Url::toRoute(['/edm/wizard/step2', 'type' => 'PaymentOrder', 'fromId' => $model->id]),
                    ['title' => Yii::t('app', 'Create')]
                );
            }
        ],
    ];
}
// Создать таблицу для вывода
$myGridWidget = InfiniteGridView::begin([
    'id' => 'paymentOrderLog',
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $model,
    'columns' => $columnsEnabled,
    'rowConfig' => [
        'attrColor' => 'businessStatus',
        'map'   => [
            'RJCT' => 'red',
        ]
    ],
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'rowOptions' => function ($model, $key, $index, $grid) {
        if ($model->status == Document::STATUS_DELETED) {
            $options['class'] = 'bg-alert-danger';
        }
        $options['ondblclick'] = "window.location='". Url::toRoute(['payment-order-view', 'id' => $model->id]) ."'";
        $options['data-payer-account'] = $model->payerAccount;

        return $options;
    },
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

echo ColumnsSettingsWidget::widget([
    'listType' => $listType,
    'columns' => array_keys($columns),
    'model' => $model
]);

$this->registerJS('stickyTableHelperInit();');

echo ToTopButtonWidget::widget();

// Вывести модальное окно формы поиска
echo $this->render('_searchModal', ['model' => $model]);
