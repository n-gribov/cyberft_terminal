<?php

use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use common\helpers\DateHelper;
use addons\edm\helpers\EdmHelper;
use common\modules\certManager\models\Cert;

/** @var $model Document */

if (isset($model)) {
    $checkboxJS = <<<JS
        $('#showSignature').click(function() {
            if ($(this).is(':checked')) {
                $('#signList').show();
            } else {
                $('#signList').hide();
            }
        });
    JS;

    $this->registerJs($checkboxJS, View::POS_READY);
    if ($model instanceof addons\edm\models\PaymentOrder\PaymentOrderType) {
        $paymentOrder = $model;
        $paymentOrder->unsetParticipantsNames();
    } else {
        if ($model->getValidStoredFileId()) {
            $paymentOrder = CyberXmlDocument::getTypeModel($model->getValidStoredFileId());
        } else if ($model->status == $model::STATUS_CREATING) {
            echo 'Документ еще не создан';
            return;
        } else {
            echo 'Нет возможности отобразить документ данного типа';
            return;
        }
    }
}
/* @var $this View */
/* @var $form ActiveForm */
/* @var $model Document */
?>
<?php if (isset($model)) : ?>
    <div class="form-group no-print">
        <?= Html::checkbox('showSignature', true, ['id' => 'showSignature']); ?>
        <?= Yii::t('doc', 'Show details of digital signature'); ?>
    </div>
    <hr>
<?php endif ?>

<?php
    $isPaymentRequirement = $paymentOrder->isRequirement();
?>

<div class="print-info clearfix">
    <div class="print-info-column pull-left">
        <?= Yii::t('app', 'Received by CyberFT') ?>
    </div>

    <?php if (isset($data['businessStatus'])) : ?>
        <div class="print-info-column pull-right">
            <?= Yii::t('edm', 'Business status') ?>:
            <?php
                $businessStatus = $data['businessStatus']['statusTranslation'];

                if (!empty($data['businessStatus']['description'])) {
                    $businessStatus .= ': ' . $data['businessStatus']['description'];
                }

                if ($data['businessStatus']['status'] == 'RJCT' && !empty($data['businessStatus']['reason'])) {
                    $businessStatus .= ' (' . $data['businessStatus']['reason'] . ')';
                }

                echo $businessStatus;
            ?>
        </div>
    <?php endif ?>
</div>
<hr>
<table>
    <tr>
        <td width="10%" style="text-align:center;"><b><?= (!empty($paymentOrder->dateProcessingFormatted)) ? $paymentOrder->dateProcessingFormatted : ''; ?></b></td>
        <td width="5%">&nbsp;</td>

        <?php if ($isPaymentRequirement) {?>
            <td width="10%" style="text-align:center;">
                <b>
                    <?php
                    if ($paymentOrder->acceptanceEndDate) {
                        echo DateHelper::formatDate($paymentOrder->acceptanceEndDate);
                    }
                    ?>
                </b>
            </td>
            <td width="5%">&nbsp;</td>
        <?php } ?>

        <td width="10%" style="text-align:center;">
            <b>
                <?= (!empty($paymentOrder->dateDue)) ? DateHelper::formatDate($paymentOrder->dateDue) : '' ?>
            </b>
        </td>
        <?php if ($isPaymentRequirement) {?>
            <td width="35%">&nbsp;</td>
        <?php } else {?>
            <td width="25%">&nbsp;</td>
        <?php } ?>
        <td width="10%" style="border:1px solid black; text-align: center; font-weight: bold;">
            <?=$paymentOrder->okud?>
        </td>
    </tr>
    <tr>
        <?php
            $tableWidth = $isPaymentRequirement ? 15 : 30;
        ?>
        <td width="<?=$tableWidth?>%" style="text-align:center; border-top:1px solid black"><?= Yii::t('doc', 'Received by the payer\'s bank'); ?></td>
        <td>&nbsp;</td>
        <?php if ($isPaymentRequirement) {?>
            <td width="<?=$tableWidth?>%" style="text-align:center; border-top:1px solid black">Оконч. срока акцепта</td>
            <td>&nbsp;</td>
        <?php } ?>
        <td width="<?=$tableWidth?>%" style="text-align:center; border-top:1px solid black"><?= Yii::t('doc', 'Debited from the payer\'s account'); ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>

