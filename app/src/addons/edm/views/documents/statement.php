<?php

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictCurrency;
use addons\edm\models\EdmDocumentTypeGroup;
use common\document\DocumentPermission;
use common\helpers\DateHelper;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\MaskedInput;

$this->title = Yii::t('app/menu', 'Statements');

$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = $this->title;

$userCanCreateStatementRequests = Yii::$app->user->can(
    DocumentPermission::CREATE,
    ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => EdmDocumentTypeGroup::STATEMENT]
) || Yii::$app->user->can('admin') || Yii::$app->user->can('additionalAdmin');

if ($userCanCreateStatementRequests) {
    // Вывести форму отправки запроса
    echo $this->render('_sendRequestForm');
}

// Вывести форму поиска
echo $this->render('_search', [
    'model' => $model,
    'filterStatus' => $filterStatus,
    'hideNullTurnovers' => $hideNullTurnovers
]);

$urlParams['from'] = 'statement';

$columns['id'] = [
    'attribute' => 'id',
    'filterInputOptions' => [
        'style' => 'width: 100%'
    ],
    'headerOptions' => [
        'style' => 'width: 1%',
    ],
    'contentOptions' => [
        'style' => 'width: 1%',
    ],
];

$columns['payer'] = [
    'attribute' => 'payer',
    'filter' => Select2::widget([
        'model' => $model,
        'attribute' => 'payer',
        'data' => EdmHelper::getOrgFilter(),
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
        'style' => 'min-width: 100px',
    ],
    'value' => function ($model) {
        return $model->accountPayerName ?: $model->orgName;
    },
];

$columns['accountNumber'] = [
    'attribute'     => 'accountNumber',
//    'value'         => 'documentExtEdmStatement.accountNumber',
    'filterInputOptions' => [
        'maxLength' => 20,
    ],
];

$columns['currency'] = [
    'attribute'     => 'currency',
    'filter' => ArrayHelper::map(DictCurrency::getValues(), 'name', 'name'),
//    'value'         => 'documentExtEdmStatement.currency',
    'filterInputOptions' => [
        'maxLength' => 3,
        'style' => 'padding: 7px 0;',
    ],
];

$columns['periodStart'] = [
    'attribute'      => 'periodStart',
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $model,
        'attribute' => 'periodStart',
        'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy',
            'todayHighlight' => true,
            'orientation' => 'bottom'
        ],
        'options' => [
            'class' => 'form-control',
            'style' => 'width: 100%; padding: 7px 2px;'
        ]
    ]),
    'headerOptions' => [
        'style' => 'width:1%;',
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ]
];

$columns['periodEnd'] = [
    'attribute'          => 'periodEnd',
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $model,
        'attribute' => 'periodEnd',
        'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy',
            'todayHighlight' => true,
            'orientation' => 'bottom'
        ],
        'options' => [
            'class' => 'form-control',
            'style' => 'width: 100%; padding: 7px 2px;'
        ]
    ]),
    'headerOptions' => [
        'style' => 'width:1%;',
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ]
];

