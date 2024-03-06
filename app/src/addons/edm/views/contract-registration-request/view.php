<?php

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt $model */

use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt;
use common\widgets\TransportInfo\TransportInfoModal;
use addons\edm\helpers\EdmHelper;
use yii\helpers\Url;

$this->title = Yii::t('edm', 'Contract registration request') . ' #' . $document->id . ' - ' . ContractRegistrationRequestExt::getPassportTypeLabel($model->passportType);

// Список кодов стран
$countryCodesList = EdmHelper::countryCodesList();

// Вывести шаблон отображения управляющих кнопок
echo $this->render('@addons/edm/views/documents/_fccViewContent', [
    'model' => $model,
    'document' => $document,
    'type' => 'crr',
    'backUrl' => $backUrl,
    'printUrl' => '/edm/contract-registration-request/print',
    'updateUrl' => '/edm/contract-registration-request/update',
    'deleteUrl' => '/edm/contract-registration-request/delete',
    'sendUrl' => '/edm/contract-registration-request/send',
    'afterSignUrl' => Url::to(['/edm/documents/foreign-currency-control-index?tabMode=tabCRR']),
    'rejectSignUrl' => '/edm/documents/foreign-currency-control/reject-signing',
    'exportExcelUrl' => '/edm/export/export-fcc',
    'beforeSigningUrl' => Url::to(['/edm/contract-registration-request/before-signing', 'id' => $document->id]),
]);

// Определение типа - кредитный договор
$isLoan = $model->passportType == $model::PASSPORT_TYPE_LOAN;
?>
<div class="row margin-bottom-15">
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('number')?>: </strong><?=$model->number ?>
    </div>
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('date')?>: </strong><?=$model->date ?>
    </div>
</div>

<div class="row margin-bottom-15">
    <div class="col-md-5">
        <strong><?=$model->getAttributeLabel('passportNumber')?>: </strong><?=$model->passportNumber ?>
    </div>
</div>

<hr>

<h4><?=Yii::t('edm', 'Nonresident info')?></h4>

<div class="row margin-bottom-15">
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('organizationId')?>: </strong><?=$model->organization->name ?>
    </div>
</div>

<div class="row margin-bottom-15">
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('inn')?>: </strong><?=$model->inn ?>
    </div>
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('kpp')?>: </strong><?=$model->kpp ?>
    </div>
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('ogrn')?>: </strong><?=$model->ogrn ?>
    </div>
    <div class="col-md-3">
        <strong><?=$model->getAttributeLabel('dateEgrul')?>: </strong><?=$model->dateEgrul ?>
    </div>
</div>

<div class="row margin-bottom-15">
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('state')?>: </strong><?=$model->state ?>
    </div>
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('city')?>: </strong><?=$model->city ?>
    </div>
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('locality')?>: </strong><?=$model->locality ?>
    </div>
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('district')?>: </strong><?=$model->district ?>
    </div>
</div>

<div class="row margin-bottom-15">
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('street')?>: </strong><?=$model->street ?>
    </div>
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('buildingNumber')?>: </strong><?=$model->buildingNumber ?>
    </div>
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('building')?>: </strong><?=$model->building ?>
    </div>
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('apartment')?>: </strong><?=$model->apartment ?>
    </div>
</div>

<hr>

<h4><?=Yii::t('edm', 'Details of the nonresidents')?></h4>

<table class="table">
    <tr>
        <th><?=Yii::t('edm', 'Nonresident')?></th>
        <th><?=Yii::t('edm', 'Country code')?></th>
        <th><?=Yii::t('app/participant', 'Country')?></th>
    </tr>
    <?php foreach($model->nonresidentsItems as $nonresident) : ?>
        <tr>
            <td><?=$nonresident->name?></td>
            <td><?=$nonresident->countryCode?></td>
            <td><?=$nonresident->numericCountryCode?></td>
        </tr>
    <?php endforeach ?>
</table>

<hr>

<h4><?=Yii::t('edm', 'General information about the contract/loan agreement')?></h4>