<?php if ($isPaymentRequirement) : ?>
    <div style="padding-top: 20px; background-color: #FFFFFF; padding-bottom: 20px;">
        <table>
            <tr>
                <td width="10%" style="text-align:center; border:1px solid black; padding: 10px;">
                    Условие оплаты
                </td>
                <td width="50%" style="text-align:left; border:1px solid black; padding: 10px;">
                    <?=$paymentOrder->paymentCondition1?>
                </td>
                <td width="10%" style="text-align:center; border:1px solid black; padding: 10px;">
                    Срок для акцепта
                </td>
                <td width="5%" style="text-align:center; border:1px solid black; padding: 10px;">
                    <?=$paymentOrder->acceptPeriod?>
                </td>
            </tr>
            <tr>
                <td width="10%" style="text-align:center; border:1px solid black; padding: 10px;">
                    Сумма прописью
                </td>
                <td width="50%" colspan="3" style="text-align:left; border:1px solid black; padding: 10px;">
                    <b><?=$paymentOrder->sumInWords?></b>
                </td>
            </tr>
        </table>
    </div>
<?php endif ?>
<table>
    <tr>
        <td width="65%"><span style="font-family:arial; font-weight:bold; font-size:150%">
            <span class="text-decoration: uppercase;"><?=$paymentOrder->documentTypeExt?> №</span><?=$paymentOrder->number?>
            </span></td>
        <td>
            <table>
                <tr align="center">
                    <td class="text-center">
                        <b>
                            <?=(!empty($paymentOrder->date)) ? DateHelper::formatDate($paymentOrder->date) : '' ?>
                        </b>
                    </td>
                    <td width="2%">&nbsp;&nbsp;&nbsp;</td>
                    <td class="text-center"><b><?= Yii::t('doc','E-commerce'); ?></b></td>
                    <td width="2%">&nbsp;</td>
                    <td style="width:2em;height:2em;border:1px solid black"><?=$paymentOrder->senderStatus?></td>
                </tr>
                <tr align="center">
                    <td class="text-center" style="border-top:1px solid black"><small>Дата</small></td>
                    <td>&nbsp;</td>
                    <td class="text-center" style="border-top:1px solid black"><small>Вид платежа</small></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="inner">
    <tr valign="top">
        <td style="border-right: 1px solid black; padding-right:2em">Сумма<br/>прописью</td>
        <td width="100%"><b><?=$paymentOrder->sumInWords?></b></td>
    </tr>
