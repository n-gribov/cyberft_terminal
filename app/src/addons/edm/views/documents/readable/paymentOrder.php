<?php

use common\document\Document;
use common\helpers\DateHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\certManager\models\Cert;
use common\widgets\FastPrint\FastPrint;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/** @var View $this */
/** @var Document $model */

$isPaymentRequirement = false;

if ($paymentOrder->isRequirement()) {
    $isPaymentRequirement = true;
}
?>

<div class="btn-group">
    <?=Html::a(Yii::t('app', 'Print'),
        Url::toRoute(['/edm/documents/print-statement-payment-order', 'id' => $documentId, 'num' => $num]), ['target' => '_blank', 'class' => 'btn btn-primary btn-statement-print'])?>
    <button type="button" class="btn btn-default"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?=Yii::t('edm', 'Export')?> <span class="caret"></span>
    </button>
    <ul class="dropdown-menu pull-left">
        <li><?=Html::a(Yii::t('app', 'Export as {format}', ['format' => 'Excel']),
                Url::toRoute(['/edm/export/export-statement-paymentorder', 'id' => $documentId, 'num' => $num, 'exportType' => 'excel']),                   ['target' => '_blank'])?>
        </li>
        <li><?=Html::a(Yii::t('app', 'Export as {format}', ['format' => '1C']),
                Url::toRoute(['/edm/export/export-statement-paymentorder', 'id' => $documentId, 'num' => $num, 'exportType' => '1c']),
                ['target' => '_blank'])?>
        </li>
    </ul>
</div>

<?php
if (isset($model)) {
    if ($model->getValidStoredFileId()) {
        $paymentOrder = CyberXmlDocument::getTypeModel($model->getValidStoredFileId());
    } else if ($model->status == $model::STATUS_CREATING) {
        echo 'Документ еще не создан';

        return;
    } else {
        echo 'К сожалению, нет возможности отобразить документ данного типа';

        return;
    }
}
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

<?php if (isset($model)) : ?>

<?= DetailView::widget([
        'template' => '<tr><th class="col-sm-1">{label}</th><td'
            . ($model->businessStatus == 'RJCT' ? ' class="alert-danger"'
            : null)
            . '>{value}</td></tr>',
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'businessStatus',
                'value' => !empty($model->businessStatusTranslation)
                            ? ($model->businessStatusTranslation
                                . (!empty($model->businessStatusDescription)
                                    ? (': ' . $model->businessStatusDescription)
                                    : null
                                  )
                              )
                            : $model->businessStatusTranslation
            ]
        ]
    ])
?>
<?php endif ?>
<br/>
<br/>
<table class="table table-bordered" style="margin-bottom: -1px">
    <tr>
        <td width="30%" style="text-align: center"><strong><?=$paymentOrder->dateProcessingFormatted?></strong><br/></td>

        <?php if ($isPaymentRequirement) : ?>
            <td width="30%" style="text-align: center;">
                <strong>
                    <?php
                        if ($paymentOrder->acceptanceEndDate) {
                            echo $paymentOrder->acceptanceEndDateFormatted;
                        }
                    ?>
                </strong><br/>
            </td>
        <?php endif ?>

        <td width="30%" style="text-align: center;"><strong><?=$paymentOrder->dateDueFormatted?></strong></td>

        <?php if ($isPaymentRequirement) : ?>
            <td width="30%" style="text-align: center;">
                <strong>
                    <?php
                        if ($isPaymentRequirement) {
                            echo $paymentOrder->okud;
                        }
                    ?>
                </strong>
            </td>
        <?php endif ?>
    </tr>
    <tr>
        <td width="30%" style="text-align: center"><?= Yii::t('doc', 'Received by the payer\'s bank'); ?></td>

        <?php if ($isPaymentRequirement) : ?>
            <td width="30%" style="text-align: center"><?= Yii::t('doc', 'Оконч. срока акцепта'); ?></td>
        <?php endif ?>

        <td width="30%" style="text-align: center"><?= Yii::t('doc', 'Debited from the payer\'s account'); ?></td>

        <?php if ($isPaymentRequirement) : ?>
            <td width="30%" style="text-align: center"></td>
        <?php endif ?>
    </tr>
</table>

