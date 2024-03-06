<?php

use yii\helpers\Html;
use kartik\field\FieldRange;
use kartik\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model addons\swiftfin\models\search\DocumentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
  <?= Yii::t('app', 'Search'); ?>
</a>
<div class="<?= (empty($filterStatus)) ? 'collapse' : 'collapse.in'; ?>" id="collapseSearch">

<?php
	$form_params = ['method' => 'get'];
	$form = ActiveForm::begin($form_params);
?>

<div class="row">
	<div class="col-xs-4">
		<?= FieldRange::widget([
			'form' => $form,
			'model' => $model,
//				'useAddons' => false,
			'label' => Yii::t('other', 'Document registration date'),
            'attribute1' => 'dateCreateFrom',
            'attribute2' => 'dateCreateBefore',
//				'type' => FieldRange::INPUT_DATE,
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
	<div class="col-xs-2">
		<label class="control-label">&nbsp;</label><br/>
		<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
	</div>
</div>
<?php ActiveForm::end(); ?>
