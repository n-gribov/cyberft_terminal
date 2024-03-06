<?php

use addons\swiftfin\models\documents\mt\MtUniversalDocument;
use kartik\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model MtUniversalDocument */
unset($form);
$form = ActiveForm::begin([
	'id'                     => 'docEdit',
	'type'                   => ActiveForm::TYPE_HORIZONTAL,
	'fullSpan'               => 12,
	'formConfig'             => ['labelSpan' => 3],
	'enableClientValidation' => false,
]);
?>

<div class="mt20">
	<?=$form->errorSummary($model)?>
	<?=$model->toHtmlForm($form)?>
</div>

<div class="col-md-offset-4 col-md-8">
	<?=Html::a(
		Yii::t('app', 'Back'),
		['/edm/wizard/index'],
		[
			'name'  => 'send',
			'class' => 'btn btn-default'
		]
	)?>
	<?=Html::submitButton(Yii::t('app', 'Next'), ['name' => 'send', 'class' => 'btn btn-primary'])?>
</div>
<?php $form->end() ?>

<?php \yii\jui\DatePicker::widget([
	'id'         => 'edmpaymentorderforeigncurrency-32a-date',
	'dateFormat' => 'yyMMdd',
]) ?>
<?php \yii\widgets\MaskedInput::widget([
	'id'            => 'edmpaymentorderforeigncurrency-32a-date',
	'name'          => 'edmpaymentorderforeigncurrency-32a-date',
	'mask'          => '999999',
	'clientOptions' => [
		'placeholder' => 'yyMMdd',
	]
])?>
