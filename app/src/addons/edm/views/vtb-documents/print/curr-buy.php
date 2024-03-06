<?php

use addons\edm\models\DictBank;
use addons\edm\models\DictCurrency;
use addons\edm\models\EdmPayerAccount;
use common\helpers\NumericHelper;
use yii\helpers\Html;

/** @var \addons\edm\models\BaseVTBDocument\BaseVTBDocumentType $typeModel */
/** @var \addons\edm\models\DictOrganization $senderOrganization */
/** @var \common\document\DocumentStatusReportsData $statusReportsData */

/** @var \common\models\vtbxml\documents\CurrBuy $bsDocument */
$bsDocument = $typeModel->document;

$dateFormat = 'php:d.m.Y';
$dateTimeFormat = 'php:d.m.Y H:i';

$checkBox = function (bool $isChecked) {
    $iconName = $isChecked ? 'check' : 'unchecked';
    return Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
};

$join = function ($glue, $values) {
    $nonEmptyValues = array_filter($values, function ($value) {
        return $value !== null && $value !== '';
    });
    $encodedValues = array_map('yii\helpers\Html::encode', $nonEmptyValues);
    return implode($glue, $encodedValues);
};

$supplyConditions = [
    1 => 'сроком «сегодня»',
    2 => 'сроком «завтра»',
    3 => 'сроком «послезавтра»',
];

$debitCurrencyCode = DictCurrency::getAlphaCodeByNumericCode($bsDocument->CURRCODEDEBET);
$creditCurrencyCode = DictCurrency::getAlphaCodeByNumericCode($bsDocument->CURRCODECREDIT);

$creditBank = DictBank::findOne([
    'bik' => EdmPayerAccount::find()->where(['number' => $bsDocument->ACCOUNTCREDIT])->select('bankBik')
]);

Yii::$app->formatter->nullDisplay = '';

?>

<h1>
    <em>Поручение № <?= Html::encode($bsDocument->DOCUMENTNUMBER) ?></em><br>
    от <?= Yii::$app->formatter->asDate($bsDocument->DOCUMENTDATE, '«d» MMMM y') ?><br>
    на покупку иностранной валюты за валюту Российской Федерации
</h1>

<p>
    Наименование Клиента<br>
    <?= $join(', ', [$senderOrganization->name, $bsDocument->CUSTOMEROKPO]) ?>
    <small>
        (наименование и код ОКПО организации/Ф.И.О. и документ,<br>
        удостоверяющий личность (серия, номер, кем и когда выдан)<br>
        индивидуального предпринимателя)
    </small>
</p>

<div class="row">
    <div class="col-xs-8">
        <strong>вид операции: </strong>
        <?php foreach ($supplyConditions as $id => $title): ?>
            <span class="no-wrap"><?= $checkBox($bsDocument->SUPPLYCONDITION == $id) . " $title" ?></span>&nbsp;
        <?php endforeach; ?>
    </div>
    <div class="col-xs-4">
        по курсу <?= Html::encode($bsDocument->REQUESTRATE ?: '_______________________') ?>
        <small>(заполняется Банком  в поручениях на бумажном носителе)</small>
    </div>
</div>

