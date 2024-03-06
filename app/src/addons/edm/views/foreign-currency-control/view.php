<?php

use addons\edm\models\DictCurrency;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationItem;
use common\document\Document;
use common\helpers\Countries;
use common\widgets\TransportInfo\TransportInfoModal;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var ForeignCurrencyOperationInformationExt $extModel */

$this->title = Yii::t('app/menu', 'Foreign currency control') . ' #' . $document->id;
$operationModel = new ForeignCurrencyOperationInformationItem();
$dateFormat = 'php:d.m.Y';
$dateTimeFormat = 'php:d.m.Y H:i';
$accpDate = $statusReportsData->getStatusDate('ACCP');
$acscDate = $statusReportsData->getStatusDate('ACSC');
$rjctDate = $statusReportsData->getStatusDate('RJCT');

// Вывести шаблон отображения управляющих кнопок
echo $this->render('@addons/edm/views/documents/_fccViewContent', [
    'document' => $document,
    'type' => 'fci',
    'backUrl' => $backUrl,
    'printUrl' => '/edm/foreign-currency-control/print',
    'updateUrl' => '/edm/foreign-currency-control/update',
    'deleteUrl' => '/edm/foreign-currency-control/delete',
    'sendUrl' => '/edm/foreign-currency-control/send',
    'afterSignUrl' => Url::to(['/edm/foreign-currency-control/after-signing']),
    'rejectSignUrl' => 'foreign-currency-control/reject-signing',
    'exportExcelUrl' => '/edm/export/export-fcc',
    'beforeSigningUrl' => Url::to(['/edm/foreign-currency-control/before-signing', 'id' => $document->id]),
]);
?>

<?php if ((Document::STATUS_SIGNING_REJECTED == $document->status) && !empty($statusEvent)) : ?>
    <div class="alert alert-warning">
        <p><?=$statusEvent->label?></p>
        <?= Html::encode($statusEvent->reason) ?>
    </div>
<?php endif ?>

<table width="100%">
    <tr>
        <td style="padding-right:1em"><nobr>Наименование уполномоченного банка</nobr></td>
        <td style="border:1px solid black; padding-left:1em" width="80%"><?= $extModel->bankName ?></td>
    </tr>
    <tr><td style="padding-right:1em">Наименование резидента</td>
        <td style="border:1px solid black; padding-left:1em" width="80%"><?= $extModel->getOrganizationName(true) ?></td></tr>
</table>
<h4 style="margin-bottom:1em; color:black">Сведения о валютной операции</h4>
<table>
    <tr>
        <td style="padding-right:1em"><nobr>Номер документа</nobr></td>
        <td style="border:1px solid black; padding-left:1em; width:148px"><?= $extModel->number ?></td>
        <td style="padding-right:1em;padding-left:1em">Дата</td>
        <td style="border:1px solid black; padding-left:1em; width:100px"><?= $extModel->date ?></td>
    </tr>
</table>
<br/>
<table width="100%">
    <tr>
        <td style="padding-right:1em"><nobr>Номер счета резидента в уполномоченном банкe</nobr></td>
        <td style="border:1px solid black; padding-left:1em" width="80%"><?=$extModel->accountNumber ?></td>
    </tr>
    <tr><td style="padding-right:1em">Код страны банка-нерезидента</td>
        <td style="border:1px solid black; padding-left:1em" width="80%"><?= Countries::getNumericCode($extModel->countryCode) ?></td></tr>
</table>
<br/>
<table border="1" width="100%">
    <tr align="center">
        <td rowspan="2">№ п/п</td>
        <td rowspan="2">Уведомление, распоряжение, расчетный или иной документ</td>
        <td rowspan="2">Дата операции</td>
        <td rowspan="2">Признак платежа</td>
        <td rowspan="2">Код вида валютной операции</td>
        <td colspan="2">Сумма операции</td>
        <td rowspan="2">Уникальный номер контракта (кредитного договора) или номер и (или) дата договора (контракта)</td>
        <td colspan="2">Сумма операции в единицах валюты контракта (кредитного договора)</td>
        <td rowspan="2">Срок возврата аванса</td>
        <td rowspan="2">Ожидаемый срок</td>
        <td rowspan="2">Признак представления документов, связанных с проведением операций<sup>1</sup></td>
    </tr>
    <tr align="center">
        <td>код валюты</td>
        <td>сумма</td>
        <td>код валюты</td>
        <td>сумма</td>
    </tr>
    <tr align="center">
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8</td>
        <td>9</td>
        <td>10</td>
        <td>11</td>
        <td>12</td>
        <td>13</td>
    </tr>
