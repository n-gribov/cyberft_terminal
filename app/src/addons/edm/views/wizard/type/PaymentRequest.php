<?php

use kartik\widgets\ActiveForm;
use yii\web\View;
use addons\edm\models\Document1C;
use yii\helpers\Html;

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
		<td width="260"><h4><?=$model->label?>&nbsp;№</h4></td>
		<td width="10%"><?=$form->field($model, 'number')?></td>
		<td></td>
		<td width="10%"><?=$form->field($model, 'date')?></td>
		<td width="2%"></td>
		<td width="10%"><?=$form->field($model, 'paymentType')?></td>
		<td width="2%"></td>
		<td width="15%"><?=$form->field($model, 'senderStatus')?></td>
	</tr>
</table>
<?php \yii\jui\DatePicker::widget([
	'id'         => 'edmpaymentrequest-date',
	'dateFormat' => 'dd.MM.yyyy',
]) ?>
<?php \yii\widgets\MaskedInput::widget([
	'id'            => 'edmpaymentrequest-date',
	'name'          => 'edmpaymentrequest-date',
	'mask'          => '99.99.9999',
	'clientOptions' => [
		'placeholder' => 'dd.MM.yyyy',
	]
])?>

<table class="table table-bordered" style="margin-bottom: -1px">
	<tr>
		<td width="100">Условия оплаты</td>
		<td>
			<?=$form->field($model, 'paymentCondition1')?><br/>
			<?=$form->field($model, 'paymentCondition2')?><br/>
			<?=$form->field($model, 'paymentCondition3')?><br/>
		</td>
		<td width="100">Срок для акцепта</td>
		<td width="20%"><?=$form->field($model, 'acceptPeriod')?></td>
	</tr>
</table>

<table class="table table-bordered" style="margin-bottom: -1px">
	<tr>
		<td colspan="2" rowspan="2">
			ИНН
			<?=$form->field($model, 'payerName')->textarea()?>
			Плательщик
		</td>
		<td>Сумма</td>
		<td colspan="3"><?=$form->field($model, 'sum')?></td>
	</tr>
	<tr>
		<td>Сч. №</td>
		<td colspan="3"><?=$form->field($model, 'payerCheckingAccount')?></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
			<?=$form->field($model, 'payerBank1')->textarea()?>
			<?=$form->field($model, 'payerBank2')?>
			Банк плательщика
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
			<?=$form->field($model, 'beneficiaryBank1')->textarea()?>
			<?=$form->field($model, 'beneficiaryBank2')?>
			Банк получателя
		</td>
		<td>БИК</td>
		<td colspan="3"><?=$form->field($model, 'beneficiaryBik')?></td>
	</tr>
	<tr>
		<td>Сч. №</td>
		<td colspan="3"><?=$form->field($model, 'beneficiaryCorrespondentAccount')?></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="4">
			ИНН
			<?=$form->field($model, 'beneficiaryName')->textarea(['rows'=>6])?>
			Получатель
		</td>
		<td>Сч. №</td>
		<td colspan="3"><?=$form->field($model, 'beneficiaryCheckingAccount')?></td>
	</tr>
	<tr>
		<td width="100">Вид. оп.</td>
		<td width="150"><?=$form->field($model, 'payType')?></td>
		<td width="100">Очер. плат.</td>
		<td width="150"><?=$form->field($model, 'priority')?></td>
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
<table class="table">
	<tr>
		<td>
			Назначение платежа
			<?=$form->field($model, 'payPurpose')?>
		</td>
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
