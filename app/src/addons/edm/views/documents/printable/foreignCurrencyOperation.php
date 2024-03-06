<?php

use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationType;
use common\document\Document;
use yii\bootstrap\BootstrapAsset;

BootstrapAsset::register($this);

/** @var Document $document */
/** @var ForeignCurrencyOperationType $typeModel */

if ($typeModel->getType() === ForeignCurrencyOperationType::OPERATION_PURCHASE) {
    $title = Yii::t('edm', 'The application for purchase of foreign currency number {num}', ['num' => $typeModel->numberDocument]);
    $labelDebitAccount = 'Расчетный счет';
    $labelCreditAccount = 'Текущий валютный счет';
    $assignment = 'Поручаем заключить от нашего имени и за наш счет сделку на покупку средств в иностранной валюте с зачислением купленной иностранной валюты на текущий валютный счет на нижеследующих условиях:';
} else {
    $title = Yii::t('edm', 'The application for sale of foreign currency number {num}', ['num' => $typeModel->numberDocument]);
    $labelDebitAccount = 'Расчетный валютный счет';
    $labelCreditAccount = 'Текущий рублевый счет';
    $assignment = 'Поручаем заключить от нашего имени и за наш счет сделку на продажу средств в иностранной валюте с зачислением полученных от продажи рублей на текущий рублевый счет на нижеследующих условиях:';
}

?>
<style type="text/css">
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
        font-size: 9pt;
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

/*    .row div{
        text-decoration: underline;
    }*/
</style>
<h2 align="center">
    <?= $title ?>
</h2>
<br />
<div class="main container-fluid">
    «<font class="underlineBlock"> <?=Yii::$app->formatter->asDate($typeModel->date, "php:d")?> </font>» <font class="underlineBlock">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::$app->formatter->asDate($typeModel->date, "php:F")?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font> 20<font class="underlineBlock"><?=Yii::$app->formatter->asDate($typeModel->date, "php:y")?></font>

    <div class="row">
      <div class="col-xs-4 startBlock">Наименование предприятия</div>
      <div class="col-xs-8 underlineBlock"><?= $typeModel->organizationName ?>&nbsp;</div>
    </div>
    <div class="row">
      <div class="col-xs-1 startBlock">ИНН</div>
      <div class="col-xs-4 underlineBlock"><?=$typeModel->applicant->inn?>&nbsp;</div>
    </div>
    <div class="row">
      <div class="col-xs-3 startBlock">Почтовый адрес</div>
      <div class="col-xs-9 underlineBlock"><?=$typeModel->applicant->address?>&nbsp;</div>
    </div>
    <div class="row">
      <div class="col-xs-2 startBlock">Телефон</div>
      <div class="col-xs-10 underlineBlock"><?=$typeModel->applicant->phone?>&nbsp;</div>
    </div>
    <div class="row">
      <div class="col-xs-8 startBlock">Ф.И.О. сотрудника, уполномоченного на решение вопросов по сделке</div>
      <div class="col-xs-4 underlineBlock"><?=$typeModel->applicant->contactPerson?>&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-xs-9 startBlock"><?= $labelDebitAccount ?>  <?=$typeModel->debitAccount->number?> в <?=$typeModel->debitAccount->bankName?></div>
    </div>
    <div class="row">
        <div class="col-xs-9 startBlock"><?= $labelCreditAccount ?> <?=$typeModel->creditAccount->number?> в <?=$typeModel->creditAccount->bankName?></div>
    </div>
    <div class="row">
        <div align="center" class="col-xs-12"><b>ВАЛЮТНОЕ ПЛАТЕЖНОЕ ПОРУЧЕНИЕ</b></div>
    </div>
    <?= $assignment ?>
    <table class="table table-bordered">
        <tr align="center">
            <td>Код валюты</td>
            <td>Курс сделки</td>
            <td>Сумма в валюте</td>
            <td>Сумма в рублях</td>
            <td>Сумма прописью</td>
        </tr>
        <tr align="center">
            <td><?=$typeModel->paymentOrderCurrCode?></td>
            <td><?php if ($typeModel->paymentOrderCurrExchangeRate) : ?><?=Yii::$app->formatter->asDecimal($typeModel->paymentOrderCurrExchangeRate, 4)?><?php else : ?>Не указан<?php endif ?></td>
            <td><?= Yii::$app->formatter->asDecimal($typeModel->paymentOrderCurrAmount ?: 0, 4) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($typeModel->paymentOrderAmount ?: 0, 4) ?></td>
            <?php if ($typeModel->paymentOrderCurrAmount) : ?>
                <td><?=Yii::$app->formatter->asSpellout($typeModel->paymentOrderCurrAmount)?></td>
            <?php else : ?>
                <td><?=Yii::$app->formatter->asSpellout($typeModel->paymentOrderAmount)?></td>
            <?php endif ?>
        </tr>
    </table>
    Предоставляем банку право удержать комиссию за совершение сделки со счета № <font class="underlineBlock"><?=$typeModel->commissionAccount->number?></font>
    <br />
    <br />
    <div class="row">
        <div class="col-xs-9 startBlock">
            М.П.
        </div>
    </div>
<?php if (count($signatures) > 0) : ?>
    <hr />
    <?= $this->render('@common/views/document/_signatures', ['signatures' => $signatures]) ?>
<?php endif ?>
?>

</div>