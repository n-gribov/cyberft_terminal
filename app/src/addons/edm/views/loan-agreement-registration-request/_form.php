<?php

use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictVTBPaymentScheduleReason;
use addons\edm\models\DictVTBRepaymentPeriod;
use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm;
use kartik\widgets\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use \yii\widgets\MaskedInput;

/** @var \yii\web\View $this */
/** @var \addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm $model */
/** @var \addons\edm\models\DictCurrency[] $currencies */

$availableOrganizations = $model->getAvailableOrganizations();
$availableReceiverBanks = $model->getAvailableReceiverBanks();
$organizationSelectOptions = ArrayHelper::map($availableOrganizations, 'id', 'name');
$receiverBankSelectOptions = ArrayHelper::map($availableReceiverBanks, 'bik', 'name');
$repaymentPeriodSelectOptions = ArrayHelper::map(DictVTBRepaymentPeriod::all(), 'code', 'name');
$rateLiborCodeSelectOptions = EdmHelper::fccLiborCodes();
$paymentScheduleReasonOptions = ArrayHelper::map(DictVTBPaymentScheduleReason::all(), 'id', 'name');
$currencySelectOptions = array_reduce(
    $currencies,
    function ($carry, DictCurrency $currency) {
        $carry[$currency->code] = "{$currency->name} - {$currency->description}";
        return $carry;
    },
    []
);

$loanAgreementUniqueNumber4SelectOption = [
    '5' => '5 Договор, условиями которого предусмотрено предоставление резидентом займа',
    '6' => '6 Договор, условиями которого предусмотрено привлечение резидентом кредита (займа)',
];

$loanAgreementUniqueNumber5SelectOption = [
    '1' => '1 Юридическое лицо или его филиал',
    '2' => '2 Физическое лицо - индивидуальный предприниматель',
    '3' => '3 Физическое лицо, занимающееся в установленном законодательством Российской Федерации порядке частной практикой',
];

$dateTimePickerOptions = [
    'pluginOptions' => [
        'format'         => 'dd.mm.yyyy',
        'todayHighlight' => true,
        'weekStart'      => 1,
        'autoclose'      => true,
        'startView'      => 4,
        'minView'        => 2,
    ],
    'options' => ['class' => 'form-control', 'style' => 'width: 100px;'],
];
$amountFieldMaskOptions = [
    'clientOptions' => [
        'alias' => 'decimal',
        'digits' => 2,
        'digitsOptional' => false,
        'radixPoint' => '.',
        'groupSeparator' => ' ',
        'autoGroup' => true,
        'autoUnmask' => true,
        'removeMaskOnSubmit' => true,
    ],
    'options' => ['class' => 'form-control', 'style' => 'width: 200px; display: inline;'],
];

$form = ActiveForm::begin(['id' => 'loan-agreement-registration-request-form']); ?>

<fieldset class="form-inline">
    <?= $form->field($model, 'documentNumber') ?>
    <?= $form
        ->field($model, 'documentDate')
        ->widget(DateTimePicker::class, $dateTimePickerOptions)
        ->label(Yii::t('edm', 'Date'))
    ?>
</fieldset>

<hr>

<h4><?= Yii::t('edm','Loan agreement unique number') ?></h4>
<fieldset class="form-inline loan-agreement-unique-number-fields">
    <?= $form
        ->field($model, 'loanAgreementUniqueNumber1')
        ->widget(
            MaskedInput::class,
            ['mask' => '99999999', 'clientOptions' => ['removeMaskOnSubmit' => true, 'autoUnmask' => true]]
        )
        ->label(false)
        ->hint('/', ['class' => 'hint'])
    ?>
    <?= $form
        ->field($model, 'loanAgreementUniqueNumber2')
        ->widget(
            MaskedInput::class,
            ['mask' => '9999', 'clientOptions' => ['removeMaskOnSubmit' => true, 'autoUnmask' => true]]
        )
        ->label(false)
        ->hint('/', ['class' => 'hint'])
    ?>
    <?= $form
        ->field($model, 'loanAgreementUniqueNumber3')
        ->widget(
            MaskedInput::class,
            ['mask' => '9999', 'clientOptions' => ['removeMaskOnSubmit' => true, 'autoUnmask' => true]]
        )
        ->label(false)
        ->hint('/', ['class' => 'hint'])
    ?>
    <?= $form
        ->field($model, 'loanAgreementUniqueNumber4')
        ->dropDownList($loanAgreementUniqueNumber4SelectOption, ['prompt' => '-'])
        ->label(false)
        ->hint('/', ['class' => 'hint'])
    ?>
    <?= $form
        ->field($model, 'loanAgreementUniqueNumber5')
        ->dropDownList($loanAgreementUniqueNumber5SelectOption, ['prompt' => '-'])
        ->label(false)
    ?>
