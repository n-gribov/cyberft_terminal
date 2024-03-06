<?php

use addons\edm\models\EdmPayerAccount;
use common\helpers\DateHelper;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyPaymentType $typeModel */
/** @var \common\document\Document $document */

/** @var \addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt $extModel */
$extModel = $document->extModel;

$edmPayerAccount = EdmPayerAccount::findOne(['number' => $typeModel->payerAccount]);
$bankName = $edmPayerAccount && $edmPayerAccount->bank
    ? $edmPayerAccount->bank->name
    : $typeModel->payerBankName;

// Список комиссий платежа
$commissions = [
    'OUR' => 'Плательщика (OUR)',
    'SHA' => 'Разделенная комиссия (SHA)',
    'BEN' => 'Получателя (BEN)'
];
?>
<div class="main container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="fcp-header">Валютный перевод</div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pull-left">
                <div class="block-date">
                    <div class="block-date-content">
                        <?= DateHelper::formatDate($extModel->dateProcessing) ?>
                    </div>
                    <div class="block-date-desc">
                        Поступ. в банк плат.
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <div class="block-date">
                    <div class="block-date-content">
                        <?= DateHelper::formatDate($extModel->dateDue) ?>
                    </div>
                    <div class="block-date-desc">
                        Списано со сч. плат.
                    </div>
                </div>
            </div>
        </div>
    </div>


    <table class="table-print table-info">
        <tr>
            <td colspan="3">Дата</td>
            <td>№ документа</td>
        </tr>
        <tr>

            <?php $timestamp = strtotime($typeModel->date); ?>

            <td><?=date('d', $timestamp)?></td>
            <td><?=date('m', $timestamp)?></td>
            <td><?=date('Y', $timestamp)?></td>
            <td><?=$typeModel->number?></td>
        </tr>
    </table>

    <div>
        Просим произвести списание по счету
        <div class="account-number">
            <?=$typeModel->payerAccount?>
        </div>
    </div>

    <table class="table-print table-payment">
        <tr>
            <td>Вид платежа</td>
            <td>Стандартный</td>
        </tr>
        <tr>
            <td>Комиссию и расходы по переводу отнести на:</td>
            <td>
                <?php foreach($commissions as $id => $commission) : ?>
                    <div class="fcp-print-commission-block">
                        <div class="fcp-print-commission-field">
                            <?php if ($id == $typeModel->commission) : ?>
                                <div class="line1"></div><div class="line2"></div>
                            <?php endif ?>
                        </div>
                        <?=$commission?>
                    </div>
                <?php endforeach ?>
            </td>
        </tr>
    </table>

    <table class="table-print table-payment-content table-payment-content-one">
        <tr>
            <td rowspan="3">32</td>
            <td>Наименование валюты</td>
            <td colspan="2"><?=$typeModel->currency?></td>
        </tr>
        <tr>
            <td>Сумма цифрами</td>
            <td colspan="2"><?=$typeModel->sum?></td>
        </tr>
        <tr>
            <td>Сумма прописью</td>

            <?php
                $formatSum = new NumberFormatter('en', NumberFormatter::SPELLOUT);
            ?>

            <td colspan="2"><?=$formatSum->format($typeModel->sum)?></td>
        </tr>

        <tr class="bold-top-border">
            <td rowspan="3">50</td>
            <td>Ordering customer</td>
            <td>Наименование (имя) *</td>
            <td><?=$typeModel->payerName?></td>
        </tr>
        <tr>
            <td>Плательщик</td>
            <td>Адрес, № дома, улица*</td>
            <td><?=$typeModel->payerAddress?></td>
        </tr>
        <tr>
            <td></td>
            <td>Город, штат, страна</td>
            <td><?=$typeModel->payerLocation?></td>
        </tr>

        <tr class="bold-top-border">
            <td rowspan="3">52</td>
            <td>Ordering institution</td>
            <td>Наименование (имя) *</td>
            <td><?=$typeModel->payerBankName?></td>
        </tr>
        <tr>
            <td>Банк плательщика</td>
            <td>Swift</td>
            <td><?=$typeModel->payerBank?></td>
        </tr>
        <tr>
            <td></td>
            <td>Адрес</td>
            <td><?=$typeModel->payerBankAddress?></td>
        </tr>

        <tr class="bold-top-border">
            <td rowspan="3">56</td>
            <td>Intermediary institution</td>
            <td>Swift *</td>
            <td><?=$typeModel->intermediaryBank?></td>
        </tr>
        <tr>
            <td>Банк-посредник</td>
            <td>№ счета *</td>
            <td><?= $typeModel->intermediaryBankAccount ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Наименование (имя) и адрес&nbsp;*</td>
            <td><?= $typeModel->intermediaryBankNameAndAddress ?></td>
        </tr>

        <tr class="bold-top-border">
            <td rowspan="3">57</td>
            <td>Account with institution</td>
            <td>Swift</td>
            <td><?= $typeModel->beneficiaryBank ?></td>
        </tr>
        <tr>
            <td>Банк получателя</td>
            <td>№ счета</td>
            <td><?= $typeModel->beneficiaryBankAccount ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Наименование (имя) и адрес&nbsp;*</td>
            <td><?= $typeModel->beneficiaryBankNameAndAddress ?></td>
        </tr>

        <tr class="bold-top-border">
            <td rowspan="2">59</td>
            <td>Beneficiary customer</td>
            <td>№ счета *</td>
            <td><?=$typeModel->beneficiaryAccount?></td>
        </tr>
        <tr>
            <td>Получатель</td>
            <td>Наименование и адрес&nbsp;*</td>
            <td><?= nl2br($typeModel->beneficiary) ?></td>
        </tr>

        <tr class="bold-top-border">
            <td rowspan="2">70</td>
            <td>Remittance information</td>
            <td rowspan="2">Назначение платежа и другие пояснения *</td>
            <td rowspan="2"><?=$typeModel->information?></td>
        </tr>
        <tr>
            <td>Информация получателя</td>
        </tr>

        <tr class="bold-top-border">
            <td rowspan="2">72</td>
            <td>Additional information</td>
            <td rowspan="2" colspan="2"><?= $typeModel->additionalInformation ?></td>
        </tr>
        <tr>
            <td>Дополнительная информация</td>
        </tr>
    </table>

    <?php if (!empty($extModel->dateDue)): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <div class="stamp-content">
                    <span class="header">Отметки банка</span>
                    <br>
                    <?= Html::encode($bankName) ?>
                    <br/>
                    ПРОВЕДЕНО
                    <br/>
                    <?= DateHelper::formatDate($extModel->dateDue) ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>

