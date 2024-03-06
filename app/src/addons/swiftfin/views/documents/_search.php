<?php

use yii\helpers\Html;
use kartik\field\FieldRange;
use kartik\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model common\document\DocumentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
	$formParams = ['method' => 'get'];
	$form = ActiveForm::begin($formParams);
	if (!isset($aliases)) {
		$aliases = [];
	}
?>

<div class="pull-right">
    <?php
        echo Html::a('',
            '#',
            [
                'class' => 'btn-columns-settings glyphicon glyphicon-cog',
                'title' => Yii::t('app', 'Columns settings')
            ]
        );
    ?>
</div>

<div class="row ow-fluid log-search">
    <div class="col-xs-4">
        <?= FieldRange::widget([
            'form' => $form,
            'model' => $model,
            'label' => Yii::t('other', 'Document registration date'),
            'attribute1' => 'dateCreateFrom',
            'attribute2' => 'dateCreateBefore',
            'type' => FieldRange::INPUT_WIDGET,
            'widgetClass' => DateControl::classname(),
            'widgetOptions1' => [
                'saveFormat' => 'php:Y-m-d'
            ],
            'widgetOptions2' => [
                'saveFormat' => 'php:Y-m-d'
            ],
            // Fix for missing translation of 'separator' label
            'separator' => Yii::t('doc', '&larr; to &rarr;'),
        ]); ?>
    </div>
    <div class="col-xs-4">
        <?= $form->field($model, 'searchBody')->label(Yii::t('other', 'Text search'))->textInput(['maxlength' => 200])?>
    </div>
    <div class="col-xs-4" style="padding-top: 5px">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
