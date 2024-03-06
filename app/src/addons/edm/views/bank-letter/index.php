<?php

use addons\edm\EdmModule;
use addons\edm\models\BankLetter\BankLetterForm;
use addons\edm\models\BankLetter\BankLetterSearch;
use addons\edm\models\EdmDocumentTypeGroup;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\Html;
use common\models\User;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\documents\DeleteSelectedDocumentsButton;
use common\widgets\documents\SelectedDocumentsCache;
use common\widgets\documents\SelectedDocumentsCountLabel;
use common\widgets\InfiniteGridView;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;

/** @var View $this */
/** @var BankLetterForm $newLetterForm */
/** @var BankLetterSearch $filterModel */
/** @var ActiveDataProvider $dataProvider */
/** @var array $selectedDocumentsIds */

$this->title = Yii::t('edm', 'Bank correspondence');
$listType = 'edmBankLetters';
$entriesSelectionCacheKey = 'bankLetters';

$userCanCreateDocument = Yii::$app->user->can(
    DocumentPermission::CREATE,
    ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => EdmDocumentTypeGroup::BANK_LETTER]
);

$userCanDeleteDocuments = Yii::$app->user->can(
    DocumentPermission::DELETE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => EdmDocumentTypeGroup::BANK_LETTER,
    ]
);

$deletableDocumentsIds = [];
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
}

$columns = [];
if ($userCanDeleteDocuments && count($deletableDocumentsIds) > 0) {
    // Колонка с чекбоксом для удаления
    $columns['delete'] = [
        'class'   => 'yii\grid\CheckboxColumn',
        'checkboxOptions' => function($model, $key, $index, $column) use ($selectedDocumentsIds, $deletableDocumentsIds) {
            $hidden = !in_array($model->id, $deletableDocumentsIds);
            $checked = !$hidden && in_array($model->id, $selectedDocumentsIds);
            return [
                'style'    => "display: " . ($hidden ? 'none': 'block'),
                'disabled' => $hidden,
                'value'    => $key,
                'class'    => 'delete-checkbox',
                'data-id'  => (string) $model->id,
                'checked'  => $checked,
            ];
        }
    ];
} else {
    $columns['deletedEmpty'] = [];
}

$columns['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'class' => 'text-right',
        'style' => 'width: 75px'
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'style' => 'width: 75px'
    ],
    'filterInputOptions' => [
        'style' => 'float:right;',
    ],
];

$columns['direction'] = [
    'attribute' => 'direction',
    'filter' => $filterModel->getDirectionLabels(),
    'format' => 'html',
    'enableSorting' => false,
    'filterInputOptions' => [
        'class' => 'form-control selectpicker',
        'data-width' => '100px',
        'data-none-selected-text' => ''
    ],
    'value' => function (BankLetterSearch $item, $params) {
        return "<span title=\"{$item->type}\">{$item->getDirectionLabel()}</span>";
    },
];

$columns['subject'] = [
    'attribute' => 'subject',
];

$columns['status'] = [
    'attribute' => 'status',
    'format' => 'html',
    'filter' => Select2::widget([
        'model' => $filterModel,
        'attribute' => 'status',
        'data' => Document::getStatusLabels(),
        'options' => [
            'prompt' => ''
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft'
        ],
    ]),
    'value' => function (BankLetterSearch $item, $params) {
        return "<span title=\"Status: {$item->status}\">{$item->getStatusLabel()}</span>";
    }
];

$columns['signaturesRequired'] = [
    'attribute' => 'signaturesRequired',
    'headerOptions' => [
        'style' => 'width: 75px'
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'style' => 'width: 75px'
    ],
];

$columns['signaturesCount'] = [
    'attribute' => 'signaturesCount',
    'headerOptions' => [
        'style' => 'width: 75px'
    ],
    'contentOptions' => [
        'class' => 'text-right',
        'style' => 'width: 75px'
    ],
];

$columns['sender'] = [
    'attribute' => 'sender',
    'value' => 'senderParticipant.name',
];

$columns['receiver'] = [
    'attribute' => 'receiver',
    'value' => 'receiverParticipant.name',
];