<div class="row margin-bottom-15">
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('passportTypeNumber')?>: </strong><?=$model->passportTypeNumber ?>
    </div>

    <?php if (isset(EdmHelper::fccContractTypeCodes()[$model->contractTypeCode])) : ?>
        <div class="col-md-2">
            <strong><?=$model->getAttributeLabel('contractTypeCode')?>: </strong><?=EdmHelper::fccContractTypeCodes()[$model->contractTypeCode] ?>
        </div>
    <?php endif ?>
</div>

<div class="row margin-bottom-15">
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('amount')?>: </strong><?=$model->amount ?>
    </div>

    <?php if ($model->currencyId) : ?>
        <div class="col-md-3">
            <strong><?=$model->getAttributeLabel('currencyId')?>: </strong><?=$model->currency->name ?>
        </div>
    <?php endif ?>
</div>

<div class="row margin-bottom-15">
    <div class="col-md-2">
        <strong><?=$model->getAttributeLabel('signingDate')?>: </strong><?=$model->signingDate ?>
    </div>
    <div class="col-md-3">
        <strong><?=$model->getAttributeLabel('completionDate')?>: </strong><?=$model->completionDate ?>
    </div>
</div>

<?php if ($isLoan) : ?>
    <div class="row margin-bottom-15">
        <div class="col-md-12">
            <strong><?=$model->getAttributeLabel('creditedAccountsAbroad')?>: </strong><?=$model->creditedAccountsAbroad ?>
        </div>
    </div>
    <div class="row margin-bottom-15">
        <div class="col-md-12">
            <strong><?=$model->getAttributeLabel('repaymentForeignCurrencyEarnings')?>: </strong><?=$model->repaymentForeignCurrencyEarnings ?>
        </div>
    </div>

    <?php if (isset(EdmHelper::fccTermInvolvementCodes()[$model->codeTermInvolvement])) : ?>
        <div class="row margin-bottom-15">
            <div class="col-md-12">
                <strong><?=$model->getAttributeLabel('codeTermInvolvement')?>: </strong><?=EdmHelper::fccTermInvolvementCodes()[$model->codeTermInvolvement] ?>
            </div>
        </div>
    <?php endif ?>

    <hr>

    <?php if ($model->tranchesItems) : ?>
        <h4><?=Yii::t('edm', 'Information about trenches')?></h4>

        <table class="table">
            <tr>
                <th><?=Yii::t('edm', "Tranche's amount")?></th>
                <th><?=Yii::t('edm', "Tranche's term code")?></th>
                <th><?=Yii::t('edm', 'The expected date of tranche')?></th>
            </tr>
            <?php foreach($model->tranchesItems as $tranche) : ?>
                <tr>
                    <td><?=$tranche->amount?></td>
                    <td><?=$tranche->termCode?></td>
                    <td><?=$tranche->date?></td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
<?php endif ?>

<hr>

<h4><?=$model->getAttributeLabel('existedPassport')?></h4>

<div class="row margin-bottom-15">
    <div class="col-md-2">
        <?=$model->existedPassport ?>
    </div>
</div>
<hr>

