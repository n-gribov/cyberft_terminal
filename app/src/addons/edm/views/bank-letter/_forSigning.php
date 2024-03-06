<?php

use addons\edm\models\BankLetter\BankLetterSearch;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\InfiniteGridView;
use yii\helpers\Html;
use yii\web\JsExpression;

$columns = [
    'subject' => [
        'attribute' => 'subject',
    ],
    'sender' => [
        'attribute' => 'sender',
        'value' => 'senderParticipant.name',
    ],
    'receiver' => [
        'attribute' => 'receiver',
        'value' => 'receiverParticipant.name',
    ],
    'dateCreate' => [
        'attribute' => 'dateCreate',
        'value'     => 'dateCreate',
        'format' => ['date', 'dd.MM.Y'],
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
                    'style' => 'width: 130px'
                ]
            ]
        ),
        'headerOptions' => [
            'style' => 'width:1%;',
        ],
        'contentOptions' => [
            'class' => 'text-center'
        ]
    ],
    'signaturesRequired' => [
        'attribute' => 'signaturesRequired',
    ],
    'signaturesCount' => [
        'attribute' => 'signaturesCount',
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

echo InfiniteGridView::widget([
    'id' => 'letters-grid',
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'highlightsByStatus' => true,
    'columns' => $columnsSettings,
    'rowOptions' => function (BankLetterSearch $model, $key, $index, $grid) {
        return ['data-document-id' => $model->id];
    },
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
]);

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $filterModel
    ]
);

$js = <<<JS
    $('#banklettersearch-datecreate').inputmask('99.99.9999', { placeholder: 'дд.мм.гггг' });

    function showLetter(documentId) {
        $('#view-modal-placeholder').load(
            '/edm/bank-letter/view?id=' + documentId,
            function () {
                $('#view-modal-placeholder .modal').modal('show');
            }
        );
    }

    $('.view-letter-button').click(function () {
        var documentId = $(this).closest('tr').data('document-id');
        showLetter(documentId);
        return false;
    });
    $('#letters-grid tbody tr').dblclick(function () {
        var documentId = $(this).data('document-id');
        showLetter(documentId);
    });
JS;

$this->registerJS($js);
?>

<div id="view-modal-placeholder"></div>
