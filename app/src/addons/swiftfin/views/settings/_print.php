<?php

use kartik\widgets\ActiveForm;
use common\widgets\GridView;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'fullSpan' => 12,
    'formConfig' => [
        'labelSpan' => 3
    ]
]) ?>

<div class="row">
    <div class="col-sm-2 tab-buttons">
        <?=Html::submitButton(Yii::t('app', 'Save'), ['name' => 'save', 'class' => 'btn btn-primary btn-block']) ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
        $myGridWidget = GridView::begin([
            'emptyText'    => Yii::t('other', 'No document types found'),
            'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
            'dataProvider' => $dataProvider,
            'columns' => [
                    [
                        'attribute' => 'name',
                        'label' => Yii::t('app/terminal', 'Incoming document type'),
                    ],
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'header' => Yii::t('app/terminal', 'Print automatically'),
                        'name' => 'mt',
                        'checkboxOptions' => function($model, $key, $index, $column) {
                            return [
                                'value' => $key,
                                'checked' => $model['checked']
                            ];
                        }
                    ],
                ],
        ]);
        $myGridWidget->end();
        ?>
    </div>
</div>
<?php ActiveForm::end()?>