<table class="table table-bordered" style="margin-bottom: -1px">
    <?php if ($isPaymentRequirement) : ?>
        <tr>
            <td width="10%" style="text-align: center">
                Условие оплаты
            </td>
            <td width="50%">
                <?=$paymentOrder->paymentCondition1?>
            </td>
            <td width="10%" style="text-align: center">
                Срок для акцепта
            </td>
            <td width="10%" style="text-align: center">
                <?=$paymentOrder->acceptPeriod?>
            </td>
        </tr>
    <?php endif ?>
    <tr>
        <td width="10%" style="text-align: center">
            Сумма прописью
        </td>
        <td width="50%" colspan="3">
            <strong><?=ucfirst($paymentOrder->sumInWords)?></strong>
        </td>
    </tr>
</table>

<table class="table table-bordered" style="margin-bottom: -1px">
	<tr>
        <td width="30" rowspan="2"><h4><?=$paymentOrder->documentTypeExt . ' №' . $paymentOrder->number?></h4></td>
        <td width="10%" style="text-align: center;"><strong><?= date('d.m.Y', strtotime($paymentOrder->date)) ?></strong></td>
        <td width="10%" style="text-align: center;"><strong><?= Yii::t('doc', 'E-commerce'); ?></strong></td>
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
		<td>ИНН: <strong><?=$paymentOrder->payerInn?></strong></td>
		<td>КПП: <strong><?=$paymentOrder->payerKpp?></strong></td>
		<td>Сумма</td>
        <td colspan="3" class="text-left"><strong><?= Yii::$app->formatter->asDecimal($paymentOrder->sum, 2); ?></strong></td>
	</tr>
	<tr>
		<td colspan="2">
            <strong><?=$paymentOrder->payerName?></strong><br/>
			<small>Плательщик</small>
		</td>
		<td>Сч. №</td>
        <td colspan="3" class="text-left">
            <strong><?=$paymentOrder->payerCheckingAccount?></strong>
		</td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
            <strong><?=$paymentOrder->payerBank1?></strong><br/>
            <strong><?=$paymentOrder->payerBank2?></strong><br/>
			<small>Банк плательщика</small>
		</td>
		<td>БИК</td>
        <td colspan="3" class="text-left"><strong><?=$paymentOrder->payerBik?></strong></td>
	</tr>
	<tr>
		<td>Сч. №</td>
        <td colspan="3" class="text-left"><strong><?=$paymentOrder->payerCorrespondentAccount?></strong></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
            <strong><?=$paymentOrder->beneficiaryBank1?></strong><br/>
            <strong><?=$paymentOrder->beneficiaryBank2?></strong><br/>
			<small>Банк получателя</small>
		</td>
		<td>БИК</td>
        <td colspan="3" class="text-left"><strong><?=$paymentOrder->beneficiaryBik?></strong></td>
	</tr>
	<tr>
		<td>Сч. №</td>
        <td colspan="3" class="text-left"><strong><?=$paymentOrder->beneficiaryCorrespondentAccount?></strong></td>
	</tr>
	<tr>
		<td>ИНН: <strong><?=$paymentOrder->beneficiaryInn?></strong></td>
		<td>КПП: <strong><?=$paymentOrder->beneficiaryKpp?></strong></td>
		<td>Сч. №</td>
        <td colspan="3" rowspan="2" class="text-left"><strong><?=$paymentOrder->beneficiaryCheckingAccount?></strong></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="4">
            <strong><?=$paymentOrder->beneficiaryName?></strong><br/>
			<small>Получатель</small>
		</td>
	</tr>
	<tr>
		<td>Вид. оп.</td>
        <td class="text-left"><strong><?=$paymentOrder->payType?></strong></td>
		<td>Срок. плат.</td>
		<td><strong><?= $paymentOrder->maturity; ?></strong></td>
	</tr>
	<tr>
		<td>Наз. пл.</td>
		<td><strong><?= $paymentOrder->paymentOrderPaymentPurpose; ?></strong></td>
		<td>Очер. плат.</td>
		<td class="text-left"><strong><?=$paymentOrder->priority?></strong></td>
	</tr>
	<tr>
		<td>Код</td>
        <td class="text-left"><strong><?= $paymentOrder->code; ?></strong></td>
		<td>Рез. поле</td>
		<td><strong><?= $paymentOrder->backingField; ?></strong></td>
	</tr>