<?php if ($isLoan) : ?>
    <h4><?=Yii::t('edm', 'Interest payments under the credit agreement under the credit agreement (except for payments to repay the principal)
')?></h4>
    <div class="row margin-bottom-15">
        <div class="col-md-3">
            <strong><?=$model->getAttributeLabel('fixedRate')?>: </strong><?=$model->fixedRate ?>
        </div>

        <?php if (isset(EdmHelper::fccLiborCodes()[$model->codeLibor])) : ?>
            <div class="col-md-5">
                <strong><?=$model->getAttributeLabel('codeLibor')?>: </strong><?=EdmHelper::fccLiborCodes()[$model->codeLibor] ?>
            </div>
        <?php endif ?>
    </div>

    <div class="row margin-bottom-15">
        <div class="col-md-5">
            <strong><?=$model->getAttributeLabel('otherMethodsDeterminingRate')?>: </strong><?=$model->otherMethodsDeterminingRate ?>
        </div>
    </div>

    <div class="row margin-bottom-15">
        <div class="col-md-12">
            <strong><?=$model->getAttributeLabel('bonusBaseRate')?>: </strong><?=$model->bonusBaseRate ?>
        </div>
    </div>

    <div class="row margin-bottom-15">
        <div class="col-md-5">
            <strong><?=$model->getAttributeLabel('otherPaymentsLoanAgreement')?>: </strong><?=$model->otherPaymentsLoanAgreement ?>
        </div>
    </div>

    <div class="row margin-bottom-15">
        <div class="col-md-5">
            <strong><?=$model->getAttributeLabel('amountMainDebt')?>: </strong><?=$model->amountMainDebt ?>
        </div>
    </div>

    <?php if ($model->contractCurrency) : ?>
        <div class="row margin-bottom-15">
            <div class="col-md-5">
                <strong><?=$model->getAttributeLabel('contractCurrencyId')?>:</strong>
                <?=$model->contractCurrency->name . ' - ' . $model->contractCurrency->description?>
            </div>
        </div>
    <?php endif ?>

    <?php if (isset($model::reasonPaymentScheduleLabels()[$model->reasonFillPaymentsSchedule])) : ?>
        <div class="row margin-bottom-15">
            <div class="col-md-5">
                <strong><?=$model->getAttributeLabel('reasonFillPaymentsSchedule')?>:</strong>
                <?=$model::reasonPaymentScheduleLabels()[$model->reasonFillPaymentsSchedule]?>
            </div>
        </div>
    <?php endif ?>

    <?php if ($model->paymentScheduleItems) : ?>
        <h4><?=Yii::t('edm', 'Description of the schedule of payments to repay the principal and interest payments')?></h4>

        <table class="table">
            <tr>
                <th colspan="2"><?=Yii::t('edm', 'Repayment of principal')?></th>
                <th colspan="2"><?=Yii::t('edm', 'On account of interest payments')?></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th><?=Yii::t('edm', "Date")?></th>
                <th><?=Yii::t('edm', "Amount")?></th>
                <th><?=Yii::t('edm', "Date")?></th>
                <th><?=Yii::t('edm', "Amount")?></th>
                <th></th>
                <th></th>
            </tr>
            <?php foreach($model->paymentScheduleItems as $paymentSchedule) : ?>
                <tr>
                    <td><?=$paymentSchedule->mainDeptDate?></td>
                    <td><?=$paymentSchedule->mainDeptAmount?></td>
                    <td><?=$paymentSchedule->interestPaymentsDate?></td>
                    <td><?=$paymentSchedule->interestPaymentsAmount?></td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>

    <?php if ($model->directInvestment) : ?>
        <div class="row margin-bottom-15">
            <div class="col-md-5">
                <strong><span class="glyphicon glyphicon-ok"></span> <?=$model->getAttributeLabel('directInvestment')?></strong>
            </div>
        </div>
    <?php endif ?>

    <div class="row margin-bottom-15">
        <div class="col-md-5">
            <strong><?=$model->getAttributeLabel('amountCollateral')?>: </strong><?=$model->amountCollateral ?>
        </div>
    </div>

    <?php if ($model->nonresidentsCreditItems) : ?>
        <h4 class="type-loan"><?=Yii::t('edm', 'Information on credits granted by non-residents on a syndicated basis')?></h4>

        <table class="table">
            <tr>
                <th><?=Yii::t('edm', 'Nonresident')?></th>
                <th><?=Yii::t('edm', 'Country code')?></th>
                <th><?=Yii::t('app/participant', 'Country')?></th>
                <th><?=Yii::t('edm', 'Amount')?></th>
                <th><?=Yii::t('edm', 'Nonresident percent')?></th>
            </tr>
            <?php foreach($model->nonresidentsCreditItems as $nonresident) : ?>
                <tr>
                    <td><?=$nonresident->name?></td>
                    <td><?=$nonresident->countryCode?></td>
                    <td><?=$countryCodesList[$nonresident->countryCode]?></td>
                    <td><?=$nonresident->amount?></td>
                    <td><?=$nonresident->percent?></td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>
<?php endif ?>

<?php
    if ($model->document) {
        echo TransportInfoModal::widget(['document' => $model->document, 'isVolatile' => false]);
    }
?>