</table>
<table class="inner" border="0" cellspacing="0" cellpadding="0" style="margin:0;padding:0">
    <tr valign="top">
        <td style="border-top: 1px solid black;">
            ИНН <b><?=$paymentOrder->payerInn; ?></b>
        </td>
        <td style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;">
            КПП <b><?=$paymentOrder->payerKpp; ?></b>
        </td>
        <td style="width: 100px; border-top: 1px solid black;">
            Сумма
        </td>
        <td style="border-left: 1px solid black; width: 160px; border-top: 1px solid black;" class="text-left">
            <b><?= Yii::$app->formatter->asDecimal($paymentOrder->sum, 2); ?></b>
        </td>
    </tr>
    <tr valign="top">
        <td style="padding-left: 0; border-top: 1px solid black; border-right: 1px solid black;" colspan="2">
            <table style="height: 100%;">
                <tr>
                    <td>
                        <div style="min-height:0em">
                            <b><?= $paymentOrder->payerName; ?></b><br/>
                        </div>
                    </td>
                </tr>
                <tr valign="bottom">
                    <td>
                        <small>Плательщик</small>
                    </td>
                </tr>
            </table>
        </td>
        <td style="border-top: 1px solid black; width: 100px;">
            Сч. №
        </td>
        <td style="border-top: 1px solid black; border-left: 1px solid black; width: 160px;" class="text-left">
            <b><?= $paymentOrder->payerCheckingAccount; ?></b>
        </td>
    </tr>
    <tr valign="top">
        <td style="padding-left: 0; border-top: 1px solid black; border-right: 1px solid black;" colspan="2">
            <table style="height: 100%;">
                <tr>
                    <td>
                        <div style="min-height:0em">
                            <b><?= $paymentOrder->payerBank1; ?></b><br/>
                            <b><?= $paymentOrder->payerBank2; ?></b><br/>
                        </div>
                    </td>
                </tr>
                <tr valign="bottom">
                    <td>
                        <small>Банк плательщика</small>
                    </td>
                </tr>
            </table>
        </td>
        <td style="border-top: 1px solid black; width: 100px;">
            <table>
                <tr>
                    <td>
                        БИК
                    </td>
                </tr>
                <tr>
                    <td>
                        Сч. №
                    </td>
                </tr>
            </table>
        </td>
        <td style="border-left: 1px solid black; width: 160px;" class="text-left">
            <table style="padding-top: 2px;">
                <tr>
                    <td class="text-left">
                        <b><?= $paymentOrder->payerBik?></b>
                    </td>
                </tr>
                <tr>
                    <td class="text-left">
                        <b><?=$paymentOrder->payerCorrespondentAccount?></b>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr valign="top">
        <td style="padding-left: 0; border-top: 1px solid black; border-right: 1px solid black;" colspan="2">
            <table style="height: 100%;">
                <tr>
                    <td>
                        <div style="min-height:0em">
                            <b><?= $paymentOrder->beneficiaryBank1; ?></b><br/>
                            <b><?= $paymentOrder->beneficiaryBank2; ?></b><br/>
                        </div>
                    </td>
                </tr>
                <tr valign="bottom">
                    <td>
                        <small>Банк получателя</small>
                    </td>
                </tr>
            </table>
        </td>
        <td style="border-top: 1px solid black; width: 100px;">
            <table>
                <tr>
                    <td>
                        БИК
                    </td>
                </tr>
                <tr>
                    <td>
                        Сч. №
                    </td>
                </tr>
            </table>
        </td>
        <td style="border-top: 1px solid black; border-left: 1px solid black; width: 160px;" class="text-left">
            <table>
                <tr>
                    <td class="text-left">
                        <b><?= $paymentOrder->beneficiaryBik; ?></b>
                    </td>
                </tr>
                <tr>
                    <td class="text-left">
                        <b><?= $paymentOrder->beneficiaryCorrespondentAccount; ?></b>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr valign="top">
        <td style="border-top: 1px solid black;">
            ИНН <b><?= $paymentOrder->beneficiaryInn; ?></b>
        </td>
        <td style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;">
            КПП <b><?= $paymentOrder->beneficiaryKpp; ?></b>
        </td>
        <td style="width: 100px; border-top: 1px solid black;">
            Сч. №
        </td>
        <td style="border-left: 1px solid black; width: 160px;" class="text-left">
            <b><?= $paymentOrder->beneficiaryCheckingAccount; ?></b>
        </td>
    </tr>
</table>

<?php if (!empty($savePdf)) : ?>

<table class="inner" border="0" cellspacing="0" cellpadding="0" style="margin:0;padding:0">
    <tr valign="top">
        <td class="table-bottom-width" style="padding-left: 0; border-top: 1px solid black; border-right: 1px solid black; height: 100%;" rowspan="3">
            <table style="height: 100%;">
                <tr>
                    <td>
                        <div style="min-height:0em">
                            <b><?= $paymentOrder->beneficiaryName; ?></b><br/>
                        </div>
                    </td>
                </tr>
                <tr valign="bottom">
                    <td>
                        <small>Получатель</small>
                    </td>
                </tr>
            </table>
        </td>
        <td style="border-top: 1px solid black; border-right: 1px solid black; width: 100px;">
            Вид. оп.
        </td>
        <td style="border-top: 1px solid black;" class="text-left">
            <b><?= $paymentOrder->payType; ?></b>
        </td>
        <td style="border: 1px solid black; border-bottom: 0; width: 100px;">
            Срок. плат.
        </td>
        <td style="border-top: 1px solid black;">
            <b><?= $paymentOrder->maturity; ?></b>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black; border-right: 1px solid black;">
            Наз. пл.
        </td>
        <td>
            <b><?= $paymentOrder->paymentOrderPaymentPurpose; ?></b>
        </td>
        <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black;">
            Очер. плат.
        </td>
        <td class="text-left">
            <b><?= $paymentOrder->priority; ?></b>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black; border-right: 1px solid black;">
            Код
        </td>
        <td class="text-left">
            <b><?= $paymentOrder->code; ?></b>
        </td>
        <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black;">
            Рез. поле
        </td>
        <td>
            <b><?= $paymentOrder->backingField; ?></b>
        </td>
    </tr>
</table>

<?php else : ?>

