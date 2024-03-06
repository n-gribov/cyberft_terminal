<?php

use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationItem;
use common\helpers\Html;
use common\widgets\ActionColumn;
use common\widgets\GridView;
use yii\data\ArrayDataProvider;
use yii\grid\SerialColumn;
use yii\web\View;

$dataProvider = new ArrayDataProvider([
    'allModels' => $childObjectData,
    'modelClass' => ForeignCurrencyOperationInformationItem::className(),
]);

$submitSaveText = Yii::t('app','Save');
//Yii::$app->formatter->decimalSeparator = '.';
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'emptyText' => false,
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => '.'
    ],
    'columns' => [
        [
            'class' => SerialColumn::className(),
            'header' => 'Номер строки'
        ],
        [
            'attribute' => 'number',
            'value' => function($model) {
                return $model->number ?: 'БН';
            }
        ],
        'docDate',
        [
            'attribute' => 'operationSum',
            'label' => 'Сумма',
            'format' => ['decimal', 2]
        ],
        'currencyTitle',
        //'operationDate',
        'paymentDocumentNumber',
        'paymentTypeTitle',
        [
            'class' => ActionColumn::className(),
            'template' => '{edit}<span style="padding-left:1em"></span>{delete}',
            'buttons'  => [
                'edit' => function ($url, $model, $key) {
                    return Html::a(
                        Html::tag('span', '', ['class' => 'glyphicon glyphicon-pencil']),
                        '#', [
                            'class' => 'update-operation',
                            'title' => Yii::t('app', 'Update'),
                            'data' => ['uuid' => $key]
                        ]
                    );
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a(
                        Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']),
                        '#', [
                            'class' => 'delete-operation',
                            'title' => Yii::t('app', 'Delete'),
                            'data' => ['uuid' => $key]
                        ]
                    );
                }
            ],
        ]
    ]
]);

$script = <<<JS
    // Удаление строки с операцией
    $('.delete-operation').on('click', function(e) {
        e.preventDefault();

        var uuid = $(this).data('uuid');

        $.ajax({
            url: '/edm/foreign-currency-control/delete-operation',
            data: 'uuid=' + uuid,
            type: 'get',
            success: function(result) {

                // Отображение таблицы операций
                $('.operations').html(result);
                checkCreateButton();

                // Удаление кэша операции
                $.post('/wizard-cache/fcc', {DeleteOperation: uuid});
            },
            fail: function(result) {
                alert(result);
            }
        });
    });

    // Обновление строки с операцией
    $('.update-operation').on('click', function(e) {
        e.preventDefault();

        var uuid = $(this).data('uuid');

        $.ajax({
            url: '/edm/foreign-currency-control/update-operation',
            data: 'uuid=' + uuid,
            type: 'get',
            success: function(result) {
                $('#operation-modal .modal-body').html(result);
                $('#operation-submit').html('{$submitSaveText}');
                attachFileController.initialize();
                $('#operation-modal').modal('show');
            }
        });
    });
JS;

$this->registerJs($script, View::POS_READY);
