<?php

use yii\helpers\Html;
use kartik\field\FieldRange;
use kartik\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model common\commands\search\CommandARSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
	$formParams = ['method' => 'get'];
	$form = ActiveForm::begin($formParams);
	if (!isset($aliases)) {
		$aliases = [];
	}
?>

<div class="row-fluid">
	<div class="col-sm-4">
		<?= FieldRange::widget([
			'form' => $form,
			'model' => $model,
			'label' => Yii::t('app', 'Approve date'),
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
		<div class="col-sm-2">
		<p style="margin-top:29px;"><?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?></p>
	</div>
</div>

<?php ActiveForm::end(); ?>

<style>
	#statusGroup label {
		width: 49%;
	}
</style>
