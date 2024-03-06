<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form kartik\form\ActiveForm */
/* @var $model addons\edm\models\ForeignCurrencySellRequest */
$accounts = $model->getRecipientsProperties();
if (isset($accounts)) {
	foreach ($accounts as $k => $v) {
		$accounts[$k] = $k . ' ' . $v['name'];
	}
}
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
	table > tbody > tr > td {
		line-height: 33px !important;
	}
</style>

<table class="table">
	<tr>
		<td width="350"><?=$model->getLabel()?></td>
		<td>
			<span style="line-height: 33px">№</span> <?=$form->field($model, 'number')->textInput()?>
			от <?=$form->field($model, 'date')->label(false)->textInput()?>
			<?php \yii\jui\DatePicker::widget([
				'id'         => 'edmforeigncurrencysellrequest-date',
				'dateFormat' => 'dd.MM.yyyy',
			]) ?>
			<?php \yii\widgets\MaskedInput::widget([
				'id'            => 'edmforeigncurrencysellrequest-date',
				'name'          => 'edmforeigncurrencysellrequest-date',
				'mask'          => '99.99.9999',
				'clientOptions' => [
					'placeholder' => "dd.MM.yyyy",
				]
			])?>
		</td>
	</tr>
	<tr>
		<td>Ответственное лицо, телефон</td>
		<td>
			<?=$form->field($model, 'implementerFullName')->label(false)->textInput()?>
			<?=$form->field($model, 'implementerPhone')->label(false)->textInput()?>
			<?php \yii\widgets\MaskedInput::widget([
				'id'            => 'edmforeigncurrencysellrequest-implementerphone',
				'name'          => 'edmforeigncurrencysellrequest-implementerphone',
				'mask'          => '+9 (999) 999-99-99',
			])?>
		</td>
	</tr>
	<tr>
		<td>Клиент</td>
		<td id="recipient"></td>
	</tr>
	<tr>
		<td><?=$model->getAttributeLabel('fromName')?></td>
		<td><?=$form->field($model, 'fromName')->label(false)->textInput()
			?></td>
	</tr>
	<tr>
		<td><?=$model->getAttributeLabel('toName')?></td>
		<td>
			<?=$form->field($model, 'toName')->label(false)->textInput()
			?>
		</td>
	</tr>
	<tr>
		<td>Сумма покупки в валюте</td>
		<td>
			<?=$form->field($model, 'sum')->label(false)->textInput()?>
			<?=$form->field($model, 'currency')->label(false)->dropDownList(
				['' => $model->getAttributeLabel('currency')] + \common\helpers\Currencies::getCodeLabels()
			)?>
		</td>
	</tr>
	<tr>
		<td><?=$model->getAttributeLabel('accountRub')?></td>
		<td>
			<?php
			if (!empty($accounts)) {
				print $form->field($model, 'accountRub')->label(false)->dropDownList(
					['' => $model->getAttributeLabel('accountRub')] + $accounts
				);
			} else {
				print $form->field($model, 'accountRub')->label(false);
			}
			?>
		</td>
	</tr>
	<tr>
		<td><?=$model->getAttributeLabel('accountClient')?></td>
		<td>
			<?php
			if (!empty($accounts)) {
				print $form->field($model, 'accountClient')->label(false)->dropDownList(
					['' => $model->getAttributeLabel('accountClient')] + $accounts
				);
			} else {
				print $form->field($model, 'accountClient')->label(false);
			}
			?>
	</tr>
	<tr>
		<td><?=$model->getAttributeLabel('accountCurrency')?></td>
		<td><?=$form->field($model, 'accountCurrency')->label(false)->textInput()?></td>
	</tr>
</table>
<?php \yii\widgets\MaskedInput::widget([
	'id'            => 'edmforeigncurrencysellrequest-accountcurrency',
	'name'          => 'edmforeigncurrencysellrequest-accountcurrency',
	'mask'          => '99999999999999999999',
])?>

<?php if (!empty($accounts)): ?>
<script language="JavaScript" type="text/javascript">
	<?php ob_start() ?>
	$(window).ready(function () {
		$('#edmforeigncurrencysellrequest-fromname').attr('readonly', true);
		$('#edmforeigncurrencysellrequest-accountrub').change(function(){
			var attr = recipient[$(this).val()];
			if (undefined !== attr) {
				$('#edmforeigncurrencysellrequest-accountclient').val($(this).val());
				$('#edmforeigncurrencysellrequest-fromname').val(attr.name);
			} else {
				$('#edmforeigncurrencysellrequest-accountclient').val('');
				$('#edmforeigncurrencysellrequest-fromname').val('');
			}
		});
	});
	var recipient = <?=json_encode($model->getRecipientsProperties(),JSON_UNESCAPED_UNICODE)?>;
	<?php $js = ob_get_contents(); ob_end_clean(); ?>
</script>
<?php $this->registerJs($js) ?>
<?php endif; ?>

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
