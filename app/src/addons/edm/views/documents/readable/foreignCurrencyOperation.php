<?php

use addons\edm\EdmModule;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationType;
use common\document\Document;
use common\document\DocumentPermission;
use yii\jui\DatePicker;
use yii\widgets\MaskedInput;

/** @var Document $document */
/** @var ForeignCurrencyOperationType $typeModel */

$documentTypeGroup = $typeModel->operationType === ForeignCurrencyOperationFactory::OPERATION_PURCHASE
    ? EdmDocumentTypeGroup::CURRENCY_PURCHASE
    : EdmDocumentTypeGroup::CURRENCY_SELL;

$userCanCreateDocuments = Yii::$app->user->can(
    DocumentPermission::CREATE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => $documentTypeGroup,
    ]
);

$isEditable = $document->isSignable() && $userCanCreateDocuments;

?>

<script>
    $('#fcoViewDeleteButton, #fcoViewUpdateButton').toggle(<?= $isEditable ? 'true' : 'false' ?>);

    $('#fcoViewSignButton')
        .toggle(<?= $document->isSignableByUserLevel(EdmModule::SERVICE_ID) ? 'true' : 'false' ?>)
        .data('document-id', <?= $document->id ?>);
</script>
<?php

if ($typeModel->operationType === ForeignCurrencyOperationType::OPERATION_PURCHASE) {
    $labelDebitAccount = 'Счет в рублях';
    $labelCreditAccount = 'Счет в валюте';
    $fieldCreditAmount = 'paymentOrderCurrAmount';
    $fieldDebitAmount = 'paymentOrderAmount';
} else {
    $labelDebitAccount = 'Счет в валюте';
    $labelCreditAccount = 'Счет в рублях';
    $fieldCreditAmount = 'paymentOrderAmount';
    $fieldDebitAmount = 'paymentOrderCurrAmount';
}

?>
<style type="text/css">
    .form-inline .form-group {
        width: 100%;
    }

    .form-inline .form-group .form-control {
        width: 100% ;
    }

    .form-inline-field {
        display: inline-block;
    }

    .form-inline-field input {
        border: 0;
        width: 100%;
    }

    .form-inline-subfield input {
        border: 0;
        width: 100%;
    }

    .form-label {
        display: block;
    }

    table > tbody tr.payment-order-bottom-row td {
        vertical-align: bottom;
        width: 14.28%;
    }

    .contract-link {
        text-decoration: none;
        display: block;
        margin-bottom: 15px;
    }

    .contract-info {
        display: none;
    }

    label {
        display: block;
        margin-top:10px;
    }

    <?php if (!$typeModel->applicant->name) : ?>
    .org-info {
        display: none;
    }
    <?php endif ?>

</style>
<?php DatePicker::widget([
    'id'         => 'foreigncurrencyoperationtype-date',
    'dateFormat' => 'dd.MM.yyyy',
]) ?>
<?php MaskedInput::widget([
    'id'            => 'foreigncurrencyoperationtype-date',
    'name'          => 'foreigncurrencyoperationtype-date',
    'mask'          => '99.99.9999',
    'clientOptions' => [
        'placeholder' => "dd.MM.yyyy",
    ]
])?>
<h4>Операция</h4>
<div class="row">
    <div class="col-sm-12">
        <?= $typeModel->getOperationTypes()[$typeModel->operationType] ?>
    </div>
</div>
<div class="body-info">
<div id="form-title"></div>
<div class="row">
    <div class="col-sm-6">
        <label><?= $typeModel->getAttributeLabel('numberDocument') ?></label>
        <?= $typeModel->numberDocument ?>
    </div>
    <div class="col-sm-6">
        <label><?= $typeModel->getAttributeLabel('date') ?></label>
        <?= $typeModel->date ?>
    </div>
</div>
<h4><?= $typeModel->getAttributeLabel('applicant.name') ?></h4>
<div class="row">
    <div class="col-sm-12">
        <?= $typeModel->organizationName ?>
    </div>
</div>
<div class="row org-info">
    <div class="col-sm-6">
        <label><?= $typeModel->getAttributeLabel('applicant.inn') ?></label>
        <?= $typeModel->applicant->inn ?>
    </div>
    <div class="col-sm-6">
        <label><?= $typeModel->getAttributeLabel('applicant.address') ?></label>
        <?= $typeModel->applicant->address ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <label><?= $typeModel->getAttributeLabel('applicant.contactPerson') ?></label>
        <?= $typeModel->applicant->contactPerson ?>
    </div>
    <div class="col-sm-6">
        <label><?= $typeModel->getAttributeLabel('applicant.phone') ?></label>
        <?= $typeModel->applicant->phone ?>
    </div>
</div>
<h4>Сделка</h4>
<div class="row">
    <div class="col-sm-6">
        <label><?= $labelDebitAccount ?></label>
        <?php
            if (isset($typeModel->debitAccount)) {
                echo $typeModel->debitAccount->number . str_repeat('&nbsp', 3) . $typeModel->getDebitAccountCurrency('name');
            } else {
                echo '- NOT FOUND -';
            }
        ?>
    </div>
    <div class="col-sm-3">
        <label><nobr><?= $typeModel->getAttributeLabel($fieldDebitAmount) ?></nobr></label>
        <?= $typeModel->$fieldDebitAmount
                ? number_format($typeModel->$fieldDebitAmount, 2, ',', ' ')
                : ''
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <label><?= $labelCreditAccount ?></label>
        <?php
            if (isset($typeModel->creditAccount)) {
                echo $typeModel->creditAccount->number . str_repeat('&nbsp', 3) . $typeModel->getCreditAccountCurrency('name');
            } else {
                echo '- NOT FOUND -';
            }
        ?>
    </div>
    <div class="col-sm-3">
        <label><nobr><?= $typeModel->getAttributeLabel($fieldCreditAmount) ?></nobr></label>
        <?= $typeModel->$fieldCreditAmount
                ? number_format($typeModel->$fieldCreditAmount, 2, ',', ' ')
                : ''
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <label><?= $typeModel->getAttributeLabel('paymentOrderCurrExchangeRate') ?></label>
        <?= $typeModel->paymentOrderCurrExchangeRate ?: 'По курсу банка' ?>
    </div>
</div>

<h4>Комиссия</h4>
<div class="row">
    <div class="col-sm-12">
        <label><?= $typeModel->getAttributeLabel('commissionAccount.number') ?></label>
        <?php
            if (isset($typeModel->commissionAccount)) {
                echo $typeModel->commissionAccount->number;
            } else {
                echo '- NOT FOUND -';
            }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <label><?= Yii::t('edm', 'Commission amount') ?></label> По тарифам банка
    </div>
</div>
</div>
