<?php

use addons\edm\models\PaymentOrder\PaymentOrderType;
use common\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\DateHelper;
use addons\edm\helpers\EdmHelper;

$paymentOrder = (new PaymentOrderType)->loadFromString($model->body);
$paymentOrder->unsetParticipantsNames();

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

<?php

// Формирование строки описания бизнес-статуса документа
$businessStatus = "";
$businessStatus .= $model->businessStatusTranslation;

if (!empty($model->businessStatusDescription)) {
    $businessStatus .= "<br>" . $model->businessStatusDescription;
}

if ($model->businessStatus == 'RJCT' && !empty($model->businessStatusComment)) {
    $businessStatus .= "<br><br>" . $model->businessStatusComment;
}

?>

<?=
    DetailView::widget([
        'template' => '<tr><th class="col-sm-1">{label}</th><td'
            . ($model->businessStatus == 'RJCT' ? ' class="alert-danger"'
            : null)
            . '>{value}</td></tr>',
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'businessStatus',
                'value' => $businessStatus,
                'format' => 'html'
            ]
        ]
    ])
?>

<table class="table table-bordered" style="margin-bottom: -1px">
    <tr>
        <td width="30%" style="text-align: center;"><strong><?= (!empty($model->dateProcessing)) ? date("d.m.Y", strtotime($model->dateProcessing)) : ''; ?></strong><br/></td>
        <td width="30%" style="text-align: center;"><strong><?= (!empty($model->dateDue)) ? date("d.m.Y", strtotime($model->dateDue)) : ''; ?></strong></td>
    </tr>
    <tr>
        <td width="30%" style="text-align: center;"><?= Yii::t('doc', 'Received by the payer\'s bank'); ?></td>
        <td width="30%" style="text-align: center;"><?= Yii::t('doc', 'Debited from the payer\'s account'); ?></td>
    </tr>
</table>
<table class="table table-bordered" style="margin-bottom: -1px">
	<tr>
        <td width="300" rowspan="2"><h4>Платежное поручение №<?=$paymentOrder->number?></h4></td>
        <td width="10%" style="text-align: center;"><strong><?= $paymentOrder->date; ?></strong></td>
        <td width="10%" style="text-align: center;"><strong><?=$paymentOrder->paymentType?></strong></td>
        <td width="10%" rowspan="2"></td>
        <td width="2%" rowspan="2"></td>
        <td width="15%" rowspan="2"><?=$paymentOrder->senderStatus?></td>
	</tr>
    <tr>
        <td style="text-align: center;"><?= Yii::t('doc', 'Date'); ?></td>
        <td style="text-align: center;"><?= Yii::t('doc', 'Payment type'); ?></td>
    </tr>
	<tr style="visibility: hidden; border: none" class="iespike">
		<td width="30%"></td>
		<td width="30%"></td>
		<td width="10%"></td>
		<td width="10%"></td>
		<td width="10%"></td>
		<td width="10%"></td>
	</tr>
	<tr>
		<td>ИНН: <?=$paymentOrder->payerInn?></td>
		<td>КПП: <?=$paymentOrder->payerKpp?></td>
		<td>Сумма</td>
        <td colspan="3" class="text-left"><?= Yii::$app->formatter->asDecimal($paymentOrder->sum, 2) ?></td>
	</tr>
	<tr>
		<td colspan="2">
			<?=$paymentOrder->payerName?><br/>
			<small>Плательщик</small>
		</td>
		<td>Сч. №</td>
        <td colspan="3" class="text-left">
			<?=$paymentOrder->payerCheckingAccount?>
		</td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
			<?=$paymentOrder->payerBank1?><br/>
			<?=$paymentOrder->payerBank2?><br/>
			<small>Банк плательщика</small>
		</td>
		<td>БИК</td>
        <td colspan="3" class="text-left"><?=$paymentOrder->payerBik?></td>
	</tr>
	<tr>
		<td>Сч. №</td>
        <td colspan="3" class="text-left"><?=$paymentOrder->payerCorrespondentAccount?></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
			<?=$paymentOrder->beneficiaryBank1?><br/>
			<?=$paymentOrder->beneficiaryBank2?><br/>
			<small>Банк получателя</small>
		</td>
		<td>БИК</td>
        <td colspan="3" class="text-left"><?=$paymentOrder->beneficiaryBik?></td>
	</tr>
	<tr>
		<td>Сч. №</td>
        <td colspan="3" class="text-left"><?=$paymentOrder->beneficiaryCorrespondentAccount?></td>
	</tr>
	<tr>
		<td>ИНН: <?=$paymentOrder->beneficiaryInn?></td>
		<td>КПП: <?=$paymentOrder->beneficiaryKpp?></td>
		<td>Сч. №</td>
        <td colspan="3" class="text-left"><?=$paymentOrder->beneficiaryCheckingAccount?></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="3">
			<?=$paymentOrder->beneficiaryName?><br/>
			<small>Получатель</small>
		</td>
		<td>Вид. оп.</td>
        <td class="text-left"><?=$paymentOrder->payType?></td>
		<td>Срок. плат.</td>
		<td><?= $paymentOrder->maturity; ?></td>
	</tr>
	<tr>
		<td>Наз. пл.</td>
		<td><?= $paymentOrder->paymentOrderPaymentPurpose; ?></td>
		<td>Очер. плат.</td>
		<td class="text-left"><?=$paymentOrder->priority?></td>
	</tr>
	<tr>
		<td>Код</td>
        <td class="text-left"><?= $paymentOrder->code; ?></td>
		<td>Рез. поле</td>
		<td><?= $paymentOrder->backingField; ?></td>
	</tr>
</table>
<table class="table table-bordered" style="margin-bottom: -1px">
	<tr>
        <td width="14.2%" class="text-right"><?=$paymentOrder->indicatorKbk?><br/></td>
		<td width="14.2%" class="text-right"><?=$paymentOrder->okato?></td>
		<td width="14.2%" class="text-right"><?=$paymentOrder->indicatorReason?></td>
		<td width="14.2%" class="text-right"><?=$paymentOrder->indicatorPeriod?></td>
		<td width="14.2%" class="text-right"><?=$paymentOrder->indicatorNumber?></td>
		<td width="14.2%" class="text-right"><?=$paymentOrder->indicatorDate?></td>
		<td width="14.2%" class="text-right"><?=$paymentOrder->indicatorType?></td>
	</tr>
</table>
<table class="table table-bordered">
	<tr>
		<td>
			<?=$paymentOrder->paymentPurpose?><br/>
			<small>Назначение платежа</small>
		</td>
	</tr>
</table>

<?php if (!empty($model->dateDue)) :?>
<center>
        <table>
            <tr>
                <td align="center" class="stamp">
                    <b><?= Html::encode($paymentOrder->payerBank1) ?><br/>
                        ПРОВЕДЕНО</b><br/>
                    <?= DateHelper::formatDate($model->dateDue) ?>
                </td>
            </tr>
        </table>
</center>
<?php endif ?>
<?php
// Если платежное поручение входит в состав реестра платежных поручений,
// выводим блок подписей

if (!empty($model->registerId)) {
    $signatures = EdmHelper::getPaymentRegisterSignaturesList($model->registerId);
    if ($signatures) {
        echo $this->render('@common/views/document/_signatures', ['signatures' => $signatures]);
    }
}
?>