<table class="bordered">
    <tr>
        <td colspan="2" class="header-cell gray">
            <span>
                <small>Ф.И.О. сотрудника, уполномоченного на решение<br> вопроса по сделке, телефон:</small>
            </span>
            <span>
                <?= Html::encode($bsDocument->SENDEROFFICIALS) ?>, <?= Html::encode($bsDocument->PHONEOFFICIAL) ?>
            </span>
        </td>
    </tr>
    <tr>
        <td rowspan="2">
            <?= $checkBox(true) ?> <strong>Просим списать</strong> (отметить необходимое):<br>
            <div style="padding-left: 10px">
                <?= $checkBox(!empty($bsDocument->TRANSFERDOCUMENTNUMBER)) ?> на основании платежного поручения №
                <?= Html::encode($bsDocument->TRANSFERDOCUMENTNUMBER ?: '___________') ?><br>
                от <?= Yii::$app->formatter->asDate($bsDocument->TRANSFERDOCUMENTDATE) ?: '_______________________' ?><br>

                <?= $checkBox(!empty($bsDocument->NONACCEPTAGREENUMBER)) ?> на основании заранее данного акцепта по соглашению №
                <?= Html::encode($bsDocument->NONACCEPTAGREENUMBER ?: '___________') ?><br>
                от <?= Yii::$app->formatter->asDate($bsDocument->NONACCEPTAGREEDATE) ?: '_______________________' ?><br>

                <strong>с расчетного</strong> счета в валюте Российской Федерации<br>
                № <?= Html::encode($bsDocument->ACCOUNTDEBET ?: '_______________________') ?> сумму для покупки иностранной валюты
            </div>
        </td>
        <td style="height: 10px;">
            <small>Указывается сумма продаваемой (списываемой) валюты Российской Федерации (цифрами и прописью)</small>
        </td>
    </tr>
    <tr>
        <td>
            <?= Html::encode($bsDocument->AMOUNTDEBET) ?> <?= Html::encode($debitCurrencyCode) ?><br>
            <?= Html::encode(NumericHelper::num2str($bsDocument->AMOUNTDEBET, $debitCurrencyCode)) ?>
        </td>
    </tr>
    <tr>
        <td rowspan="2">
            <?= $checkBox(true) ?>
            <strong>Просим купить иностранную валюту</strong> в пределах суммы списанной валюты Российской Федерации, но не более указанной суммы иностранной валюты:
        </td>
        <td style="height: 10px;">
            <small>Указывается сумма покупаемой иностранной валюты (цифрами и прописью) и ее наименование</small>
        </td>
    </tr>
    <tr>
        <td>
            <?= Html::encode($bsDocument->AMOUNTCREDIT) ?> <?= Html::encode($creditCurrencyCode) ?><br>
            <?= Html::encode(NumericHelper::num2str($bsDocument->AMOUNTCREDIT, $creditCurrencyCode)) ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <strong>Вид расчетов по операции:</strong>
            <br>
            <?= $checkBox($bsDocument->REQUESTRATETYPE == 0) ?>
            <strong>по курсу Банка ВТБ (ПАО)</strong>, установленному  для данного вида операций
            <br>
            <?= $checkBox($bsDocument->REQUESTRATETYPE == 1 || $bsDocument->REQUESTRATETYPE == 2) ?>
            <strong>по льготному курсу</strong> (по согласованию с Банком ВТБ (ПАО))
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <strong>
                <?= $checkBox(true) ?>
                Сумму купленной иностранной валюты зачислить на расчетный счет в иностранной валюте
                № <?= Html::encode($bsDocument->ACCOUNTCREDIT) ?> открытый в <?= Html::encode($creditBank ? $creditBank->name : '') ?>
            </strong>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <strong>Код валютной операции:</strong> {VO<?= Html::encode($bsDocument->GROUNDCODE) ?>}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            Документ, являющийся основанием для проведения операции
            <table>
                <tr>
                    <th class="width-33-percent">Вид обосновывающего документа</th>
                    <th class="width-33-percent">Номер документа</th>
                    <th>Дата документа</th>
                </tr>
                <?php foreach ($bsDocument->GROUNDRECEIPTSBLOB as $groundReceipt): ?>
                    <tr>
                        <td><?= Html::encode($groundReceipt->DOCUMENTTYPE) ?></td>
                        <td><?= Html::encode($groundReceipt->DOCUMENTNUMBER) ?></td>
                        <td><?= Yii::$app->formatter->asDate($groundReceipt->DOCUMENTDATE, $dateFormat) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            В случае невозможности исполнения Банком настоящего поручения, а также в случае превышения суммы,
            списанной с нашего счета валюты Российской Федерации над суммой, направленной на покупку иностранной валюты,
            просим вернуть списанную и неиспользованную сумму валюты Российской Федерации на наш расчетный счет в
            валюте Российской Федерации в Банке ВТБ (ПАО) № <?= Html::encode($bsDocument->BALANCEACCOUNT) ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            С условиями Правил приема и исполнения поручений юридических лиц и индивидуальных предпринимателей
            на совершение конверсионных операций, действующих в Банке ВТБ (ПАО), ознакомлены и согласны.
        </td>
    </tr>
    <tr>
        <td colspan="2" class="gray">
            Примечание: <?= Html::encode($bsDocument->MESSAGEFORBANK) ?>
        </td>
    </tr>
</table>

<?= $this->render(
    '_bottom',
    [
        'typeModel' => $typeModel,
        'bankName' => $creditBank ? $creditBank->name : '',
        'statusReportsData' => $statusReportsData,
        'stampStatus' => 'ACCP',
    ]
) ?>

<style>
    body {
        padding: 0;
    }
    #forPrintPreview {
        padding: 0;
    }
    h1 {
        font-size: 14px;
        line-height: 1.5;
        font-weight: bold;
        margin-top: 0;
    }
    td, th {
        padding: 2px 5px;
        vertical-align: top;
    }
    th {
        font-weight: bold;
        text-align: center;
    }
    table {
        margin-bottom: 5px;
        margin-top: 10px;
        width: 100%;
    }
    .bordered,
    table.bordered th,
    table.bordered td {
        border: 1px solid black;
    }
    small {
        line-height: 1;
        display: block;
    }
    .row {
        margin: 0;
    }
    .col-xs-8, .col-xs-4 {
        padding: 0;
    }
    .glyphicon {
        font-size: 11px;
    }
    table .header-cell > * {
        display: inline-block;
        margin-right: 10px;
        vertical-align: middle;
    }
    .gray {
        background-color: #eee !important;
        box-shadow: inset 0 0 0 1000px #eee !important;
    }
    .no-wrap {
        white-space: nowrap;
    }
    .width-33-percent {
        width: 33%;
    }
    table table td, table table th {
        height: 20px;
    }
</style>