<table class="inner" border="0" cellspacing="0" cellpadding="0" style="margin:0;padding:0">
    <tr valign="top">
        <td class="table-bottom-width" style="padding-left: 0; border-top: 1px solid black; border-right: 1px solid black; height: 100%;" rowspan="3">
            <table style="height: 100%;">
                <tr>
                    <td>
                        <div style="min-height:0em">
                            <b><?= $paymentOrder->beneficiaryName; ?></b><br/>
                        </div>
                    </td>
                </tr>
                <tr valign="bottom">
                    <td>
                        <small>Получатель</small>
                    </td>
                </tr>
            </table>
        </td>
        <td class="td-width" style="border-top: 1px solid black; border-right: 1px solid black; width: 100px;">
            Вид. оп.
        </td>
        <td style="border-top: 1px solid black; width: 30px;" class="text-left test2">
            <b><?= $paymentOrder->payType; ?></b>
        </td>
        <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; width: 100px;">
            Срок. плат.
        </td>
        <td style="border-top: 1px solid black; width: 30px;">
            <b><?= $paymentOrder->maturity; ?></b>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black; border-right: 1px solid black; width: 100px;">
            Наз. пл.
        </td>
        <td style="width: 30px;">
            <b><?= $paymentOrder->paymentOrderPaymentPurpose; ?></b>
        </td>
        <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; width: 100px;">
            Очер. плат.
        </td>
        <td style="width: 30px;" class="text-left">
            <b><?= $paymentOrder->priority; ?></b>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid black; border-right: 1px solid black; width: 100px;">
            Код
        </td>
        <td style="width: 30px;" class="text-left">
            <b><?= $paymentOrder->code; ?></b>
        </td>
        <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; width: 100px;">
            Рез. поле
        </td>
        <td style="width: 30px;">
            <b><?= $paymentOrder->backingField; ?></b>
        </td>
    </tr>
</table>

<?php endif ?>

<table class="inner budget-table" style="min-height: 2em;">
    <tr>
        <td style="border: 1px solid black; border-left: 0; text-align: right; " width="14.2%">
            <b><?= $paymentOrder->indicatorKbk ?: '0' ?></b>
        </td>
        <td style="border: 1px solid black; border-left: 0; text-align: right;" width="14.2%">
            <b><?= $paymentOrder->okato ?: '0' ?></b>
        </td>
        <td style="border: 1px solid black; border-left: 0; text-align: right;" width="14.2%">
            <b><?= $paymentOrder->indicatorReason ?: '0' ?></b>
        </td>
        <td style="border: 1px solid black; border-left: 0; text-align: right;" width="14.2%">
            <b><?= $paymentOrder->indicatorPeriod ?: '0' ?></b>
        </td>
        <td style="border: 1px solid black; border-left: 0; text-align: right;" width="14.2%">
            <b><?= $paymentOrder->indicatorNumber ?: '0' ?></b>
        </td>
        <td style="border: 1px solid black; border-left: 0; text-align: right;" width="14.2%">
            <b><?= $paymentOrder->indicatorDate ?: '0' ?></b>
        </td>
        <td style="border: 1px solid black; border-left: 0; border-right: 0; text-align: right;" width="14.2%">
            <b><?= $paymentOrder->indicatorType ?: '0' ?></b>
        </td>
    </tr>
</table>
<table class="inner">
    <tr style="height:100%">
        <td>
            <b><?=$paymentOrder->paymentPurpose?></b>
        </td>
    </tr>
    <tr valign="bottom" style="border-bottom:1px solid black">
        <td><small>Назначение платежа</small></td>
    </tr>
</table>

<?php if (!empty($savePdf)) : ?>
    <table class="stamp-table">
        <tr style="height:8em" align="center">
            <td width="23%" class="text-center">М.П.</td>
            <td width="43%" class="text-center" style="padding-right: 30px;">
                <table style="height:100%">
                    <tr><td valign="top" align="center"><small>Подписи</small></td></tr>
                    <tr><td style="border-top:1px solid black; border-bottom:1px solid black">&nbsp;</td></tr>
                </table>
            </td>
            <td valign="top" width="11%" style="border: 1px solid #000" class="text-center">Отметки банка<br/><br/>
                <?php if (!empty($paymentOrder->dateDue)) :?>
            <p><div class="col-lg-4 col-lg-offset-4 stamp">
                <b><?= Html::encode($paymentOrder->payerBank1) ?><br/>
                    ПРОВЕДЕНО</b><br/>
                <?= DateHelper::formatDate($paymentOrder->dateDue) ?>
            </div></p>
            <?php endif ?>
            </td>
        </tr>
    </table>
