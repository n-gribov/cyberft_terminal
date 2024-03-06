<?php

$organization = $model->getOrganization();
//$bank = $model->getOrganizationAccountBank();

?>

<div class="row margin-bottom-10">
    <div class="col-md-6">
        <strong><?=$model->getAttributeLabel('number')?></strong>: <?=$model->number?>
    </div>
    <div class="col-md-6">
        <strong><?=$model->getAttributeLabel('date')?></strong>: <?=$model->date?>
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-12 margin-bottom-10">
        <strong><?=$model->getAttributeLabel('organizationId')?></strong>: <?=$organization->name?>
    </div>
    <div class="col-md-12 margin-bottom-10">
        <strong>ИНН</strong>: <?=$organization->inn?>
    </div>
    <div class="col-md-12 margin-bottom-10">
        <strong>Адрес</strong>: <?=$organization->address?>
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-6">
        <strong><?=$model->getAttributeLabel('contactPersonName')?></strong>: <?=$model->contactPersonName?>
    </div>
    <div class="col-md-6">
        <strong><?=$model->getAttributeLabel('contactPersonPhone')?></strong>: <?=$model->contactPersonPhone?>
    </div>
</div>
<hr>
<h4>Сделка</h4>

<div class="row margin-bottom-30">
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('currencyIncomingNumber')?></strong>: <?=$model->currencyIncomingNumber?>
    </div>
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('currencyIncomingDate')?></strong>: <?=$model->currencyIncomingDate?>
    </div>
</div>

<div class="row margin-bottom-30">
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('transitAccount')?></strong>: <?=$model->transitAccount?>
    </div>
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('amount')?></strong>: <?=Yii::$app->formatter->asDecimal($model->amount, 2) ?> <?= $model->amountCurrency ?>
    </div>
</div>

<div class="row margin-bottom-30">
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('foreignAccount')?></strong>: <?=$model->foreignAccount?>
    </div>
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('amountTransfer')?></strong>: <?=Yii::$app->formatter->asDecimal($model->amountTransfer, 2)?> <?= $model->amountTransferCurrency ?>
    </div>
</div>
<?php if ($model->amountSell) : ?>
<div class="row margin-bottom-30">
    <div class="col-md-12">
        <strong>Продать валюту с транзитного счета на валютном рынке на сумму</strong>
    </div>
    <div class="col-md-12">
        <?=Yii::$app->formatter->asDecimal($model->amountSell, 2) ?> <?= $model->amountSellCurrency ?>
    </div>
</div>

<div class="row margin-bottom-30">
    <div class="col-md-12">
        <strong>Вырученные от продажи средства зачислить на расчетный счет</strong>
    </div>
    <div class="col-md-12">
        <?=$model->account?>
    </div>
</div>
<?php endif ?>
<div class="row margin-bottom-30">
    <div class="col-md-12">
        <strong>Комиссию и расходы списать со счета</strong>
    </div>
    <div class="col-md-12">
        <?=$model->commissionAccount?>
    </div>
</div>
<div class="row">
    <?= $this->render('@common/views/document/_signatures', ['signatures' => $signatureList]) ?>
</div>
<style>
    .margin-bottom-10 {
        margin-bottom: 10px;
    }
    .margin-bottom-30 {
        margin-bottom: 30px;
    }
</style>