<?php

use addons\edm\EdmModule;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\Pain001Fcy\Pain001FcyType;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\Currencies;
use common\helpers\DocumentHelper;
use common\helpers\Html;
use common\helpers\vtb\VTBHelper;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\DatePicker;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$userCanDeleteDocuments = Yii::$app->user->can(
    DocumentPermission::DELETE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => $documentTypeGroup
    ]
);

$userCanCreateDocuments = Yii::$app->user->can(
    DocumentPermission::CREATE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => $documentTypeGroup
    ]
);

$columnsDisabledByDefault = ['id', 'direction', 'dateCreate', 'signaturesCount', 'signaturesRequired'];

$selected = Yii::t('app', 'Selected');

$this->registerJs(
    'var documentsCount = Number(' . count($cachedEntries['entries']) . ');',
    View::POS_READY
);

if ($jsonFcoCU = Yii::$app->session->getFlash('fcoCU')) {
    $fcoCU = json_decode($jsonFcoCU);
    $tempId = $fcoCU->id;
    $tempType = $fcoCU->type;

    $this->registerJs("fcoModalView('$tempId', '$tempType');", View::POS_READY);
}

$currentTab = Yii::$app->request->get('tabMode');

$businessStatusesList = DocumentHelper::getBusinessStatusesList();

$checkboxesJs = <<<JS
    function sendSaveEntriesRequest(entries)
    {
        $.post(
            'select-entries?tabMode=tabFCO',
            {
                entries: entries
            },

            function(data) {
                var selectedIds = JSON.parse(data);
                $('#btnDelete').toggleClass('disabled', selectedIds.length == 0);
                documentsCount = selectedIds.length;
                showCheckedLabel(documentsCount);
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
JS;

$language = Yii::$app->language;
$differentAccountsMessage = Yii::t('edm', 'Document can only be created for single payer account');
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
    function showAlertBox(message) {
        $('.well .alert.alert-danger').remove();
        var alert = $('<div class="alert-danger alert-dismissible alert fade in "/>');
        alert.text(message);
        $('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>').prependTo(alert);
        alert.prependTo('.well-content');
    }
JS;

if ($userCanDeleteDocuments || $userCanCreateDocuments) {
    $this->registerJs($checkboxesJs, View::POS_READY);
}
$this->registerJs($buttonsJs, View::POS_READY);

// Вывести форму поиска
echo $this->render('_search', [
    'model' => $filterModel,
    'filterStatus' => $filterStatus,
    'hideNullTurnovers' => false
]);
?>

<style>
    #btnCreate {
        margin-right: 10px;
    }
    #btnCreate.disabled, #btnDelete.disabled {
        pointer-events: auto !important;
    }
</style>

<?php
    $disabledClass = empty($cachedEntries['entries']) ? ' disabled' : '';
    // Рисуем кнопку "Создать" только если $wizardType не пустой.
    // Если пустой - то визарда для данного типа документов нет.
    if ($userCanCreateDocuments && $wizardType) {
        // Вывести кнопку
        echo $this->render('_fcoCreateButton', compact('wizardType'));
    }

    if ($userCanDeleteDocuments && $dataProvider->count > 0) {
        echo Html::a(
            Yii::t('app', 'Delete selected'),
            Url::toRoute(['foreign-currency-operations-delete']),
            [
                'id' => 'btnDelete',
                'class' => 'btn btn-danger' . $disabledClass,
                'data-content' => Yii::t('edm', 'Select documents'),
                'data-placement' => 'bottom',
                'rel' => 'popover'
            ]
        );

    }
?>
<div style="margin-left:1em" class="checked-signing-label label label-danger"></div>
<div class="pull-right" style="margin-right:1em">
<?php

$form = ActiveForm::begin([
    'id' => $filterModel->formName(),
    'method' => 'get',
    //'action' => Url::toRoute(['index'])
]);
echo $form->field($filterModel, 'showDeleted')->checkbox([
   'onChange' => '$("#' . $filterModel->formName() . '").submit();'
]);

ActiveForm::end();
?>
</div>

<?php
$columns['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'style' => 'width: 5%',
    ],
    'filterInputOptions' => [
        'style' => 'float:right',
    ],
];