$columns['openingBalance'] = [
    'attribute'     => 'openingBalance',
//    'value'         => 'documentExtEdmStatement.openingBalance',
    'format' => ['decimal', 2],
    'filter' => MaskedInput::widget([
        'attribute'     => 'openingBalance',
        'model' => $model,
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
    'filterInputOptions' => [
        'style' => 'width: 100%'
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ],
];

$columns['debitTurnover'] = [
    'attribute'     => 'debitTurnover',
//    'value'         => 'documentExtEdmStatement.debitTurnover',
    'format' => ['decimal', 2],
    'filter' => MaskedInput::widget([
        'attribute' => 'debitTurnover',
        'model' => $model,
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
    'filterInputOptions' => [
        'style' => 'width: 100%'
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ],
];

$columns['creditTurnover'] = [
    'attribute'     => 'creditTurnover',
    'format' => ['decimal', 2],
    'filter' => MaskedInput::widget([
        'attribute' => 'creditTurnover',
        'model' => $model,
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
    'filterInputOptions' => [
        'style' => 'width: 100%'
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ],
];

$columns['closingBalance'] = [
    'attribute'     => 'closingBalance',
//    'value'         => 'documentExtEdmStatement.closingBalance',
    'format' => ['decimal', 2],
    'filter' => MaskedInput::widget([
        'attribute' => 'closingBalance',
        'model' => $model,
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
    'filterInputOptions' => [
        'style' => 'width: 100%'
    ],
    'contentOptions' => [
        'class' => 'text-right'
    ],
];

$columns['dateCreate'] = [
    'attribute'          => 'dateCreate',
    'value' => function($model) {
        return DateHelper::formatDate($model->dateCreate, 'datetime');
    },
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $model,
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
            'style' => 'width:100%'
        ]
    ]),
    'contentOptions' => [
        'class' => 'text-right'
    ]
];

$columns['status'] = [
    'attribute' => 'status',
    'format' => 'html',
    'filter' => $model->getStatusLabels(),
    'value' => function ($item, $params) {
        return "<span title=\"Status: {$item->status}\">{$item->getStatusLabel()}</span>";
    },
];

$columns['bankBik'] = [
    'attribute' => 'bankBik',
    'filter' => Select2::widget([
        'model' => $model,
        'attribute' => 'bankBik',
        'data' => EdmHelper::getBankFilter(),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'prompt' => '',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft'
        ],
    ]),
    'value' => function ($model) {
        return $model->bankName;
    }
];

// Получение колонок, которые могут быть отображены
$columnsEnabled = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

$today = new DateTime;
$todayFormat = $today->format('Y-m-d');
$isAdmin = Yii::$app->user->can('admin');
// Создать таблицу для вывода
$myGridWidget = InfiniteGridView::begin([
    'id' => 'statements-grid',
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $model,
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => '.'
    ],
    'rowOptions' => function ($model) use ($urlParams, $todayFormat, $isAdmin) {
        $options['onclick'] = "window.location='"
            . Url::toRoute(array_merge(['view', 'id' => $model->id, 'mode' => 'source'], $urlParams)) . "'";

        if (in_array($model->status, array_merge($model->getErrorStatus(), ['']))) {
            $options['class'] = 'bg-alert-danger';
        } else if (in_array($model->status, $model->getProcessingStatus())) {
            $options['class'] = 'bg-alert-warning';
        }

        // Выделение непросмотренных
        // документов за сегодняшний день
        if (!$isAdmin) {
            $date = new DateTime($model->dateCreate);
            $dateFormat = $date->format('Y-m-d');

            if ($todayFormat == $dateFormat && !$model->viewed) {
                $options['style'] = 'font-weight: bold';
            }
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
    'model' => $model
]);

echo ToTopButtonWidget::widget();

$script = <<<JS
    // Изменение свойства отображения
    // документов с нулевыми оборотами
    $('#hide-null-turnover').on('click', function(e) {
        var value = $(this).is(':checked') ? 1 : 0;
        $.ajax({
            url: '/edm/documents/hide-zero-turnover-statements',
            type: 'POST',
            data: {
                status: value,
                redirect: '/edm/documents/statement'
            }
        });
    });

    // Маска для ввода значения счета
    $('#statementsearch-accountnumber').inputmask('99999999999999999999', {placeholder: ' '});

    // Маска для ввода значения дат
    $('#statementsearch-periodstart, #statementsearch-periodend, #statementsearch-datecreate').inputmask('99.99.9999', {placeholder: 'дд.мм.гггг'});

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
        window.location.href = '/edm/documents/statement';
    }

    stickyTableHelperInit();
JS;

$this->registerJs($script, View::POS_READY);

$this->registerCss(<<<CSS
    .select2-cyberft {
        width: 200px;
    }
CSS);

// Вывести модальное окно с формой поиска
echo $this->render('_searchModal', ['model' => $model]);
