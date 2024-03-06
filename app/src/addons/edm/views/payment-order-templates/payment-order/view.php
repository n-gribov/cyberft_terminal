<?php

use addons\edm\models\PaymentOrder\PaymentOrderType;
use common\helpers\Html;

$paymentOrder = (new PaymentOrderType)->loadFromString($model->body);
$paymentOrder->unsetParticipantsNames();

$this->title = $model->name;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app/menu', 'Banking'),
    'url' => ['/edm']
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app/menu', 'My templates'),
    'url' => ['index']
];

$this->params['breadcrumbs'][] = $model->name;

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
        <td width="300" rowspan="2">Платежное поручение №<?=$paymentOrder->number?></td>
        <td width="10%" style="text-align: center;"><strong><?= $paymentOrder->date; ?></strong></td>
        <td width="10%" style="text-align: center;"><strong><?= Yii::t('doc',
                    'E-commerce'); ?></strong></td>
        <td width="10%" rowspan="2"><?=$paymentOrder->paymentType?></td>
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
        <td><b>ИНН:</b> <?=$paymentOrder->payerInn?></td>
        <td><b>КПП:</b> <?=$paymentOrder->payerKpp?></td>
        <td><b>Сумма</b></td>
        <td colspan="3" class="text-right"><?= $paymentOrder->sum ?></td>
    </tr>
    <tr>
        <td colspan="2">
            <?=$paymentOrder->payerName?><br/>
            <small>Плательщик</small>
        </td>
        <td><b>Сч. №</b></td>
        <td colspan="3" class="text-right">
            <?=$paymentOrder->payerCheckingAccount?>
        </td>
    </tr>
    <tr>
        <td colspan="2" rowspan="2">
            <?=$paymentOrder->payerBank1?><br/>
            <?=$paymentOrder->payerBank2?><br/>
            <small>Банк плательщика</small>
        </td>
        <td><b>БИК</b></td>
        <td colspan="3" class="text-right"><?=$paymentOrder->payerBik?></td>
    </tr>
    <tr>
        <td><b>Сч. №</b></td>
        <td colspan="3" class="text-right"><?=$paymentOrder->payerCorrespondentAccount?></td>
    </tr>
    <tr>
        <td colspan="2" rowspan="2">
            <?=$paymentOrder->beneficiaryBank1?><br/>
            <?=$paymentOrder->beneficiaryBank2?><br/>
            <small>Банк получателя</small>
        </td>
        <td><b>БИК</b></td>
        <td colspan="3" class="text-right"><?=$paymentOrder->beneficiaryBik?></td>
    </tr>
    <tr>
        <td><b>Сч. №</b></td>
        <td colspan="3" class="text-right"><?=$paymentOrder->beneficiaryCorrespondentAccount?></td>
    </tr>
    <tr>
        <td>ИНН: <?=$paymentOrder->beneficiaryInn?></td>
        <td>КПП: <?=$paymentOrder->beneficiaryKpp?></td>
        <td><b>Сч. №</b></td>
        <td colspan="3" class="text-right"><?=$paymentOrder->beneficiaryCheckingAccount?></td>
    </tr>
    <tr>
        <td colspan="2" rowspan="3">
            <?=$paymentOrder->beneficiaryName?><br/>
            <small>Получатель</small>
        </td>
        <td><b>Вид. оп.</b></td>
        <td class="text-right"><?=$paymentOrder->payType?></td>
        <td><b>Срок. плат.</b></td>
        <td><?= $paymentOrder->maturity; ?></td>
    </tr>
    <tr>
        <td><b>Наз. пл.</b></td>
        <td><?= $paymentOrder->paymentOrderPaymentPurpose; ?></td>
        <td><b>Очер. плат.</b></td>
        <td class="text-right"><?=$paymentOrder->priority?></td>
    </tr>
    <tr>
        <td><b>Код</b></td>
        <td class="text-right"><?= $paymentOrder->code; ?></td>
        <td><b>Рез. поле</b></td>
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