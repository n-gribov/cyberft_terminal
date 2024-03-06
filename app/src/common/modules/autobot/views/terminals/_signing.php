<?php

use common\widgets\GridView;
use common\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\TouchSpin;

$dataProvider = $data['dataProvider'];
$terminalId = $data['terminalId'];
$commonSigningSettings = $data['commonSigningSettings'];

?>

<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'fullSpan' => 12,
    'formConfig' => [
        'labelSpan' => 3
    ]
]);

?>

<label>
    <?= Html::checkbox('usePersonalAddonsSigningSettings', $commonSigningSettings['usePersonalAddonsSigningSettings'], ['id' => 'usePersonal']) ?>
    <?= Yii::t('app/settings', 'Use separate signing settings for each module') ?>
</label>

<div class="row">
    <div class="col-md-5">

<?php
// Создать таблицу для вывода
$myGridWidget = GridView::begin([
    'emptyText'    => Yii::t('other', 'No entries found'),
    'id' => 'commonSettings',
    'summary'      => false,
    'dataProvider' => $commonSigningSettings['dataProvider'],
    'columns' => [
        [
            'attribute' => 'title',
            'label' => Yii::t('app', 'Module'),
            'options' => [
                'width' => 150,
            ],
        ],
        [
            'attribute' => 'qty',
            'label' => Yii::t('app', 'Required signatures'),
            'options' => [
                'width' => 80,
            ],
            'value' => function($model){
                return TouchSpin::widget([
                    'name' => 'commonQtySignings',
                    'options' => [
                        'onClick' => 'this.setSelectionRange(0, this.value.length)'
                    ],
                    'pluginOptions' => [
                        'initval' => $model['qty'] ? $model['qty'] : 0,
                        'min' => 0,
                        'max' => 7,
                        'verticalbuttons' => true
                    ],
                ]);
            },
            'format' => 'raw'
        ],
        [
            'header' => Yii::t('app', 'Automatic signing'),
            'format' => 'raw',
            'value' => function($model) {
                return Html::checkbox('commonAutoSigning', $model['auto']);
            }
        ],
    ],
]);
$myGridWidget->end();
// Создать таблицу для вывода
$myGridWidget = GridView::begin([
    'emptyText'    => Yii::t('other', 'No entries found'),
    'id' => 'modulesSettings',
    'summary'      => false,
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'title',
            'label' => Yii::t('app', 'Module'),
            'options' => [
                'width' => 150,
            ],
        ],
        [
            'attribute' => 'qty',
            'label' => Yii::t('app', 'Required signatures'),
            'options' => [
                'width' => 80,
            ],
            'value' => function($model){
                return TouchSpin::widget([
                    'name' => 'qty[' . $model['document'] .']',
                    'options' => [
                        'onClick' => 'this.setSelectionRange(0, this.value.length)'
                    ],
                    'pluginOptions' => [
                        'initval' => $model['qty'] ? $model['qty'] : 0,
                        'min' => 0,
                        'max' => 7,
                        'verticalbuttons' => true
                    ],
                ]);
            },
            'format' => 'raw'
        ],
        [
            'class' => 'yii\grid\CheckboxColumn',
            'header' => Yii::t('app', 'Automatic signing'),
            'name' => 'autoSigning',
            'checkboxOptions' => function($model) {
                return [
                    'value' => $model['document'],
                    'checked' => $model['auto'] ? true : null,
                ];
            }
        ],
    ],
]);
$myGridWidget->end();
?>

<p><?=Html::submitButton(Yii::t('app', 'Save'), ['name' => 'save', 'class' => 'btn btn-success']) ?></p>

</div>
</div>

<?php
ActiveForm::end();

$this->registerCss('
    /* Стилизация кнопок изменения количества подписей */
    .bootstrap-touchspin .input-group-btn-vertical .bootstrap-touchspin-down {
        margin-top: -1px;
    }

    .bootstrap-touchspin .input-group-btn-vertical i {
        top: 9px;
    }

    #usePersonal,
    .checkbox-label {
        cursor: pointer;
    }

    #modulesSettings,
    #commonSettings {
        display: none;
    }
');

$script = <<<JS
    // Скрывать/отображать список настроек модулей
    function settingsVisibility() {
        if ($('#usePersonal').prop('checked')) {
            $('#modulesSettings').slideDown();
            $('#commonSettings').slideUp();
        } else {
            $('#modulesSettings').slideUp();
            $('#commonSettings').slideDown();
        }
    }

    $('#usePersonal').on('click', function() {
        settingsVisibility();
    });

    settingsVisibility();
JS;

$this->registerJs($script, yii\web\View::POS_READY);