</table>
<table class="table table-bordered" style="margin-bottom: -1px">
	<tr>
        <td width="14.2%" class="text-right"><?= $paymentOrder->indicatorKbk ?: '0' ?><br/></td>
		<td width="14.2%" class="text-right"><?= $paymentOrder->okato ?: '0' ?></td>
		<td width="14.2%" class="text-right"><?= $paymentOrder->indicatorReason ?: '0' ?></td>
		<td width="14.2%" class="text-right"><?= $paymentOrder->indicatorPeriod ?: '0' ?></td>
		<td width="14.2%" class="text-right"><?= $paymentOrder->indicatorNumber ?: '0' ?></td>
		<td width="14.2%" class="text-right"><?= $paymentOrder->indicatorDate ?: '0' ?></td>
		<td width="14.2%" class="text-right"><?= $paymentOrder->indicatorType ?: '0' ?></td>
	</tr>
</table>
<table class="table table-bordered">
	<tr>
		<td>
            <strong><?=$paymentOrder->paymentPurpose?></strong><br/>
			<small>Назначение платежа</small>
		</td>
	</tr>
</table>

<?php if (isset($model) && !empty($model->dateProcessing)) : ?>
    <div style="float: right;">
        <table class="table-bordered" style="width: 300px;">
            <tr>
                <th style="text-align: center">
                    Отметка банка
                </th>
            </tr>
            <tr>
                <?php
                    $module = Yii::$app->getModule('edm');
                    echo $module->settings->bankName;
                ?>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <div style="margin: 5px; border: 1px solid #000000;">Поступило</div>
                </td>
            </tr>
        </table>
    </div>
<?php endif ?>

<?php if ($isPaymentRequirement) : ?>
    <div class="clearfix">
        <table class="table table-bordered" style="float: left; width: 85%">
            <tr>
                <td width="10%" style="text-align: center">
                    № ч. платежа
                </td>
                <td width="10%" style="text-align: center">
                    № плат. ордера
                </td>
                <td width="10%" style="text-align: center">
                    Дата плат. ордера
                </td>
                <td width="10%" style="text-align: center">
                    Сумма частичного платежа
                </td>
                <td width="10%" style="text-align: center">
                    Сумма остатка платежа
                </td>
                <td width="10%" style="text-align: center">
                    Подпись
                </td>
            </tr>

            <?php for ($i = 1; $i <= 6; $i++) : ?>
                <tr>
                    <td style="border-bottom: 0; border-top: 0"></td>
                    <td style="border-bottom: 0; border-top: 0"></td>
                    <td style="border-bottom: 0; border-top: 0"></td>
                    <td style="border-bottom: 0; border-top: 0"></td>
                    <td style="border-bottom: 0; border-top: 0"></td>
                    <td style="border-bottom: 0; border-top: 0"></td>
                </tr>
            <?php endfor ?>
        </table>
        <div style="float: right">
            <p style="margin-bottom: 35px;">Дата помещения в картотеку</p>
            <p>Отметки банка плательщика</p>
        </div>
    </div>
<?php endif ?>

<?php if (!empty($paymentOrder->dateDue)) : ?>
<center>
        <table>
            <tr>
                <td align="center" class="stamp">
                    <b><?= Html::encode($paymentOrder->payerBank1) ?><br/>
                        ПРОВЕДЕНО</b><br/>
                    <?= DateHelper::formatDate($paymentOrder->dateDue) ?>
                </td>
            </tr>
        </table>
</center>
<?php endif ?>
<?php
if (
    isset($model)
    && $signatures = $model->getSignatures(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER)
) {
    echo $this->render('@common/views/document/_signatures', ['signatures' => $signatures]);
}

$this->registerCss('
    .btn-statement-print {
        margin-right: 5px;
    }
');

// Печать выписки
$printUrl = Url::toRoute(['/edm/documents/print-statement-payment-order', 'id' => $documentId, 'num' => $num]);
$printBtn = '.btn-statement-print';

echo FastPrint::widget(
    [
        'printUrl' => $printUrl,
        'printBtn' => $printBtn
    ]
);

?>