<?php if (count($signatures) > 0) : ?>
    <hr>
    <?= // Вывести страницу
        $this->render('@common/views/document/_signatures', ['signatures' => $signatures]) ?>
<?php endif ?>

<?php
    $this->registerCss(<<<CSS
        .table-print {
            border: 2px solid #000;
        }

        .table-print td {
            border: 1px solid #000;
        }

        .table-info {
            width: 400px;
            margin-bottom: 5px;
        }

        .account-number {
            border: 2px solid #000;
            padding-left: 10px;
            padding-right: 10px;
            margin-bottom: 5px;
            width: 300px;
        }

        .table-info td {
            padding: 1px 2px;
            text-align: center;
        }

        .table-payment {
            width: 100%;
            margin-bottom: 5px;
        }

        .table-payment td {
            padding: 1px 2px;
        }

        .table-payment td:first-child {
            width: 20%;
        }

        .table-payment-content {
            width: 100%;
        }

        .table-payment-content td {
            padding: 1px 2px;
        }

        .table-payment-content td:nth-child(2) {
            width: 18%;
        }

        .stamp-content {
            border: 1px solid #000;
            font-size: 10pt;
            font-weight: bold;
            width: 300px;
            height: 80px;
            text-align: center;
            margin-bottom: 5px;
        }

        .stamp-content .header {
            font-size: 8pt;
            font-weight: normal;
        }

        .stamp-content-container {
            border: 1px solid #000;
            width: 300px;
            height: 65px;
            padding-left: 5px;
            padding-right: 5px;
            line-height: 15px;
        }

        .block-date {
            margin-bottom: 15px;
            width: 250px;
            text-align: center;
        }

        .block-date-content {
            border-bottom: 2px solid #000;
            min-height: 20px;
        }

        @media print{
            .no-print, .no-print *
            {
                display: none !important;
            }
        }

        .main {
            background:white;
            color:black;
            font-family:arial;
            font-size: 8pt;
        }
        .startBlock {
            margin-top: 2px;
        }

        .underlineBlock {
            margin-bottom: -5px;
            border-bottom: 1px solid black;
        }

        .row {
            padding-top: 8px;
        }

        .col-xs-auto {
            width: auto;
            padding: 0px;
        }

        .bold-top-border {
            border-top: 2px solid #000 !important;
        }

        .fcp-header {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .table-payment-content-one {
            margin-bottom: 30px;
        }
    CSS);