$columns['dateCreate'] = [
    'attribute' => 'dateCreate',
    'headerOptions' => [
        'style' => 'width:1%;',
    ],
    'contentOptions' => [
        'class' => 'text-center'
    ],
    'filter' => kartik\widgets\DatePicker::widget(
        [
            'model' => $filterModel,
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
                'style' => 'width: 130px; text-align: right;',
            ],
        ]
    ),
];

$columns['businessStatus'] = [
    'attribute' => 'businessStatus',
    'value' => function (BankLetterSearch $item, $params) {
        return ($item->getBusinessStatusLabel() != null) ?
                $item->getBusinessStatusLabel() : '';
    },
    'filter' => Select2::widget([
        'model' => $filterModel,
        'attribute' => 'businessStatus',
        'data' => BankLetterSearch::getBusinessStatusLabels(),
        'options' => [
            'prompt' => ''
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft',
            'width' => '130px',
        ],
    ]),
];

$columnsEnabled = [];
$columnsSettings = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);
foreach ($columnsSettings as $setting => $value) {
    $columnsEnabled[$setting] = $value;
}

if ($userCanCreateDocument) {
    $columnsEnabled['edit'] = [
        'class'    => 'yii\grid\ActionColumn',
        'template' => '{edit}',
        'buttons'  => [
            'edit' => function ($url, $model, $key) {
                $isEditable = in_array($model->status, [Document::STATUS_CREATING, Document::STATUS_FORSIGNING]);
                return $isEditable
                    ? Html::a(
                        '<span class="glyphicon glyphicon-edit"></span>',
                        '#',
                        [
                            'class' => 'edit-letter-button',
                            'data' => [
                                'id' => $model->id,
                            ],
                        ]
                    )
                    : null;
            }
        ],
    ];
}

$columnsEnabled['view'] = [
    'class' => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'buttons' => [
        'view' => function ($url, $model, $key) {
            return Html::a(
                '<span class="glyphicon glyphicon-eye-open"></span>',
                '#',
                ['class' => 'view-letter-button']
            );
        }
    ],
];

if ($userCanCreateDocument) {
    $columnsEnabled['create'] = [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{create}',
        'buttons' => [
            'create' => function ($url, $model, $key) {
                return Html::a(
                    '<span class="glyphicon glyphicon-plus"></span>',
                    '#',
                    ['class' => 'create-from-existing-letter-button']
                );
            }
        ],
    ];
}

?>

<?= SelectedDocumentsCache::widget([
    'saveUrl' => Url::to(['/edm/documents/select-entries', 'tabMode' => $entriesSelectionCacheKey]),
]) ?>

<?= $this->render('_search') ?>

<div class="clearfix">
    <div class="pull-left">
        <?php
        if ($userCanCreateDocument) {
            echo Html::button(
                Yii::t('edm', 'Create letter'),
                [
                    'id'    => 'create-letter-button',
                    'class' => 'btn btn-success',
                    'style' => 'margin-right: 10px',
                ]
            );
        }
        if (Yii::$app->user->identity->role === User::ROLE_USER) {
            echo Html::a(
                Yii::t('edm', 'Mark all as read'),
                ['/edm/bank-letter/mark-all-as-read'],
                [
                    'class' => 'btn btn-info',
                    'style' => 'margin-right: 10px; display: inline-block',
                    'data'  => ['method' => 'POST'],
                ]
            );
        }
        if ($userCanDeleteDocuments) {
            echo DeleteSelectedDocumentsButton::widget([
                'checkboxesSelector'       => '.delete-checkbox, .select-on-check-all',
                'entriesSelectionCacheKey' => $entriesSelectionCacheKey,
            ]);
            echo SelectedDocumentsCountLabel::widget(['checkboxesSelector' => '.delete-checkbox, .select-on-check-all']);
        }
        ?>
    </div>
</div>