<?php else : ?>
    <table class="stamp-table">
        <tr style="height:8em" align="center">
            <td width="33%">М.П.</td>
            <td width="33%">
                <table style="height:100%">
                    <tr><td valign="top" align="center"><small>Подписи</small></td></tr>
                    <tr><td style="border-top:1px solid black; border-bottom:1px solid black">&nbsp;</td></tr>
                </table>
            </td>
            <td valign="top">Отметки банка<br/><br/>
                <?php if (!empty($paymentOrder->dateDue)) :?>
            <p><div class="col-lg-4 col-lg-offset-4 stamp">
                <b><?= Html::encode($paymentOrder->payerBank1) ?><br/>
                    ПРОВЕДЕНО</b><br/>
                <?= DateHelper::formatDate($paymentOrder->dateDue) ?>
            </div></p>
            <?php endif ?>
            </td>
        </tr>
    </table>
<?php endif ?>


<?php if ($isPaymentRequirement) : ?>
    <div class="clearfix">
        <table class="table table-bordered" style="float: left; width: 90%;">
            <tr>
                <td width="10%" style="text-align: center; border:1px solid black;">
                    № ч. платежа
                </td>
                <td width="10%" style="text-align: center; border:1px solid black;">
                    № плат. ордера
                </td>
                <td width="10%" style="text-align: center; border:1px solid black;">
                    Дата плат. ордера
                </td>
                <td width="10%" style="text-align: center; border:1px solid black;">
                    Сумма частичного платежа
                </td>
                <td width="10%" style="text-align: center; border:1px solid black;">
                    Сумма остатка платежа
                </td>
                <td width="10%" style="text-align: center; border:1px solid black;">
                    Подпись
                </td>
            </tr>

            <?php for ($i = 1; $i <= 6; $i++) : ?>
                <tr>
                    <td style="border:1px solid black; border-bottom: 0; border-top: 0;"></td>
                    <td style="border:1px solid black; border-bottom: 0; border-top: 0;"></td>
                    <td style="border:1px solid black; border-bottom: 0; border-top: 0;"></td>
                    <td style="border:1px solid black; border-bottom: 0; border-top: 0;"></td>
                    <td style="border:1px solid black; border-bottom: 0; border-top: 0;"></td>
                    <td style="border:1px solid black; border-bottom: 0; border-top: 0;"></td>
                </tr>
            <?php endfor ?>
        </table>
        <div style="float: right; height: 132px; background-color: #fff">
            <p style="margin-bottom: 35px;">Дата помещения в картотеку</p>
            <p>Отметки банка плательщика</p>
        </div>
    </div>
<?php endif ?>
<?php
    // Вывод подписей только для реестра платежных поручений
    $signatures = [];

    if (isset($data['paymentOrderModel']) && !empty($data['paymentOrderModel']->registerId)) {
        $signatures = EdmHelper::getPaymentRegisterSignaturesList(
            $data['paymentOrderModel']->registerId, Cert::ROLE_SIGNER
        );
    }

    if (count($signatures) > 0) : ?>
        <hr>
        <?= // Вывести блок подписей
            $this->render('@common/views/document/_signatures', ['signatures' => $signatures]) ?>
    <?php endif ?>
<?php if ($paymentOrder->swiftMessage) : ?>
    <div style="page-break-before: always; font-size: 150%">
        <p>
            <strong><?= Yii::t('edm', 'Attached SWIFT-document') ?></strong>
        <p><?= nl2br(Html::encode($paymentOrder->swiftMessage)) ?></p>
        </p>
    </div>
<?php endif ?>

<?php
    $this->registerCss(<<<CSS
        @media print {
            .no-print, .no-print * {
                display: none !important;
            }

            .stamp {
                margin: 0 auto;
                margin-top: -15px;
                width: 64%;
            }

            @page {
                size: portrait;
            }
        }

        @media print and (orientation:landscape) {
            body {
                zoom: 90%;
            }
        }

        table {
            padding:0;
            width:100%;
            margin:0;
            border-collapse:collapse;
        }

        table td {
            background:white;
            color:black;
            font-family:arial;
        }

        table.inner td {
            padding: 0.3em;
        }

        .static-header {
            width: 100%;
        }
    CSS);