</fieldset>
<hr>

<h4><?= Yii::t('edm','Resident info') ?></h4>
<?= $form
    ->field($model, 'organizationId')
    ->dropDownList($organizationSelectOptions, ['prompt' => count($organizationSelectOptions) > 1 ? '-' : null])
?>
<?php foreach ($availableOrganizations as $organization) : ?>
    <div class="organization-info hidden" data-id="<?= Html::encode($organization->id) ?>">
        <p>
            <strong><?= Yii::t('edm', 'INN') ?></strong>
            <span class="value"><?= Html::encode($organization->inn) ?></span>

            <strong><?= Yii::t('edm', 'KPP') ?></strong>
            <span class="value"><?= Html::encode($organization->kpp) ?></span>

            <strong><?= Yii::t('edm', 'OGRN') ?></strong>
            <span class="value"><?= Html::encode($organization->ogrn) ?></span>

            <strong><?= Yii::t('edm', 'Date of entry to EGRUL') ?></strong>
            <span class="value"><?= Html::encode($organization->dateEgrul) ?></span>
        </p>
        <p>
            <strong><?= Yii::t('edm', 'Address') ?></strong>
            <span class="value"><?= Html::encode($organization->fullAddress) ?></span>
        </p>
    </div>
<?php endforeach ?>

<fieldset class="form-inline">
    <?= $form->field($model, 'contactPerson')->textInput(['maxlength' => 40, 'size' => 42]) ?>
    <?= $form->field($model, 'contactPhone')->textInput(['maxlength' => 20]) ?>
</fieldset>

<?= $form
    ->field($model, 'receiverBankBik')
    ->dropDownList($receiverBankSelectOptions, ['prompt' => count($receiverBankSelectOptions) > 1 ? '-' : null])
?>

<hr>

<h4><?= Yii::t('edm', 'Non-residents requisites') ?></h4>
<?= // Вывести страницу
    $this->render('form/grid-views/_nonResidentsGridView', ['models' => $model->nonResidents]) ?>
<?= Html::button(
    Yii::t('app', 'Add'),
    [
        'id' => 'add-non-resident-button',
        'class' => 'btn btn-primary',
    ]
) ?>
<hr>

<h4><?= Yii::t('edm', 'Information about previous unique loan agreement number') ?></h4>
<fieldset class="form-inline">
    <?= $form
        ->field($model, 'previousLoanAgreementUniqueNumber')
        ->textInput(['maxlength' => 50])
        ->label(false)
    ?>
</fieldset>
<hr>

<h4><?= Yii::t('edm', 'General loan agreement information') ?></h4>
<fieldset class="form-inline">
    <?= $form->field($model, 'loanAgreementNumber')->label(Yii::t('edm', 'Number')) ?>
    <?= $form->field($model, 'noLoanAgreementNumber')->checkbox()->label(Yii::t('edm', 'No number')) ?>
</fieldset>

<fieldset class="form-inline">
    <?= $form
        ->field($model, 'loanAgreementDate')
        ->widget(DateTimePicker::class, $dateTimePickerOptions)
        ->label(Yii::t('edm', 'Date'))
    ?>
    <?= $form
        ->field($model, 'loanAgreementEndDate')
        ->widget(DateTimePicker::class, $dateTimePickerOptions)
    ?>
</fieldset>

<fieldset class="form-inline">
    <?= $form
        ->field($model, 'loanAgreementAmount')
        ->widget(
            MaskedInput::class,
            $amountFieldMaskOptions
        )
    ?>
    <?= $form
        ->field($model, 'noLoanAgreementAmount')
        ->checkbox()
        ->label(Yii::t('edm', 'No amount'))
    ?>
</fieldset>
<fieldset class="form-inline">
    <?= $form
        ->field($model, 'loanAgreementCurrencyCode')
        ->dropDownList($currencySelectOptions, ['prompt' => ''])
    ?>
</fieldset>
<hr>

