<?php

use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyPaymentType;
use addons\swiftfin\models\SwiftFinDictBank;
use yii\helpers\Html;
use common\helpers\StringHelper;

?>

<div class="row">
    <div class="col-md-12">
        <strong>Сумма </strong><?= Yii::$app->formatter->asDecimal($model->sum, 2) ?> <?= Html::encode($model->currency) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <strong>Срочность платежа </strong><?= $model->immediatePaymentDescription ?> 
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4>Плательщик / Ordering customer</h4>
    </div>
</div>

<div class="row padding-left-15">
    <div class="col-md-12">
        <span class="attribute-title">Счет</span>
        <span class="attribute-value"><?= Html::encode($model->payerAccount) ?></span>
    </div>
    <div class="col-md-12">
        <span class="attribute-title">ИНН</span>
        <span class="attribute-value"><?= Html::encode($model->payerInn) ?></span>
    </div>
    <div class="col-md-12">
        <span class="attribute-title">Наименование</span>
        <span class="attribute-value"><?= Html::encode($model->payerName) ?></span>
    </div>
    <div class="col-md-12">
        <span class="attribute-title">Адрес</span>
        <span class="attribute-value"><?= Html::encode($model->payerAddress) ?></span>
    </div>
    <div class="col-md-12">
        <span class="attribute-title">Город, страна</span>
        <span class="attribute-value"><?= Html::encode($model->payerLocation) ?></span>
    </div>
</div>

<?php if ($model->payerBank || $model->payerBankName || $model->payerBankAddress): ?>
    <div class="row">
        <div class="col-md-12">
            <h4>Банк плательщика / Ordering institution</h4>
        </div>
    </div>

    <div class="row padding-left-15">
        <div class="col-md-12">
            <span class="attribute-title">SWIFT BIC</span>
            <span class="attribute-value"><?= Html::encode($model->payerBank) ?></span>
        </div>
        <div class="col-md-12">
            <span class="attribute-title">Наименование</span>
            <span class="attribute-value"><?= Html::encode($model->payerBankName) ?></span>
        </div>
        <div class="col-md-12">
            <span class="attribute-title">Адрес</span>
            <span class="attribute-value"><?= Html::encode($model->payerBankAddress) ?></span>
        </div>
    </div>
<?php endif; ?>


<?php if ($model->intermediaryBank || $model->intermediaryBankNameAndAddress): ?>
    <div class="row">
        <div class="col-md-12">
            <h4>Банк посредник / Intermediary institution</h4>
        </div>
    </div>

    <div class="row padding-left-15">
        <div class="col-md-12">
            <span class="attribute-title">SWIFT BIC</span>
            <span class="attribute-value"><?= Html::encode($model->intermediaryBank) ?></span>
        </div>

        <?php
        $swiftFinBank = $model->intermediaryBank
            ? SwiftFinDictBank::findOne(['fullCode' => $model->intermediaryBank])
            : null;
        ?>

        <?php if ($swiftFinBank): ?>
            <div class="col-md-12">
                <span class="attribute-title">Наименование</span>
                <span class="attribute-value"><?= Html::encode($swiftFinBank->name) ?></span>
            </div>
            <div class="col-md-12">
                <span class="attribute-title">Адрес</span>
                <span class="attribute-value"><?= Html::encode($swiftFinBank->address) ?></span>
            </div>
        <?php else: ?>
            <div class="col-md-12">
                <span class="attribute-title">Наименование и адрес</span>
                <span class="attribute-value"><?= Html::encode($model->intermediaryBankNameAndAddress) ?></span>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12">
        <h4>Получатель / Beneficiary customer</h4>
    </div>
</div>

<div class="row padding-left-15">
    <div class="col-md-12">
        <span class="attribute-title">Номер счета</span>
        <span class="attribute-value"><?= Html::encode($model->beneficiaryAccount) ?></span>
    </div>
    <div class="col-md-12">
        <span class="attribute-title">Наименование и адрес</span>
        <span class="attribute-value"><?= nl2br(Html::encode($model->beneficiary)) ?></span>
    </div>
    <div class="col-md-12">
        <span class="attribute-title"></span>
        <span class="attribute-value"><?= nl2br(Html::encode($model->beneficiaryAddress)) ?></span>
    </div>
    <div class="col-md-12">
        <span class="attribute-title"></span>
        <span class="attribute-value"><?= nl2br(Html::encode($model->beneficiaryLocation)) ?></span>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4>Банк получателя / Beneficiary institution</h4>
    </div>
</div>

<div class="row padding-left-15">
    <div class="col-md-12">
        <span class="attribute-title">SWIFT BIC</span>
        <span class="attribute-value"><?= Html::encode($model->beneficiaryBank) ?></span>
    </div>

    <?php
    $swiftFinBank = $model->beneficiaryBank
        ? SwiftFinDictBank::findOne(['fullCode' => $model->beneficiaryBank])
        : null;
    ?>

    <?php if ($swiftFinBank): ?>
        <div class="col-md-12">
            <span class="attribute-title">Наименование</span>
            <span class="attribute-value"><?= Html::encode($swiftFinBank->name) ?></span>
        </div>
        <div class="col-md-12">
            <span class="attribute-title">Адрес</span>
            <span class="attribute-value"><?= Html::encode($swiftFinBank->address) ?></span>
        </div>
    <?php else: ?>
        <div class="col-md-12">
            <span class="attribute-title">Наименование и адрес</span>
            <span class="attribute-value"><?= Html::encode($model->beneficiaryBankNameAndAddress) ?></span>
        </div>
    <?php endif; ?>
</div>

<div class="row">
    <div class="col-md-12">
        <h4>Информация / Remittance information</h4>
    </div>
</div>

<div class="row padding-left-15">
    <div class="col-md-12">
        <?= StringHelper::mtMultiline(Html::encode($model->information)) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4>Комиссия / Commission</h4>
    </div>
</div>

<div class="row padding-left-15">
    <div class="col-md-12">
        <?= ForeignCurrencyPaymentType::commissionLabels($model->commission) ?>
    </div>
</div>

<div class="row padding-left-15">
    <div class="col-md-12">
        <strong>Счет списания комиссии </strong><?= $model->commissionAccount ?> 
    </div>
</div>

<?php if (floatval($model->commissionSum) > 0): ?>
    <div class="row">
        <div class="col-md-12">
            <h4>Расходы отправителя / Customer expenses</h4>
        </div>
    </div>

    <div class="row padding-left-15">
        <div class="col-md-12">
            <?= Yii::$app->formatter->asDecimal($model->commissionSumm, 2) ?>
        </div>
    </div>
<?php endif ?>

<div class="row">
    <div class="col-md-12">
        <h4>Дополнительная информация / Additional information</h4>
    </div>
</div>

<div class="row padding-left-15">
    <div class="col-md-12">
        <?= StringHelper::mtMultiline(Html::encode($model->additionalInformation)) ?>
    </div>
</div>

<?php

$this->registerCss('
    .margin-bottom-10 {
        margin-bottom: 10px;
    }

    .padding-left-15 {
        padding-left: 15px;
    }

    .margin-left-100 {
        margin-left: 100px;
    }

    .attribute-title {
        display: inline-block;
        font-weight: bold;
        vertical-align: top;
        width: 170px;
    }
    
    .attribute-value {
        display: inline-block;
        vertical-align: top;
        width: 350px;
    }
');


?>