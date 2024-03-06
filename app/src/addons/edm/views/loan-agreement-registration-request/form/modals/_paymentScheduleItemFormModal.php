<?php

use kartik\widgets\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/** @var \addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm\PaymentScheduleItem $model */

$dateTimePickerOptions = [
    'pluginOptions' => [
        'format'         => 'dd.mm.yyyy',
        'todayHighlight' => true,
        'weekStart'      => 1,
        'autoclose'      => true,
        'startView'      => 4,
        'minView'        => 2
    ]
];

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

Modal::begin([
    'id' => 'payment-schedule-item-form-modal',
    'header' => Html::tag('h4', Yii::t('edm', 'Payment')),
    'footer' => Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success save-button']),
    'options' => [
        'data' => [
            'backdrop' => 'static',
        ],
    ],
]);

$form = ActiveForm::begin();

echo $form->field($model, 'debtDate')->widget(DateTimePicker::classname(), $dateTimePickerOptions);
echo $form->field($model, 'debtAmount')->widget(MaskedInput::class, $amountFieldMaskOptions);
echo $form->field($model, 'interestDate')->widget(DateTimePicker::classname(), $dateTimePickerOptions);
echo $form->field($model, 'interestAmount')->widget(MaskedInput::class, $amountFieldMaskOptions);
echo $form->field($model, 'specialConditions')->textarea(['rows' => 2, 'maxlength' => 255]);
echo $form->field($model, 'id', ['template' => '{input}'])->hiddenInput();

ActiveForm::end();
Modal::end();