<h4><?= Yii::t('edm', 'Special conditions') ?></h4>
<fieldset class="form-inline">
    <?= $form
        ->field($model, 'foreignAccountsTransferAmount')
        ->widget(
            MaskedInput::class,
            $amountFieldMaskOptions
        )
        ->hint($model->loanAgreementCurrencyName ?: ' ', ['style' => 'display: inline', 'class' => 'help-block currency-name'])
    ?>
    <br>
    <?= $form
        ->field($model, 'currencyIncomeRepaymentAmount')
        ->widget(
            MaskedInput::class,
            $amountFieldMaskOptions
        )
        ->hint($model->loanAgreementCurrencyName ?: ' ', ['style' => 'display: inline', 'class' => 'help-block currency-name'])
    ?>
</fieldset>
<hr>
<fieldset class="form-inline">
    <?= $form
        ->field($model, 'repaymentPeriodCode')
        ->dropDownList($repaymentPeriodSelectOptions, ['prompt' => ''])
        ->label(Yii::t('edm', 'Repayment period'))
    ?>
</fieldset>
<hr>

<h4><?= Yii::t('edm', 'Information about amounts and dates of used tranches') ?></h4>
<?= // Вывести страницу
    $this->render(
    'form/grid-views/_tranchesGridView',
    [
        'models' => $model->tranches,
        'params' => ['currencyName' => $model->loanAgreementCurrencyName]
    ]
) ?>
<?= Html::button(
    Yii::t('app', 'Add'),
    [
        'id' => 'add-tranche-button',
        'class' => 'btn btn-primary',
    ]
) ?>
<hr>

<h4><?= Yii::t('edm', 'Loan agreement interest payments (excluding main debt payments)') ?></h4>
<fieldset class="form-inline">
    <?= $form
        ->field($model, 'fixedInterestRatePercent')
        ->textInput(['class' => 'form-control text-right'])
        ->hint('%', ['style' => 'display: inline'])
    ?>
    <?= $form
        ->field($model, 'fixedInterestRateLiborCode')
        ->dropDownList($rateLiborCodeSelectOptions, ['prompt' => ''])
    ?>
</fieldset>

<?= $form->field($model, 'otherPercentRateCalculationMethod')->textarea(['rows' => 3]) ?>
<?= $form
    ->field(
        $model,
        'increaseRatePercent',
        ['labelOptions' => ['style' => 'display: block']]
    )
    ->textInput(['class' => 'form-control text-right', 'style' => 'width: 200px; display: inline;'])
    ->hint(Yii::t('edm', '% per annum'), ['style' => 'display: inline'])
?>
<hr>

<?= $form->field($model, 'otherPayments')->textInput(['maxlength' => 255]) ?>
<hr>

<?= $form
    ->field(
        $model,
        'mainDebtAmount',
        ['labelOptions' => ['style' => 'display: block']]
    )
    ->widget(
        MaskedInput::class,
        $amountFieldMaskOptions
    )
    ->hint($model->loanAgreementCurrencyName ?: ' ', ['style' => 'display: inline', 'class' => 'help-block currency-name'])
?>
<hr>

<fieldset class="form-inline">
<?= $form
    ->field($model, 'paymentScheduleReason')
    ->radioList($paymentScheduleReasonOptions);
?>
</fieldset>
<hr>

<h4><?= Yii::t('edm', 'Information about main debt and interest payments schedule') ?></h4>
<?= // Вывести страницу
    $this->render('form/grid-views/_paymentScheduleGridView', ['models' => $model->paymentScheduleItems]) ?>
<?= Html::button(
    Yii::t('app', 'Add'),
    [
        'id' => 'add-payment-schedule-item-button',
        'class' => 'btn btn-primary',
    ]
) ?>
<hr>

<?= $form
    ->field($model, 'isDirectInvesting')
    ->checkbox();
?>
<hr>

<?= $form
    ->field(
        $model,
        'depositAmount',
        ['labelOptions' => ['style' => 'display: block']]
    )
    ->widget(
        MaskedInput::class,
        $amountFieldMaskOptions
    )
    ->hint($model->loanAgreementCurrencyName ?: ' ', ['style' => 'display: inline', 'class' => 'help-block currency-name'])
?>
<hr>

<h4><?= Yii::t('edm', 'Information about receiving syndicated loan by resident') ?></h4>
<?= // Вывести страницу
    $this->render('form/grid-views/_receiptsGridView', ['models' => $model->receipts]) ?>
<?= Html::button(
    Yii::t('app', 'Add'),
    [
        'id' => 'add-receipt-button',
        'class' => 'btn btn-primary',
    ]
) ?>
<hr>

<h4><?= Yii::t('edm', 'Attached files') ?></h4>
<?= // Вывести страницу
    $this->render('form/grid-views/_attachedFilesGridView', ['models' => $model->attachedFiles]) ?>
