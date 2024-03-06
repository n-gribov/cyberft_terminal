<?php

use addons\finzip\FinZipModule;
use addons\finzip\models\FinZipSearch;
use common\document\Document;
use common\document\DocumentPermission;
use common\models\User;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\documents\DeleteSelectedDocumentsButton;
use common\widgets\documents\SignDocumentsButton;
use common\widgets\InfiniteGridView;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this View */
/* @var $searchModel FinZipSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $controllerCacheKey string */

$this->title = Yii::t('app/menu', 'Documents for signing');

// Получить роль пользователя из активной сессии
$isAdmin = in_array(Yii::$app->user->identity->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN]);
$selected = Yii::t('app', 'Selected');

$userCanDeleteDocuments = \Yii::$app->user->can(DocumentPermission::DELETE, ['serviceId' => FinZipModule::SERVICE_ID]);
$userCanSignDocuments = \Yii::$app->user->can(DocumentPermission::SIGN, ['serviceId' => FinZipModule::SERVICE_ID]);

if ($userCanDeleteDocuments) {
    $deletableDocumentsIds = array_reduce(
        $dataProvider->models,
        function ($carry, Document $document) {
            if ($document->isDeletable()) {
                $carry[] = $document->id;
            }
            return $carry;
        },
        []
    );

    if (count($deletableDocumentsIds) > 0) {
        echo DeleteSelectedDocumentsButton::widget(['checkboxesSelector' => '.delete-checkbox, .select-on-check-all']);
    }
}

// Проверяем на наличие параметра адреса переадресации после подписания
$get = Yii::$app->request->get();

if (isset($get['redirectUrl'])) {
    $redirectUrl = $get['redirectUrl'];
} else {
    $redirectUrl = '';
}


$this->registerJs(
    'var documentCount = Number(' . count($cachedEntries['entries']) . ');',
    View::POS_READY
);

$script = <<<JS
    $("[name='selection[]']").change(function(e) {
        var entries = [
            {
                id: this.value,
                checked: $(this).is(':checked')
            },
        ];

        sendSaveEntriesRequest(entries);
    });

    function sendSaveEntriesRequest(entries)
    {
        $.post(
            'select-entries',
            {
                entries: entries
            },

            function(data) {
                var selectedIds = JSON.parse(data);
                documentCount = selectedIds.length;
                showCheckedLabel(documentCount);
            }
        );
    };

    $('.select-on-check-all').click(function(e) {
        // костыль для ie
        $('[name="selection[]"]:visible:enabled').prop('checked', $(this).is(':checked'));

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

    function showCheckedLabel(checkedQty)
    {
        // Если выбраны элементы, отображаем их количество, иначе обнуляем и скрываем
        if (checkedQty > 0) {
            $('.checked-signing-label').css({'display': 'inline-block'}).html('{$selected} ' + checkedQty);
            $('#sign-documents-button').removeClass('disabled');
        } else {
            $('.checked-signing-label').css({'display': 'none'}).html('');
            $('#sign-documents-button').addClass('disabled');
        }
    }

    showCheckedLabel(documentCount);

    $('body').on('click', '.disabled', function(e) {
        e.preventDefault();
    });

    var table = $('.grid-view table');
    table.stickyTableHeaders();

    var fakeInputs = $('.grid-view table .tableFloatingHeader input, .grid-view table .tableFloatingHeader select');

    fakeInputs.each(function() {
        $(this).attr('name', '');
        $(this).attr('id', '');
    });

    $('.tableFloatingHeader #w0-filters').attr('id', '');

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
        window.location.href = '/edm/documents/signing-index?tabMode=tabCFO';
    }
JS;

$this->registerJs($script, View::POS_READY);

$this->registerCss('
.select2-cyberft {
    width: 200px;
}

.tableFloatingHeaderOriginal {
    background-color: #fff;
}
');

if ($dataProvider->count > 0 && $userCanSignDocuments) {
    echo ' ' . SignDocumentsButton::widget([
        'buttonText' => Yii::t('app/message', 'Signing'),
        'prepareDocumentsIds' => new JsExpression("function (signCallback) { $.get('get-selected-entries-ids', function (ids) { signCallback(ids); }) }"),
        'successRedirectUrl' => Url::to(['/finzip/default']),
        'entriesSelectionCacheKey' => $controllerCacheKey
    ]);
    ?>
    <div class="checked-signing-label label label-success"></div>
    <?php
}

// Вывести форму поиска
echo $this->render('_search', [
    'model' => $searchModel,
    'filterStatus' => $filterStatus
]);

/**
 * Параметр для корректной работы кнопки Назад на странице просмотра документа
 */
$urlParams['from'] = 'forSigning';

$columns['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'width' => '50px'
    ],
];

$columns['senderParticipantName'] = [
    'attribute' => 'senderParticipantName',
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'theme' => Select2::THEME_BOOTSTRAP,
	'attribute' => 'senderParticipantName',
        'options' => [
            'prompt' => '',
	],
	'pluginOptions' => [
            'minimumInputLength' => 0,
            'ajax' => [
                'url'      => Url::to(['documents/list', 'type' => 'sender', 'page' => 'signing-index']),
                'dataType' => 'json',
                'delay'    => 250,
                'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
            ],
            'templateResult' => new JsExpression('function(item) { return item.name; }'),
            'templateSelection'  => new JsExpression('function(item) { return item.name; }'),
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft',
	],
	'pluginEvents'  => [
	    'select2:select' => 'function(e) { searchForField(e.params.data) }',
            'select2:unselect' => 'function(e) { }'
	],
    ]),
    'contentOptions' => [
        'style' => 'width: 160px'
    ],
    'filterOptions' => [
        'style' => 'width: 160px'
    ]
];

