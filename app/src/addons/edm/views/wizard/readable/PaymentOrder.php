<?php

use addons\edm\models\Document1C;
use kartik\widgets\ActiveForm;
use yii\web\View;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model Document1C */
?>

<style type="text/css">
	table.table td {
		background: #ffffff
	}
	table tr.iespike {
		border: none;
	}
	table tr.iespike td {
		border: none; padding: 0;
	}
</style>

<table class="table">
	<tr>
		<td width="300"><h4>Платежное поручение №<?=$model->number?></h4></td>
		<td></td>
		<td width="10%"><?=$model->date?></td>
		<td width="2%"></td>
		<td width="10%"><?=$model->paymentType?></td>
		<td width="2%"></td>
		<td width="15%"><?=$model->senderStatus?></td>
	</tr>
</table>

<table class="table table-bordered" style="margin-bottom: -1px">
	<tr style="visibility: hidden; border: none" class="iespike">
		<td width="30%"></td>
		<td width="30%"></td>
		<td width="10%"></td>
		<td width="10%"></td>
		<td width="10%"></td>
		<td width="10%"></td>
	</tr>
	<tr>
		<td>ИНН: <?=$model->payerInn?></td>
		<td>КПП: <?=$model->payerKpp?></td>
		<td>Сумма</td>
		<td colspan="3"><?= Yii::$app->formatter->asDecimal($model->sum, 2); ?></td>
	</tr>
	<tr>
		<td colspan="2">
			<?=$model->payerName?><br/>
			<small>Плательщик</small>
		</td>
		<td>Сч. №</td>
		<td colspan="3">
			<?=$model->payerCheckingAccount?>
		</td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
			<?=$model->payerBank1?><br/>
			<?=$model->payerBank2?><br/>
			<small>Банк плательщика</small>
		</td>
		<td>БИК</td>
		<td colspan="3"><?=$model->payerBik?></td>
	</tr>
	<tr>
		<td>Сч. №</td>
		<td colspan="3"><?=$model->payerCorrespondentAccount?></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
			<?=$model->beneficiaryBank1?><br/>
			<?=$model->beneficiaryBank2?><br/>
			<small>Банк получателя</small>
		</td>
		<td>БИК</td>
		<td colspan="3"><?=$model->beneficiaryBik?></td>
	</tr>
	<tr>
		<td>Сч. №</td>
		<td colspan="3" class="text-right"><?=$model->beneficiaryCorrespondentAccount?></td>
	</tr>
	<tr>
		<td>ИНН: <?=$model->beneficiaryInn?></td>
		<td>КПП: <?=$model->beneficiaryKpp?></td>
		<td>Сч. №</td>
		<td colspan="3" rowspan="2" class="text-right"><?=$model->beneficiaryCheckingAccount?></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="4">
			<?=$model->beneficiaryName?><br/>
			<small>Получатель</small>
		</td>
	</tr>
	<tr>
		<td>Вид. оп.</td>
		<td><?=$model->payType?></td>
		<td>Срок. плат.</td>
		<td><?= $model->maturity; ?></td>
	</tr>
	<tr>
		<td>Наз. пл.</td>
		<td><?= $model->paymentOrderPaymentPurpose; ?></td>
		<td>Очер. плат.</td>
		<td><?=$model->priority?></td>
	</tr>
	<tr>
		<td>Код</td>
		<td><?= $model->code; ?></td>
		<td>Рез. поле</td>
		<td><?= $model->backingField; ?></td>
	</tr>
</table>

<table class="table table-bordered">
	<tr height="28px;">
		<td width="14.2%" class="text-right"><?=$model->indicatorKbk?></td>
		<td width="14.2%" class="text-right"><?=$model->okato?></td>
		<td width="14.2%" class="text-right"><?=$model->indicatorReason?></td>
		<td width="14.2%" class="text-right"><?=$model->indicatorPeriod?></td>
		<td width="14.2%" class="text-right"><?=$model->indicatorNumber?></td>
		<td width="14.2%" class="text-right"><?=$model->indicatorDate?></td>
		<td width="14.2%" class="text-right"><?=$model->indicatorType?></td>
	</tr>
</table>
<table class="table table-bordered">
	<tr>
		<td>
			<?=$model->paymentPurpose?><br/>
			<small>Назначение платежа</small>
		</td>
	</tr>
</table>