<?php
    $i = 1;
    /** @var ForeignCurrencyOperationInformationItem $operation */
?>
<?php foreach($extModel->items as $operation) : ?>
    <tr align="center">
        <td><?= $i++ ?></td>
        <td><?=$operation->number ?: 'БН' ?></td>
        <td><?=$operation->operationDate ?></td>
        <td><?= $operation->paymentType == 1 ? 'Зачисление' : 'Списание' ?></td>
        <td><?=$operation->codeFCO ?></td>
        <td><?=DictCurrency::getNameById($operation->currencyId) ?></td>
        <td align="right"><?=$operation->operationSum ?></td>
        <td><?=$operation->paymentDocumentNumber ?></td>
        <td><?=DictCurrency::getNameById($operation->currencyUnitsId) ?></td>
        <td align="right"><?=$operation->operationSumUnits ?></td>
        <td><?=$operation->refundDate ?></td>
        <td><?=$operation->expectedDate ?></td>
        <td><?=($operation->docRepresentation ?: '') ?></td>
    </tr>
<?php endforeach ?>
</table>
__________________________________<br/>
Примечание.
<table cellpadding="10" border="1" width="100%">
    <tr align="center">
        <td>№ строки</td>
        <td width="90%">Содержание</td>
    </tr>
<?php $i = 1 ?>
<?php foreach($extModel->items as $operation) : ?>
    <tr align="center">
        <td><?= $i++ ?></td>
        <td><?= $operation->comment ?></td>
    </tr>
<?php endforeach ?>
</table>
<br/>
<strong>Приложенные документы</strong>
<br/>
<?php foreach($files as $item => $fileList) : ?>
    <?php foreach($fileList as $pos => $file) : ?>
        <?= Html::a(
            $file->name,
            Url::toRoute(['download-attachment', 'id' => $document->id, 'item' => $item, 'pos' => $pos]))
        ?><br/>
    <?php endforeach ?>
<?php endforeach ?>
<br/>
<?php
    $signatures = $document->getSignatures(Document::SIGNATURES_TYPEMODEL);
    // Вывести блок подписей
    echo $this->render('@common/views/document/_signatures', ['signatures' => $signatures]);
?>
<br/>
<p>
    <strong>Дата представления в Банк:</strong>
    <?= $accpDate ? Yii::$app->formatter->asDatetime($accpDate, $dateTimeFormat) : '' ?>
    <br/>
    <strong>Дата принятия Банком: </strong>
    <?= $acscDate ? Yii::$app->formatter->asDatetime($acscDate, $dateTimeFormat) : '' ?>
    <br/>
    <?php if ($rjctDate) : ?>
    <strong>Дата отказа Банком: </strong>
    <?= Yii::$app->formatter->asDatetime($rjctDate, $dateTimeFormat) ?>
    <br/>
    <strong>Причина отказа:</strong>
    <?= Html::encode($statusReportsData->getRejectionReason()) ?>
    <?php endif ?>
</p>
__________________________________<br/>
<div class="row">
<div class="col-sm-4">
    <small><nobr>1 Указываются коды признаков представления резидентом документов:</nobr></small>
</div>
<div class="col-sm-8"><small>
1 – документы не представлены в соответствии с пунктами 2.7 и 2.15 Инструкции 181-И;<br/>
2 – документы не представлены в соответствии с пунктами 2.6, 2.8, 2.14 и 2.16 Инструкции 181-И,
а также в случае зачисления валюты РФ по договору, не требующего постановки на учет;<br/>
3 – документы не представлены в соответствии с пунктом 2.2 Инструкции 181-И;<br/>
4 – документы представлены.</small>
</div>
</div>
<?php
if ($extModel->document) {
    echo TransportInfoModal::widget(['document' => $extModel->document, 'isVolatile' => false]);
}
