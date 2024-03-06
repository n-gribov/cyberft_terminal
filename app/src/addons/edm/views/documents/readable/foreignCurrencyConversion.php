<?php
$organization = $model->getOrganization();
Yii::$app->formatter->decimalSeparator = '.';
?>
<div class="row margin-bottom-10">
    <div class="col-md-6">
        <strong><?=$model->getAttributeLabel('number')?></strong>: <?=$model->number ?>
    </div>
    <div class="col-md-6">
        <strong><?=$model->getAttributeLabel('date')?></strong>: <?=$model->date ?>
    </div>
</div>

<hr>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('organizationId')?></strong>: <?=$organization->name ?>
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-4">
        <strong><?=$organization->getAttributeLabel('inn')?></strong>: <?=$organization->inn ?>
    </div>
    <div class="col-md-4">
        <strong><?=$organization->getAttributeLabel('kpp')?></strong>: <?=$organization->kpp ?>
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        <strong><?=$organization->getAttributeLabel('address')?></strong>: <?=$organization->address ?>
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('contactPersonName')?></strong>: <?=$model->contactPersonName ?>
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('contactPersonPhone')?></strong>: <?=$model->contactPersonPhone ?>
    </div>
</div>

<hr>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('debitAccount')?></strong>: <?=$model->debitAccount ?>
        (<?=$model->getDebitAccountCurrencyName()?>)
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('debitAmount')?></strong>:

        <?php if ($model->debitAmount) : ?>
            <?= Yii::$app->formatter->asDecimal($model->debitAmount, 2) ?>
        <?php endif ?>
    </div>
</div>

<hr>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('creditAccount')?></strong>: <?=$model->creditAccount ?>
        (<?=$model->getCreditAccountCurrencyName()?>)
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('creditAmount')?></strong>:
        <?php if ($model->creditAmount) : ?>
            <?=Yii::$app->formatter->asDecimal($model->creditAmount, 2);?>
        <?php endif ?>
    </div>
</div>

<hr>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        <strong><?=$model->getAttributeLabel('commissionAccount')?></strong>:
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-12">
        <?=$model->commissionAccount ?>
    </div>
</div>

<style>
    .margin-bottom-10 {
        margin-bottom: 10px;
    }
    .margin-bottom-30 {
        margin-bottom: 30px;
    }
</style>