<?= InfiniteGridView::widget([
    'id' => 'letters-grid',
    'emptyText' => Yii::t('other', 'No documents matched your query'),
    'summary' => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel' => $filterModel,
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => '.',
        'nullDisplay' => '',
    ],
    'highlightsByStatus' => true,
    'columns' => $columnsEnabled,
    'rowOptions' => function (BankLetterSearch $model, $key, $index, $grid) {
        $options = ['data-document-id' => $model->id];

        $classes = [];
        if (is_null($model->extModel)) {
            $classes = ['danger'];
        }

        if (!Yii::$app->user->can('admin') && $model->viewed == 0 && $model->direction === Document::DIRECTION_IN) {
            $classes[] = 'not-viewed';
        }

        if ($model->businessStatus === 'RJCT') {
            $classes[] = 'danger';
        }

        $options['class'] = implode(' ', $classes);

        $options['data']['status'] = $model->status;
        $options['data']['document-type'] = $model->type;

        return $options;
    },
    'options' => ['class' => 'grid-view documents-journal-grid'],
])
?>

<?= ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $filterModel
    ]
);
?>

<?= $this->render('@addons/edm/views/documents/_searchModal', ['model' => $filterModel]) ?>

<?= $this->render('_formModal', ['model' => $newLetterForm]) ?>

    <div id="view-modal-placeholder"></div>

    <style>
        #letters-grid tr.not-viewed {
            font-weight: bold;
        }
        .modal-body {
            word-wrap:break-word;
        }
    </style>

<?php

$js = <<<'JS'
    function showLetter(documentId) {
        $('#view-modal-placeholder').load(
            '/edm/bank-letter/view?id=' + documentId,
            function () {
                $('#view-modal-placeholder .modal').modal('show');
                $('#letters-grid tr[data-document-id=' + documentId + ']').removeClass('not-viewed');
            }
        );
    }

    function showForm(documentId, isEditMode) {
        $.get('/edm/bank-letter/get-form', {id: documentId}).done(function (response) {
            if (isEditMode && response.hasSignatures) {
                var confirmMessage = 'Внимание! Документ подписан! В случае изменения документа подписи будут автоматически отозваны! Редактировать документ?';
                if (!confirm(confirmMessage)) {
                    return;
                }
            }

            var form = $('#bank-letter-form');
            form.trigger('reset');
            var attrs = ['senderTerminalId', 'receiverBankBik', 'subject', 'message', 'isoMessageTypeCode', 'vtbMessageTypeCode', 'attachedFilesJson'];
            if (isEditMode) {
                attrs.push('documentId');
            }
            form.find('[name="BankLetterForm[documentId]"]').val(''); // Hidden form fields are reset
            attrs.forEach(function (attr) {
                var value = response[attr] === null || response[attr] === undefined
                    ? ''
                    : response[attr];
                form
                    .find('[name="BankLetterForm[' + attr + ']"]')
                    .val(value)
                    .trigger('change');
            });
            $('#view-modal').modal('hide');
            $('#form-modal').modal('show');
        });
    }

    $('#letters-grid').on('click', '.view-letter-button', function () {
        var documentId = $(this).closest('tr').data('document-id');
        showLetter(documentId);
        return false;
    });

    $('#create-letter-button').click(function () {
        $('#bankletterform-subject').val('');
        $('#bankletterform-message').html('');

        $('#form-modal').modal('show');
    });

    $('body').on('click', '.edit-letter-button', function(event) {
        event.preventDefault();
        var documentId = $(this).data('id');
        showForm(documentId, true);
    });

    $('#letters-grid').on('click', '.create-from-existing-letter-button', function () {
        event.preventDefault();
        var documentId = $(this).closest('tr').data('document-id');
        showForm(documentId, false);
    });

    $('#letters-grid tbody').on('dblclick', 'tr', function () {
        var documentId = $(this).data('document-id');
        showLetter(documentId);
    });

    $('body').on('click', '.btn-transport-info', function(e) {
        e.preventDefault();
        $('.transport-info').slideToggle('400');
    });
JS;

$this->registerJs($js, View::POS_READY);

$savedDocumentId = Yii::$app->session->getFlash('savedDocumentId');
if (!empty($savedDocumentId)) {
    $this->registerJs("showLetter($savedDocumentId);", View::POS_READY);
}

$this->registerCss('
#view-modal .modal-footer .btn {
    margin-left: 10px;
}
');
