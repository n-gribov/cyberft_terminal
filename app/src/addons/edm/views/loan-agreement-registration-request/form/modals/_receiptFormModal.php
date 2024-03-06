<?php

use common\helpers\Countries;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/** @var \addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm\Receipt $model */

$countrySelectOptions = ArrayHelper::map(Countries::getAll(), 'numericCode', 'name');

$amountFieldMaskOptions = [
    'clientOptions' => [
        'alias' => 'decimal',
        'digits' => 2,
        'digitsOptional' => false,
        'radixPoint' => '.',
        'autoGroup' => true,
        'removeMaskOnSubmit' => true,
    ],
];

$shareOfLoanAmountFieldMaskOptions = [
    'clientOptions' => [
        'alias' => 'decimal',
        'radixPoint' => '.',
        'autoGroup' => true,
        'removeMaskOnSubmit' => true,
    ],
];

Modal::begin([
    'id' => 'receipt-form-modal',
    'header' => Html::tag('h4', Yii::t('edm', 'Syndicated loan receipt')),
    'footer' => Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success save-button']),
    'options' => [
        'data' => [
            'backdrop' => 'static',
        ],
    ],
]);

$form = ActiveForm::begin();

echo $form->field($model, 'beneficiaryName');
echo $form->field($model, 'beneficiaryCountryCode')->dropDownList($countrySelectOptions, ['prompt' => '']);
echo $form->field($model, 'amount')->widget(MaskedInput::class, $amountFieldMaskOptions);
echo $form->field($model, 'shareOfLoanAmount')->widget(MaskedInput::class, $shareOfLoanAmountFieldMaskOptions);
echo $form->field($model, 'id', ['template' => '{input}'])->hiddenInput();

ActiveForm::end();
Modal::end();
