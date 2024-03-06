<?php

use kartik\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use addons\edm\models\Document1C;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model Document1C */
?>

<?php $form = ActiveForm::begin([
	'type'                   => ActiveForm::TYPE_INLINE,
	'enableClientValidation' => false,
	'enableAjaxValidation'   => true,
	'formConfig'             => [
		'labelSpan'  => 3,
		'deviceSize' => ActiveForm::SIZE_TINY,
		'showErrors' => true,
	]
]);
?>

<?php // Временно такой вот костылик со стилями ?>
<style type="text/css">
	.form-inline .form-group {
		width: 100% !important;
	}
	
	.form-inline .form-group .form-control {
		width: 100% !important;
	}
</style>

<table class="table">
	<tr>
		<td width="250"><h4><?=$model->label?>&nbsp;№</h4></td>
		<td width="10%"><?=$form->field($model, 'number')?></td>
		<td></td>
		<td width="10%"><?=$form->field($model, 'date')?></td>
		<td width="2%"></td>
		<td width="10%"><?=$form->field($model, 'paymentType')?></td>
		<td width="2%"></td>
		<td width="15%"><?=$form->field($model, 'senderStatus')?></td>
		<td></td>
	</tr>
</table>
<?php \yii\jui\DatePicker::widget([
	'id'         => 'edmcollectionorder-date',
	'dateFormat' => 'dd.MM.yyyy',
]) ?>
<?php \yii\widgets\MaskedInput::widget([
	'id'            => 'edmcollectionorder-date',
	'name'          => 'edmcollectionorder-date',
	'mask'          => '99.99.9999',
	'clientOptions' => [
		'placeholder' => "dd.MM.yyyy",
	]
])?>

<table class="table table-bordered" style="margin-bottom: -1px">
	<tr>
		<td><?=$form->field($model, 'payerInn')?></td>
		<td><?=$form->field($model, 'payerKpp')?></td>
		<td rowspan="2">Сумма</td>
		<td colspan="3" rowspan="2"><?=$form->field($model, 'sum')?></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
			<?=$form->field($model, 'payerName')->textarea(['cols' => 80])?>
		</td>
	</tr>
	<tr>
		<td>Сч. №</td>
		<td colspan="3"><?=$form->field($model, 'payerCheckingAccount')?></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
			Банк плательщика:<br/>
			<?=$form->field($model, 'payerBank1')->textarea(['cols' => 80])?>
			<?=$form->field($model, 'payerBank2')?>
		</td>
		<td>БИК</td>
		<td colspan="3"><?=$form->field($model, 'payerBik')?></td>
	</tr>
	<tr>
		<td>Сч. №</td>
		<td colspan="3"><?=$form->field($model, 'payerCorrespondentAccount')?></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
			Банк получателя:<br/>
			<?=$form->field($model, 'beneficiaryBank1')->textarea(['cols' => 80])?>
			<?=$form->field($model, 'beneficiaryBank2')?>
		</td>
		<td>БИК</td>
		<td colspan="3"><?=$form->field($model, 'beneficiaryBik')?></td>
	</tr>
	<tr>
		<td>Сч. №</td>
		<td colspan="3"><?=$form->field($model, 'beneficiaryCorrespondentAccount')?></td>
	</tr>
	<tr>
		<td><?=$form->field($model, 'beneficiaryInn')?></td>
		<td><?=$form->field($model, 'beneficiaryKpp')?></td>
		<td>Сч. №</td>
		<td colspan="3" rowspan="2"><?=$form->field($model, 'beneficiaryCheckingAccount')?></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="4"><?=$form->field($model, 'beneficiaryName')->textarea(['cols' => 80])?></td>
	</tr>
	<tr>
		<td>Вид. оп.</td>
		<td><?=$form->field($model, 'payType')?></td>
		<td>Очер. плат.</td>
		<td><?=$form->field($model, 'priority')?></td>
	</tr>
	<tr>
		<td>Наз. пл.</td>
		<td>&nbsp;</td>
		<td rowspan="2">Рез. поле</td>
		<td rowspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td>Код</td>
		<td>&nbsp;</td>
	</tr>
</table>
<table class="table table-bordered">
	<tr>
		<td><?=$form->field($model, 'indicatorKbk')?></td>
		<td><?=$form->field($model, 'okato')?></td>
		<td><?=$form->field($model, 'indicatorReason')?></td>
		<td><?=$form->field($model, 'indicatorPeriod')?></td>
		<td><?=$form->field($model, 'indicatorNumber')?></td>
		<td><?=$form->field($model, 'indicatorDate')?></td>
		<td><?=$form->field($model, 'indicatorType')?></td>
	</tr>
</table>

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
