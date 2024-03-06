<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \addons\edm\models\BaseVTBDocument\BaseVTBDocumentType $typeModel */
/** @var \addons\edm\models\DictOrganization $senderOrganization */
/** @var \common\document\DocumentStatusReportsData $statusReportsData */

/** @var \common\models\vtbxml\documents\CurrDealInquiry181i $bsDocument */
$bsDocument = $typeModel->document;

$dateFormat = 'php:d.m.Y';
$dateTimeFormat = 'php:d.m.Y H:i';

Yii::$app->formatter->nullDisplay = '';

?>

<table>
    <tr>
        <td class="no-wrap" style="width: 240px;">Наименование уполномоченного банка</td>
        <td class="bordered"><?= Html::encode($bsDocument->CUSTOMERBANKNAME) ?></td>
    </tr>
    <tr>
        <td>Наименование резидента</td>
        <td class="bordered"><?= Html::encode($senderOrganization->name) ?></td>
    </tr>
</table>

<h1>
    СВЕДЕНИЯ О ВАЛЮТНОЙ ОПЕРАЦИИ<br>
    Номер <?= Html::encode($bsDocument->DOCUMENTNUMBER) ?> от <?= Yii::$app->formatter->asDate($bsDocument->DOCUMENTDATE, $dateFormat) ?>
</h1>

<table>
    <tr>
        <td class="no-wrap" style="width: 240px;">Номер счета резидента в уполномоченном банке</td>
        <td class="bordered" colspan="3"><?= Html::encode($bsDocument->ACCOUNT) ?></td>
    </tr>
    <tr>
        <td>Код страны банка-нерезидента</td>
        <td class="bordered"><?= Html::encode($bsDocument->BANKCOUNTRYCODE) ?></td>
        <td class="text-right">Признак корректировки</td>
        <td class="bordered"></td>
    </tr>
</table>

<table class="bordered text-center">
    <tr>
        <th rowspan="2">№ <span class="no-wrap">п/п</span></th>
        <th rowspan="2">Уведомление, распоряжение, расчетный или иной документ</th>
        <th rowspan="2">Дата операции</th>
        <th rowspan="2">Признак платежа</th>
        <th rowspan="2">Код вида валютной операции</th>
        <th colspan="2">Сумма операции</th>
        <th rowspan="2">Уникальный номер контракта (кредитного договора) или номер и (или) дата договора (контракта)</th>
        <th colspan="2">Сумма операции в единицах валюты контракта (кредитного договора)</th>
        <th rowspan="2">Срок возврата аванса</th>
        <th rowspan="2">Ожидаемый срок</th>
        <th rowspan="2">Признак представления документов, связанных с проведением операций</th>
    </tr>
    <tr>
        <th>код валюты</th>
        <th>сумма</th>
        <th>код валюты</th>
        <th>сумма</th>
    </tr>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8</th>
        <th>9</th>
        <th>10</th>
        <th>11</th>
        <th>12</th>
        <th>13</th>
    </tr>
    <?php foreach ($bsDocument->DEALINFOBLOB as $i => $dealInfo): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= Html::encode($dealInfo->DOCUMENTNUMBER) ?></td>
            <td><?= Yii::$app->formatter->asDate($dealInfo->OPERDATE, $dateFormat) ?></td>
            <td><?= Html::encode($dealInfo->PAYMENTDIRECTION) ?></td>
            <td><?= Html::encode($dealInfo->OPERCODE) ?></td>
            <td><?= Html::encode($dealInfo->PAYMENTCURRCODE) ?></td>
            <td><?= Html::encode($dealInfo->PAYMENTAMOUNT) ?></td>
            <td><?= Html::encode($dealInfo->PSNUMBER) ?></td>
            <td><?= Html::encode($dealInfo->CURRCODEPS) ?></td>
            <td><?= Html::encode($dealInfo->AMOUNTPSCURRENCY) ?></td>
            <td><?= Yii::$app->formatter->asDate($dealInfo->PREPAYRETURN, $dateFormat) ?></td>
            <td><?= Yii::$app->formatter->asDate($dealInfo->EXPECTDATE, $dateFormat) ?></td>
            <td></td>
        </tr>
    <?php endforeach; ?>
</table>

<p>Примечание</p>
<table class="bordered">
    <tr>
        <th style="width: 100px;">№ строки</th>
        <th>Содержание</th>
    </tr>
    <?php foreach ($bsDocument->DEALINFOBLOB as $index => $dealInfo): ?>
        <?php if (!(empty($dealInfo->REMARK))): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= Html::encode($dealInfo->REMARK) ?></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>

<?= $this->render(
    '_bottom',
    [
        'typeModel' => $typeModel,
        'bankName' => $bsDocument->CUSTOMERBANKNAME,
        'statusReportsData' => $statusReportsData,
        'stampStatus' => 'ACCP',
    ]
) ?>

<style>
    @media print {
        @page {
            size: landscape;
            margin: 20mm 10mm 10mm 10mm;
        }
    }
    body {
        padding: 0;
    }
    #forPrintPreview {
        padding: 0;
    }
    h1 {
        font-size: 16px;
        line-height: 1.5;
        text-align: center;
        font-weight: bold;
    }
    td, th {
        padding: 5px;
    }
    th {
        font-weight: normal;
        text-align: center;
    }
    table {
        margin-bottom: 10px;
        margin-top: 10px;
        width: 100%;
    }
    .bordered,
    table.bordered th,
    table.bordered td {
        border: 1px solid black;
    }
    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .no-wrap {
        white-space: nowrap;
    }
</style>
