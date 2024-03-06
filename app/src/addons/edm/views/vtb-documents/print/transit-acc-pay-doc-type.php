<?php

use addons\edm\models\DictBank;
use addons\edm\models\DictCurrency;
use common\helpers\NumericHelper;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \addons\edm\models\BaseVTBDocument\BaseVTBDocumentType $typeModel */
/** @var \addons\edm\models\DictOrganization $senderOrganization */
/** @var \common\document\DocumentStatusReportsData $statusReportsData */

/** @var \common\models\vtbxml\documents\TransitAccPayDoc $bsDocument */
$bsDocument = $typeModel->document;

$dateFormat = 'php:d.m.Y';

Yii::$app->formatter->nullDisplay = '';

$currencyCode = DictCurrency::getAlphaCodeByNumericCode($bsDocument->CURRCODE);
$bank = DictBank::findOne(['bik' => $bsDocument->CREDITBANKBICCURR]);
$bankName = $bank ? $bank->name : null;

?>

<table>
    <tr>
        <td style="width: 50%;"><strong><?= Html::encode($bankName) ?></strong></td>
        <td>
            <strong><?= Html::encode($senderOrganization->name) ?></strong><br>
            <small>(наименование юридического лица/Ф.И.О. индивидуального предпринимателя)</small>
        </td>
    </tr>
</table>

<h1>
    РАСПОРЯЖЕНИЕ № <?= Html::encode($bsDocument->DOCUMENTNUMBER) ?> от <?= Yii::$app->formatter->asDate($bsDocument->DOCUMENTDATE, 'd MMMM y') ?>
    <br>
    на перечисление средств без осуществления продажи иностранной валюты на расчетный
    <br>
    счет в иностранной валюте
</h1>

<p>
    Настоящим просим из общей суммы иностранной валюты:<br>
    - наименование иностранной валюты: <?= $currencyCode ?><br>
    - сумма (цифрами и прописью): <?= $bsDocument->TOTALAMOUNT ?> (<?= NumericHelper::num2str($bsDocument->TOTALAMOUNT, $currencyCode) ?>),<br>
    поступившей на наш транзитный валютный счет<br>
    № <?= Html::encode($bsDocument->ACCOUNTTRANSIT) ?>
</p>

<p><strong>Уведомления БАНК ВТБ (ПАО):</strong></p>

<table class="bordered">
    <tr>
        <th>Номер</th>
        <th>Дата</th>
        <th>Сумма</th>
        <th>Код валюты</th>
    </tr>
    <?php foreach ($bsDocument->NOTICEBLOB as $notice) : ?>
        <tr>
            <td><?= Html::encode($notice->NOTICENUMBER) ?></td>
            <td><?= Yii::$app->formatter->asDate($notice->NOTICEDATE, $dateFormat) ?></td>
            <td><?= Html::encode($notice->NOTICEAMOUNT) ?></td>
            <td><?= Html::encode($notice->NOTICECURRCODE) ?></td>
        </tr>
    <?php endforeach ?>
</table>

<p>
    списать с указанного счета для перечисления на расчетный счет в иностранной валюте в сумме:<br>
    - наименование иностранной валюты: <?= $currencyCode ?><br>
    - сумма (цифрами и прописью): <?= $bsDocument->AMOUNTDEBET ?> (<?= NumericHelper::num2str($bsDocument->AMOUNTDEBET, $currencyCode) ?>).
</p>
<p>
    Расчетный счет в иностранной валюте № <?= Html::encode($bsDocument->RECEIVERCURRACCOUNT) ?>
    в <?= Html::encode($bankName) ?>
</p>
<p>
    Дополнительная информация:
    <small>(краткое описание содержания валютной операции со ссылкой на соответствующие подтверждающие документы)</small>
</p>

<p><strong>Обосновывающие документы:</strong></p>
<table class="bordered">
    <tr>
        <th>Вид документа</th>
        <th>Номер</th>
        <th>Дата</th>
        <th>Примечание</th>
    </tr>
    <?php foreach ($bsDocument->GROUNDRECEIPTSBLOB as $groundReceipt) : ?>
        <tr>
            <td><?= Html::encode($groundReceipt->DOCUMENTTYPE) ?></td>
            <td><?= Html::encode($groundReceipt->DOCUMENTNUMBER) ?></td>
            <td><?= Yii::$app->formatter->asDate($groundReceipt->DOCUMENTDATE, $dateFormat) ?></td>
            <td><?= Html::encode($groundReceipt->DESCRIPTION) ?></td>
        </tr>
    <?php endforeach ?>
</table>
<p>Копии подтверждающих документов прилагаем.</p>
<?= $this->render(
    '_bottom',
    [
        'typeModel' => $typeModel,
        'bankName' => $bankName,
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
        font-size: 16px;
        line-height: 1.5;
        text-align: center;
        font-weight: bold;
    }
    td, th {
        padding: 5px;
        vertical-align: top;
    }
    th {
        font-weight: bold;
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