$columns['receiverParticipantName'] = [
    'attribute' => 'receiverParticipantName',
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'theme' => Select2::THEME_BOOTSTRAP,
	'attribute' => 'receiverParticipantName',
        'options' => [
            'prompt' => '',
	],
	'pluginOptions' => [
            'minimumInputLength' => 0,
            'ajax'               => [
                'url'      => Url::to(['documents/list', 'type' => 'receiver', 'page' => 'signing-index']),
                'dataType' => 'json',
                'delay'    => 250,
                'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
            ],
            'templateResult' => new JsExpression('function(item) { return item.name; }'),
            'templateSelection'  => new JsExpression('function(item) { return item.name; }'),
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft',
	],
	'pluginEvents'  => [
	    'select2:select' => 'function(e) { searchForField(e.params.data); }',
	],
    ]),
    'contentOptions' => [
        'style' => 'width: 160px'
    ],
    'filterOptions' => [
        'style' => 'width: 160px'
    ]
];

$columns['subject'] = [
    'attribute' => 'subject',
    'value' => function($model) use ($isAdmin) {
        if (!$isAdmin && $model->documentExtFinZip) {
            if ($model->isEncrypted) {
                Yii::$app->exchange->setCurrentTerminalId($model->originTerminalId);
                return $model->documentExtFinZip->getEncryptedSubject();
            } else {
                return $model->documentExtFinZip->subject;
            }
        }
    },
    'filter' => true,
];

$columns['fileCount'] = [
    'attribute' => 'fileCount',
    'value' => 'documentExtFinZip.attachmentsCount',
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'contentOptions' => [
        'class' => 'text-right',
    ],
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
        'model' => $searchModel,
        'attribute' => 'dateCreate',
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

$columnsEnabled = [];

// Колонка с чекбоксом удаления
$columnsEnabled['delete'] = [
    'class'           => 'yii\grid\CheckboxColumn',
    'visible'         => $userCanDeleteDocuments && $dataProvider->count > 0,
    'checkboxOptions' => function ($model, $key, $index, $column) use ($cachedEntries) {
        $checked = !empty($cachedEntries['entries']) && array_key_exists($key, $cachedEntries['entries']);
        return [
            'checked' => $checked,
            'class'   => 'delete-checkbox',
            'value'   => $key,
            'data-id' => (string) $model->id
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
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options['ondblclick'] = "window.location='"
                . Url::toRoute(['default/view', 'id' => $model->id, 'from' => 'forSigning'])
                . "'";

        if (in_array($model->status, array_merge(Document::getErrorStatus(),['']))) {
            $options['class'] = 'bg-alert-danger';
        } else if (in_array($model->status, Document::getProcessingStatus())) {
            $options['class'] = 'bg-alert-warning';
        }

        return $options;
    },
    'columns'=> $columnsEnabled
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();

echo ColumnsSettingsWidget::widget([
    'listType' => $listType,
    'columns' => array_keys($columns),
    'model' => $searchModel
]);

// Вывести модальное окно формы поиска
echo $this->render('@addons/edm/views/documents/_searchModal', ['model' => $searchModel]);
