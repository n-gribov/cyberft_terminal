<?php

use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use common\document\Document;
use common\helpers\Countries;
use common\modules\certManager\models\Cert;
use yii\helpers\Html;

$account = EdmPayerAccount::findOne($extModel->accountId);
$organization = DictOrganization::findOne($extModel->organizationId);
$stampDate = $statusReportsData->getStatusDate('ACCP');
$dateFormat = 'php:d.m.Y';
$dateTimeFormat = 'php:d.m.Y H:i';
$accpDate = $statusReportsData->getStatusDate('ACCP');
$acscDate = $statusReportsData->getStatusDate('ACSC');
$rjctDate = $statusReportsData->getStatusDate('RJCT');

?>

<!--<div class="row">
    <div class="col-sm-12 text-right font-size-15 margin-bottom-10">
        Код формы по ОКУД 0406009
    </div>
</div>-->

<div class="row">
    <div class="col-sm-3 width-19">
        Наименование уполномоченного банка
    </div>
    <div class="col-sm-9 width-81">
        <div class="border-all padding-left-10">
            <?=$extModel->bankName?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-3 width-19">
        Наименование резидента
    </div>
    <div class="col-sm-9 width-81">
        <div class="border-all padding-left-10">
            <?=$extModel->getOrganizationName(true)?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 text-center">
        <h3 class="text-uppercase color-black margin-bottom-0">Сведения о валютной операции</h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 text-center">
        <h4 class="color-black">от <?=$extModel->date?></h4>
    </div>
</div>

<div class="row">
    <div class="col-sm-3">Номер счета резидента в уполномоченном банкe</div>
    <div class="col-sm-9">
        <div class="border-all padding-left-10"><?=$extModel->accountNumber?></div>
    </div>
</div>

<div class="row margin-bottom-30">
    <div class="col-sm-3">Код страны банка-нерезидента</div>
    <div class="col-sm-9">
        <div class="row">
            <div class="col-sm-3">
                <div class="border-all padding-left-10">
                    <?= Countries::getNumericCode($extModel->countryCode) ?><br/>
                </div>
            </div>
            <div class="col-sm-offset-5 col-sm-3">Признак корректировки</div>
            <div class="col-sm-2">
                <div class="border-all padding-left-10"><?=$extModel->correctionNumber?><br/></div>
            </div>
        </div>
    </div>
</div>

        <table class="table-operation">
            <tr>
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
            <tr>
                <td>код валюты</td>
                <td>сумма</td>
                <td>код валюты</td>
                <td>сумма</td>
            </tr>
            <tr>
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
            <?php $i = 1; ?>
            <?php foreach($extModel->items as $operation) : ?>
                <tr>
                    <td><?=$i++?></td>
                    <td><?=$operation->number ?: 'БН' ?></td>
                    <td><?=$operation->operationDate ?></td>
                    <td><?=$operation->paymentType ?></td>
                    <td><?=$operation->codeFCO ?></td>
                    <td><?=$operation->currencyCode ?></td>
                    <td><?=Yii::$app->formatter->asDecimal($operation->operationSum, 2) ?></td>
                    <td><?=$operation->paymentDocumentNumber ?></td>
                    <td><?=$operation->currencyUnitsCode ?></td>
                    <td><?= $operation->operationSumUnits ? Yii::$app->formatter->asDecimal($operation->operationSumUnits, 2) : '0.00' ?></td>
                    <td><?=$operation->refundDate ?></td>
                    <td><?=$operation->expectedDate ?></td>
                    <td><?=($operation->docRepresentation ?: '') ?></td>
                </tr>
            <?php endforeach ?>
        </table>
__________________________________<br/>
<div class="row">
    <div class="col-sm-12">
        Примечание.
        <table class="table-operation">
            <tr>
                <td class="table-operation-comment-td-1">№ строки</td>
                <td class="table-operation-comment-td-2">Содержание</td>
            </tr>
            <?php $i = 1; ?>
            <?php foreach($extModel->items as $operation) : ?>
                <tr>
                    <td><?=$i++?></td>
                    <td><?=$operation->comment?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>
<br/>
<strong>Приложенные документы</strong>
<div class="row">
    <div class="col-sm-6">
        <?php foreach($files as $item => $fileList) : ?>
            <?php foreach($fileList as $pos => $file) : ?>
                <?= $file->name ?><br/>
            <?php endforeach ?>
        <?php endforeach ?>
        <br/>
        <strong>Электронные подписи</strong>
        <br/>
        <?php
            $signList = $document->getSignatures(Document::SIGNATURES_TYPEMODEL);
            foreach($signList as $sign) {
                echo $sign['name'] .  '<br/>';
            }
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
    </div>
    <div class="col-sm-6 text-right">
        <?php if ($stampDate): ?>
            <div class="stamp">
                <strong><?= Html::encode($extModel->bankName) ?></strong><br>
                <strong>Принято</strong><br>
                <?= Yii::$app->formatter->asDate($stampDate, $dateFormat) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
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
    $signatures = $document->getSignatures(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER);

    echo $this->render('@common/views/document/_signatures', ['signatures' => $signatures]);
?>

<?php

$this->registerCss('
    .stamp {
        border: 1px solid black;
        float: right;
        margin-right: 20px;
        margin-top: ;
        min-width: 200px;
        text-transform: uppercase;
    }
    .margin-bottom-15 {
        margin-bottom: 15px;
    }

    .font-size-13 {
        font-size: 13px;
    }

    .font-size-15 {
        font-size: 15px;
    }

    .margin-bottom-30 {
        margin-bottom: 30px;
    }

    .margin-bottom-10 {
        margin-bottom: 10px;
    }

    .width-19 {
        width: 19%;
    }

    .width-81 {
        width: 81%;
    }

    .border-all {
        border: 1px solid #000;
    }

    .color-black {
        color: #000;
    }

    .margin-bottom-0 {
        margin-bottom: 0;
    }

    .table-operation {
        width: 100%;
    }

    .table-operation-comment-td-1 {
        width: 10%;
    }

    .table-operation-comment-td-2 {
        width: 90%;
    }

    .table-operation td {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
    }

    .operation-bank-info {
        width: 100%;
    }

    .padding-left-10 {
        padding-left: 10px;
    }

    @media print {
        @page {
            size: landscape;
        }

        .border-all {
            border: 1px solid #000;
        }

        .table-operation td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
    }

    @media print {
      [class*="col-sm-"] {
        float: left;
      }
    }

   .col-sm-offset-5 {
        margin-left: 33.333333%;
   }

    .col-sm-12 {
        width: 100%;
    }

    .col-sm-11 {
        width: 91.66666667%;
    }

    .col-sm-10 {
        width: 83.33333333%;
    }

    .col-sm-9 {
        width: 75%;
    }

    .col-sm-8 {
        width: 66.66666667%;
    }

    .col-sm-7 {
        width: 58.33333333%;
    }

    .col-sm-6 {
        width: 50%;
    }

    .col-sm-5 {
        width: 41.66666667%;
    }

    .col-sm-4 {
        width: 33.33333333%;
    }

    .col-sm-3 {
        width: 25%;
    }

    .col-sm-2 {
        width: 16.66666667%;
    }

    .col-sm-1 {
        width: 8.33333333%;
    }
');

?>