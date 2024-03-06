<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \addons\edm\models\BaseVTBDocument\BaseVTBDocumentType $typeModel */
/** @var \addons\edm\models\DictOrganization $senderOrganization */
/** @var \common\document\DocumentStatusReportsData $statusReportsData */

/** @var \common\models\vtbxml\documents\ConfDocInquiry138I $bsDocument */
$bsDocument = $typeModel->document;

$formatContractNumber = function (string $number): string {
    $number = str_replace('/', '', $number);
    $number = str_pad($number, 18);
    return substr($number, 0, 8)
        . '/'
        . substr($number, 8, 4)
        . '/'
        . substr($number, 12, 4)
        . '/'
        . substr($number, 16, 1)
        . '/'
        . substr($number, 17, 1);
};

$dateFormat = 'php:d.m.Y';
$dateTimeFormat = 'php:d.m.Y H:i';

Yii::$app->formatter->nullDisplay = '';
?>
<p>Код формы по ОКУД 0406010</p>
<table>
    <tr>
        <td>Наименование банка УК</td>
        <td class="bordered"><?= Html::encode($bsDocument->CUSTOMERBANKNAME) ?></td>
    </tr>
    <tr>
        <td>Наименование резидента</td>
        <td class="bordered"><?= Html::encode($senderOrganization->name) ?></td>
    </tr>
</table>

<h1>СПРАВКА О ПОДТВЕРЖДАЮЩИХ ДОКУМЕНТАХ</h1>
<p class="text-center"><strong>от <?= Yii::$app->formatter->asDate($bsDocument->DOCUMENTDATE, $dateFormat) ?></strong></p>

<table>
    <tr>
        <th class="text-right bold">Уникальный номер контракта (кредитного договора)</th>
        <?php foreach (str_split($formatContractNumber($bsDocument->PSNUMBER)) as $char) : ?>
            <td class="text-center bordered"><?= $char ?></td>
        <?php endforeach ?>
    </tr>
</table>

<table class="bordered text-center">
    <tr>
        <th rowspan="2">№ п/п</th>
        <th colspan="2">Подтверждающий документ</th>
        <th rowspan="2">Код вида подтверждающего документа</th>
        <th colspan="4">Сумма по подтверждающему документу</th>
        <th rowspan="2">Признак поставки</th>
        <th rowspan="2">Ожидаемый срок репатриации иностранной валюты и (или) валюты Российской Федерации</th>
        <th rowspan="2">Код страны грузоотправителя (грузополучателя)</th>
        <th rowspan="2">Признак корректировки</th>
    </tr>
    <tr>
        <th>№</th>
        <th>дата</th>
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
    </tr>
    <?php foreach ($bsDocument->CONFDOCPSBLOB as $dealInfo) : ?>
        <tr>
            <td><?= Html::encode($dealInfo->NUM) ?></td>
            <td><?= Html::encode($dealInfo->DOCUMENTNUMBER) ?></td>
            <td><?= Yii::$app->formatter->asDate($dealInfo->DOCDATE, $dateFormat) ?></td>
            <td><?= Html::encode($dealInfo->DOCCODE) ?></td>
            <td><?= Html::encode($dealInfo->CURRCODE1) ?></td>
            <td><?= Html::encode($dealInfo->AMOUNTCURRENCY1) ?></td>
            <td><?= Html::encode($dealInfo->CURRCODE2) ?></td>
            <td><?= Html::encode($dealInfo->AMOUNTCURRENCY2) ?></td>
            <td><?= Html::encode($dealInfo->FDELIVERY) ?></td>
            <td><?= Yii::$app->formatter->asDate($dealInfo->EXPECTDATE, $dateFormat) ?></td>
            <td><?= Html::encode($dealInfo->COUNTRYCODE) ?></td>
            <td><?= Yii::$app->formatter->asDate($dealInfo->CORRECTION, $dateFormat) ?></td>
        </tr>
    <?php endforeach ?>
</table>

<p><strong>Примечание</strong></p>
<table class="bordered">
    <tr>
        <th>№ строки</th>
        <th>Содержание</th>
    </tr>
    <?php foreach ($bsDocument->CONFDOCPSBLOB as $index => $dealInfo) : ?>
        <?php if (!(empty($dealInfo->ADDINFO))) : ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= Html::encode($dealInfo->ADDINFO) ?></td>
            </tr>
        <?php endif ?>
    <?php endforeach ?>
</table>

<p>Информация банка УК</p>

<?= // Вывести колонтитул
    $this->render(
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
        font-size: 20px;
        text-align: center;
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
    .bold {
        font-weight: bold;
    }
</style>