<?= Html::button(
    Yii::t('app', 'Add'),
    [
        'id' => 'add-attached-file-button',
        'class' => 'btn btn-primary',
        'data' => ['loading-text' => '<i class=\'fa fa-spinner fa-spin\'></i> ' . Yii::t('app', 'Add')]
    ]
) ?>
<hr>
<?= $form->field($model, 'nonResidentsJson', ['template' => '{input}'])->hiddenInput() ?>
<?= $form->field($model, 'paymentScheduleItemsJson', ['template' => '{input}'])->hiddenInput() ?>
<?= $form->field($model, 'receiptsJson', ['template' => '{input}'])->hiddenInput() ?>
<?= $form->field($model, 'tranchesJson', ['template' => '{input}'])->hiddenInput() ?>
<?= $form->field($model, 'attachedFilesJson', ['template' => '{input}'])->hiddenInput() ?>
<div>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>
<form action="upload-attached-file" id="upload-attached-file-form" enctype="multipart/form-data">
    <input type="file" name="file" class="hidden" />
</form>
<?php
// Вывести модальное окно
echo $this->render('form/modals/_nonResidentFormModal', ['model' => new LoanAgreementRegistrationRequestForm\NonResident()]);
// Вывести модальное окно
echo $this->render('form/modals/_paymentScheduleItemFormModal', ['model' => new LoanAgreementRegistrationRequestForm\PaymentScheduleItem()]);
// Вывести модальное окно
echo $this->render('form/modals/_receiptFormModal', ['model' => new LoanAgreementRegistrationRequestForm\Receipt()]);
// Вывести модальное окно
echo $this->render('form/modals/_trancheFormModal', ['model' => new LoanAgreementRegistrationRequestForm\Tranche()]);

$this->registerJsFile(
    '@web/js/edm/loan-agreement-registration-request/loan-agreement-registration-request-form.js',
    ['depends' => [JqueryAsset::className()]]
);

$currencyNames = ArrayHelper::map($currencies, 'code', 'name');
echo '<script>var currencyNames = ' . json_encode($currencyNames) .'</script>';

$this->registerCss(<<<CSS
    .form-inline .form-group {
        margin-right: 10px;
    }
    .input-group-addon .glyphicon {
        font-size: 18px;
    }
    .has-error .input-group-addon {
        background-color: #f2dede;
    }
    #loan-agreement-registration-request-form input[name="LoanAgreementRegistrationRequestForm[loanAgreementUniqueNumber1]"] {
        width: 90px;
    }
    #loan-agreement-registration-request-form input[name="LoanAgreementRegistrationRequestForm[loanAgreementUniqueNumber2]"],
    #loan-agreement-registration-request-form input[name="LoanAgreementRegistrationRequestForm[loanAgreementUniqueNumber3]"] {
        width: 60px;
    }
    #loan-agreement-registration-request-form select[name="LoanAgreementRegistrationRequestForm[loanAgreementUniqueNumber4]"],
    #loan-agreement-registration-request-form select[name="LoanAgreementRegistrationRequestForm[loanAgreementUniqueNumber5]"] {
        width: 54px;
    }
    #loan-agreement-registration-request-form input[name="LoanAgreementRegistrationRequestForm[previousLoanAgreementUniqueNumber]"] {
        width: 400px;
    }
    #loan-agreement-registration-request-form .form-inline .checkbox {
        padding-top: 6px;
        vertical-align: text-bottom;
    }
    .form-inline .form-group {
        margin-right: 30px;
        vertical-align: top;
    }
    .form-inline .control-label {
        margin-right: 5px;
    }
    .organization-info .value {
        margin-right: 15px;
    }
    #loan-agreement-registration-request-form .form-inline .field-loanagreementregistrationrequestform-loanagreementnumber,
    #loan-agreement-registration-request-form .form-inline .field-loanagreementregistrationrequestform-loanagreementamount {
        height: 45px;
        line-height: 34px;
        margin-right: 10px;
    }
    .has-success .checkbox {
        color: black;
    }
    form hr {
        margin: 7px 0;
    }
    .loan-agreement-unique-number-fields .hint {
        display: inline;
    }
    .loan-agreement-unique-number-fields .form-group {
        margin-right: 0;
    }
    .field-loanagreementregistrationrequestform-loanagreementuniquenumber4 .help-block {
        white-space: nowrap;
    }
    .field-loanagreementregistrationrequestform-loanagreementuniquenumber4 {
        max-width: 62px;
    }
CSS
);