$columns['direction'] = [
    'attribute' => 'direction',
    'filter' =>  $filterModel->getDirectionLabels(),
    'format' => 'html',
    'enableSorting' => false,
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '90px',
        'data-none-selected-text' => ''
    ],
    'value' => function ($item, $params) {
        return Html::tag('span', $item->getDirectionLabel(), ['title' => $item->type]);
    },
];

$columns['status'] = [
    'attribute'     => 'status',
    'format'        => 'html',
    'filter' => Document::getStatusLabels(),
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '130px',
        'data-none-selected-text' => ''
    ],
    'value' => function ($item, $params) {
        return Html::tag('span', $item->getStatusLabel(), ['title' => 'Status: ' . $item->status]);
    },
];

$columns['signaturesRequired'] = [
    'attribute' => 'signaturesRequired',
    'headerOptions' => [
        'class' => 'text-right'
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ],
];

$columns['signaturesCount'] = [
    'attribute' => 'signaturesCount',
    'headerOptions' => [
        'class' => 'text-right'
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ],
];

$columns['date'] = [
    'attribute' => 'date',
    'value'  => 'date',
    'format' => ['date', 'dd.MM.Y'],
    'filter' => DatePicker::widget([
        'model' => $filterModel,
        'attribute' => 'date',
        'type' => DatePicker::TYPE_INPUT,
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
        'style' => 'width: 5%',
    ],
    'filterInputOptions' => [
        'style' => 'float:right;',
    ],
    'value' => function($model) {
        if ($model->type === VTBRegisterCurType::TYPE || $model->type === Pain001FcyType::TYPE) {
            return Yii::t('edm', 'Register');
        } else {
            return $model->numberDocument;
        }
    }
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
        // Получение наименования плательщика по счету дебета
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
        'data-width' => '165px',
        'data-none-selected-text' => ''
    ],
    'contentOptions' => [
        'style' => 'width: 165px'
    ],
    'headerOptions' => [
        'style' => 'width: 165px'
    ]
];

$columns['bankName'] = [
    'attribute'     => 'bankName',
    'filter' => $banksFilter,
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '165px',
        'data-none-selected-text' => ''
    ],
    'contentOptions' => [
        'style' => 'width: 165px'
    ],
    'headerOptions' => [
        'style' => 'width: 165px'
    ]
];

