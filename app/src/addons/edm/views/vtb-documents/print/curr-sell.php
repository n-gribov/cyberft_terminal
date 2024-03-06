<?php

use addons\edm\models\DictBank;
use addons\edm\models\DictCurrency;
use addons\edm\models\EdmPayerAccount;
use common\helpers\NumericHelper;
use yii\helpers\Html;

/** @var \addons\edm\models\BaseVTBDocument\BaseVTBDocumentType $typeModel */
/** @var \addons\edm\models\DictOrganization $senderOrganization */
/** @var \common\document\DocumentStatusReportsData $statusReportsData */

/** @var \common\models\vtbxml\documents\CurrSell $bsDocument */
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
    на продажу иностранной валюты за<br>
    валюту Российской Федерации
</h1>

<p>
    Наименование Клиента<br>
    <?= $join(', ', [$senderOrganization->name, $bsDocument->CUSTOMEROKPO]) ?>
    <small>
        (наименование и код ОКПО организации / Ф.И.О. и документ,<br>
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
        <td colspan="2">
            <p>
                <?= $checkBox(false) ?> <strong>Просим списать</strong> с транзитного валютного счета № ______________________________________,<br>
                из общей суммы иностранной валюты __________________________________________, поступившей на указанный транзитный<br>
                валютный счет «___» ____________________ 20__ г. (Уведомление Банка № ________ от «___» ____________________ 20__ г.)
            </p>
            <table class="bordered text-center">
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
            <table class="bordered">
                <tr>
                    <td class="width-50-percent">
                        <?= $checkBox(false) ?>
                        <strong>для продажи иностранной валюты за валюту Российской Федерации </strong>
                    </td>
                    <td>
                        <strong>В сумме</strong>
                        <br>
                        <br>
                        <div class="bottom-border"></div>
                        <small>(Указывается сумма продаваемой иностранной валюты (цифрами и прописью) и ее наименование)</small>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $checkBox(false) ?>
                        <strong>для перечисления части иностранной валюты</strong>
                        <div style="padding-left: 10px">
                            <?= $checkBox(false) ?> на расчетный счет в иностранной валюте<br>
                            № _____________________________________________, открытый в<br>
                            Банк ВТБ (ПАО)<br>
                            <?= $checkBox(false) ?> в соответствии с прилагаемым Заявлением на перевод<br>
                            № ______ от __________________________________ г.
                        </div>
                    </td>
                    <td>
                        <strong>В сумме</strong>
                        <br>
                        <br>
                        <div class="bottom-border"></div>
                        <small>(Указывается сумма, переводимая на расчетный счет в иностранной валюте (цифрами и прописью) и ее наименование)</small>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="width-50-percent" rowspan="2">
            <?= $checkBox(true) ?> <strong>
                Просим списать с расчетного счета в иностранной валюте<br>
                № <?= Html::encode($bsDocument->ACCOUNTDEBET) ?><br>
                сумму в иностранной валюте, необходимую для покупки<br>
                валюты Российской Федерации
            </strong>
        </td>
        <td>
            <small>Указывается сумма продаваемой иностранной валюты (цифрами и прописью) и ее наименование</small>
        </td>
    </tr>
    <tr>
        <td>
            <?= Html::encode($bsDocument->AMOUNTDEBET) ?> <?= Html::encode($debitCurrencyCode) ?>
            <br>
            <?= Html::encode(NumericHelper::num2str($bsDocument->AMOUNTDEBET, $debitCurrencyCode)) ?>
        </td>
    </tr>
    <tr>
        <td rowspan="2">
            <?= $checkBox(true) ?> <strong>Просим купить валюту Российской Федерации</strong>
        </td>
        <td>
            <small>Указывается сумма покупаемой валюты Российской Федерации (цифрами и прописью)</small>
        </td>
    </tr>
    <tr>
        <td>
            <?= Html::encode($bsDocument->AMOUNTCREDIT) ?> <?= Html::encode($creditCurrencyCode) ?>
            <br>
            <?= Html::encode(NumericHelper::num2str($bsDocument->AMOUNTCREDIT, $creditCurrencyCode)) ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            Вид расчетов по операции:<br>
            <?= $checkBox($bsDocument->REQUESTRATETYPE == 0) ?>
            <strong>по курсу Банк ВТБ (ПАО)</strong>, установленному для данного вида операций
            <br>
            <?= $checkBox($bsDocument->REQUESTRATETYPE == 1 || $bsDocument->REQUESTRATETYPE == 2) ?>
            <strong>по льготному курсу</strong> (по согласованию с Банк ВТБ (ПАО))
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?= $checkBox(true) ?> <strong>Просим зачислить полученную сумму валюты Российской Федерации на расчетный счет в валюте Российской Федерации</strong>
            <br>
            № <?= Html::encode($bsDocument->ACCOUNTCREDIT) ?>, открытый в <?= Html::encode($creditBank ? $creditBank->name : '') ?>
            <br>
            <small>(указывается наименование банка)</small>
            кор.счет <?= Html::encode($creditBank ? $creditBank->account : '') ?> БИК <?= Html::encode($creditBank ? $creditBank->bik : '') ?> ИНН 7702070139
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <strong>Код валютной операции:</strong> {VO<?= Html::encode($bsDocument->OPERCODE) ?>}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <small>
                <?= $checkBox(true) ?> В случае невозможности исполнения Банком настоящего поручения, а также в случае
                превышения суммы списанной с нашего счета иностранной валюты над суммой, направленной на покупку валюты
                Российской Федерации, просим вернуть списанную и неиспользованную сумму иностранной валюты на наш расчетный
                счет в иностранной валюте / транзитный валютный счет в Банк ВТБ (ПАО)
                № <?= Html::encode($bsDocument->BALANCEACCOUNT) ?>
            </small>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <small>
                С условиями Правил приема и исполнения поручений юридических лиц и индивидуальных предпринимателей на
                совершение конверсионных операций, действующих в Банк ВТБ (ПАО), ознакомлены и согласны.
            </small>
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
    .text-center {
        text-align: center;
    }
    .no-wrap {
        white-space: nowrap;
    }
    .width-50-percent {
        width: 50%;
    }
    .width-33-percent {
        width: 33%;
    }
    .bottom-border {
        border-bottom: 1px solid black;
    }
    table table td, table table th {
        height: 20px;
    }
</style>