$columns['currency'] = [
    'attribute' => 'currency',
    'value' => function ($model) {
        return $model->type == VTBRegisterCurType::TYPE ? null : $model->currency;
    },
    'filter' => Currencies::getCodeLabels(),
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'filterInputOptions' => [
        'data-width' => '60px',
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
];

$columns['documentSum'] = [
    'attribute' => 'documentSum',
    'value' => function ($model) {
        if ($model->type == VTBRegisterCurType::TYPE) {
            return null;
        }
        if ($model->currencySum) {
            return $model->currencySum;
        } else if ($model->sum) {
            return $model->sum;
        }
    },
    'format' => ['decimal', 2],
    'filter' => MaskedInput::widget([
        'attribute'     => 'documentSum',
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
    'contentOptions' => [
        'class' => 'text-right'
    ],
];

$columns['dateCreate'] = [
    'attribute' => 'dateCreate',
    'filter' => DatePicker::widget([
        'model' => $filterModel,
        'attribute' => 'dateCreate',
        'type' => DatePicker::TYPE_INPUT,
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

$columns['businessStatus'] = [
    'attribute' => 'businessStatus',
    'filter' => $businessStatusesList,
    'value' => function($model) {
        return PaymentRegisterDocumentExt::translateBusinessStatus($model) ?: '';
    },
];

$columnsEnabled = [];
// Колонка с чекбоксом удаления
if ($userCanDeleteDocuments) {
    $columnsEnabled['deleted'] = [
        'class' => 'yii\grid\CheckboxColumn',
        'visible' => $userCanDeleteDocuments && $dataProvider->count > 0,
        'checkboxOptions' => function($model, $key, $index, $column) use ($cachedEntries) {
            $checked = false;
            $hidden = false;
            if (!in_array($model->status, [
                    Document::STATUS_FORSIGNING, Document::STATUS_SIGNING_REJECTED,
                ])
            ) {
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
}

// Получение колонок, которые могут быть отображены
$columnsEnabled = array_merge(
    $columnsEnabled,
    UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id, $columnsDisabledByDefault)
);

// Обязательные колонки, которые должны отображаться в любом случае
$columnsEnabled['view'] = [
    'class'    => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'buttons'  => [
        'view' => function ($url, $model, $key) {
            $documentType = $model->type;
            if (!VTBHelper::isVTBDocument($model)) {
                $documentType = @$model->extModel->documentType ?: $documentType;
            }
            if (is_null($model) || is_null($model->extModel)) {
                return 'Invalid document!';
            } else {
               return Html::a(
                    '<span class="glyphicon glyphicon-eye-open">',
                    '#',
                    [
                        'title' => Yii::t('app', 'View'),
                        'class' => 'view-modal-btn',
                        'data' => [
                            'id' => $model->id,
                            'type' => $documentType
                        ]
                    ]
                );
            }
        }
    ],
];

if ($userCanCreateDocuments) {
    $columnsEnabled['create'] = [
        'class'    => 'yii\grid\ActionColumn',
        'template' => '{create}',
        'buttons'  => [
            'create' => function ($url, $model, $key) {
                // Показываем кнопку создания только когда строка не указывает на ВТБ-шный документ
                if (strpos($model->type, 'VTB') === false) {
                    if ($model->direction == Document::DIRECTION_OUT) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-plus">',
                            '#',
                            [
                                'title' => Yii::t('app', 'Create'),
                                'class' => 'create-modal-btn',
                                'data' => [
                                    'id' => $model->id,
                                    'type' => $model->type
                                ]
                            ]
                        );
                    }
                }
            }
        ]
    ];
}
// Создать таблицу для вывода
$myGridWidget = InfiniteGridView::begin([
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'highlightsByStatus' => true,
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => ','
    ],
    'rowOptions' => function ($model, $key) {
        $type = $model->type;

        $extModel = $model->extModel;

        if (is_null($extModel)) {
            $options['class'] = 'danger';
        } else {
            if (!VTBHelper::isVTBDocument($model)) {
                $documentType = $model->extModel->documentType;
                if ($documentType) {
                    $type = $documentType;
                }
            }
        }

        if ($model->type == VTBRegisterCurType::TYPE) {
            if ($extModel->businessStatus == 'RJCT') {
                $options['class'] = 'danger';
            } else if (in_array($extModel->businessStatus,
                ['PRJT', 'PART', 'PACP', 'PPNG'])) {
                $options['class'] = 'payment-orders-with-errors';
            }
        }

        $options['data']['status'] = $model->status;
        $options['data']['document-type'] = $type;

        $options['ondblclick'] = 'fcoModalView(' . $key . ', "' . $type . '")';

        return $options;
    },
    'columns' => $columnsEnabled,
    'options' => ['class' => 'grid-view documents-journal-grid'],
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

// Формат поля выбора дат
$this->registerJS(<<<JS
    // Маска для ввода значения счета
    $('#fco-debitaccount').inputmask('99999999999999999999', { placeholder: '' });
    $('#fco-numberdocument').inputmask('99999999999999999999', { placeholder: '' });
    $('#fco-date').inputmask('99.99.9999', { placeholder: 'дд.мм.гггг'});
    $('#fco-datecreate').inputmask('99.99.9999', { placeholder: 'дд.мм.гггг' });

    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };

    if (getUrlParameter('page')) {
        window.location.href = '/edm/documents/foreign-currency-operation-journal';
    }

    stickyTableHelperInit();
JS
);

echo ColumnsSettingsWidget::widget([
    'listType' => $listType,
    'columns' => array_keys($columns),
    'model' => $filterModel,
    'columnsDisabledByDefault' => $columnsDisabledByDefault,
]);

// Вывести модальное окно с формой создания
echo $this->render('_fcoCreateModal');
// Вывести модальное окно с формой редактирования
echo $this->render('_fcoUpdateModal');
// Вывести модальное окно с просмотром
echo $this->render('_fcoViewModal');
// Вывести модальное окно отказа подписания
echo $this->render('_fcoRejectSigningModal');
// Вывести модальное олкно с формой поиска
echo $this->render('_searchModal', ['model' => $filterModel]);
?>
<script>
    function fcoModalView(id, type) {
        $('#fcoViewModal .modal-body').html('');
        var isModalView = true;
        var viewUrl = null;
        if (type === 'ForeignCurrencyPurchaseRequest' ||
            type === 'ForeignCurrencySellRequest') {
            viewUrl = '/edm/documents/foreign-currency-operation-view?id=' + id + '&ajax=1';
        } else if (type === 'ForeignCurrencySellTransitAccount') {
            viewUrl = '/edm/documents/foreign-currency-sell-transit-view?id=' + id + '&ajax=1';
        } else if (type.indexOf('VTB') === 0) {
            isModalView = false;
            viewUrl = '/edm/vtb-documents/view?id=' + id;
        } else if (type === 'ForeignCurrencyConversion') {
            viewUrl = '/edm/documents/foreign-currency-conversion-view?id=' + id + '&ajax=1';
        }

        if (viewUrl === null) {
            return;
        }

        if (isModalView) {
            $.ajax({
                url: viewUrl,
                type: 'get',
                success: function (answer) {
                    // Добавляем html содержимое на страницу формы
                    $('#fcoViewModal .modal-body').html(answer);
                }
            });
            $('#fcoViewModal').modal('show');
        } else {
            location.href = viewUrl;
        }
    }
</script>
<?php
// createFCO
$this->registerJS(<<<JS
    $('body').on('click', '.create-modal-btn', function(e) {
        e.preventDefault();

        $('#fcoCreateModalTitle').html('Создание валютной операции');
        $('#fcoCreateModalButtons').hide();
        $('#fcoCreateModal .modal-body').html('');
        $('#fcoUpdateModal .modal-body').html('');

        var id = $(this).data('id');
        var type = $(this).data('type');

        $.get('/edm/foreign-currency-operation-wizard/create-from-existing-document', {id: id, type: type}).done(function(result) {
            $('#fcoCreateModal .modal-body').html(result);
            $('#fcoCreateModal').modal('show');
        });

    });

    $('body').on(
        'click', '.view-modal-btn',
        function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var type = $(this).data('type');
            fcoModalView(id, type);
        }
    );

    function showCheckedLabel(checkedQty) {
        // Если выбраны элементы, отображаем их количество, иначе обнуляем и скрываем
        if (checkedQty > 0) {
            $('.checked-signing-label').css({'display': 'inline-block'}).html('{$selected} ' + checkedQty);
            $('#btn_sign').removeClass('disabled');
        } else {
            $(".checked-signing-label").css({'display': 'none'}).html('');
            $('#btn_sign').addClass('disabled');
        }
    }

    showCheckedLabel(documentsCount);
    checkForSelectableDocument();

    if ({$view}) {
        fcoModalView({$view}, '{$type}');
    }
JS
);

$this->registerCss(<<<CSS
.select2-cyberft {
    width: 200px;
}
CSS
);

// Создание документа из шаблона
$templateId = Yii::$app->request->get('template');

if ($templateId) {
    $this->registerJS(<<<JS

    var type = 'ForeignCurrencyPayment';

    $.ajax({
        url: '/edm/foreign-currency-operation-wizard/create?type=' + type + '&templateId=' + $templateId,
        type: 'get',
        success: function(answer) {
            // Добавляем html содержимое на страницу формы
            $('#fcoCreateModalTitle').html('Создание валютной операции');
            $('#fcoCreateModal .modal-body').html(answer);
            $('#fcoCreateModalButtons').show();
            $('#fcoCreateModal').modal('show');
        }
    });

JS
    );
}

// Создание документа с проверкой кэша
$withCache = Yii::$app->request->get('withCache');
$cacheType = Yii::$app->request->get('cacheType');

if ($withCache && $cacheType) {
    $this->registerJS(<<<JS

    var type = '$cacheType';

    $.ajax({
        url: '/edm/foreign-currency-operation-wizard/create?type=' + type,
        type: 'get',
        success: function(answer) {
            // Добавляем html содержимое на страницу формы
            $('#fcoCreateModalTitle').html('Создание валютной операции');
            $('#fcoCreateModal .modal-body').html(answer);
            $('#fcoCreateModalButtons').show();
            $('#fcoCreateModal').modal('show');
        }
    });

JS
    );
}

echo ToTopButtonWidget::widget();
?>
<style>
    .grid-view thead .dropdown-menu > li > a {
        padding: 3px 5px;
    }
    .select-on-check-all, #btnDelete {
        display: none;
    }
</style>
