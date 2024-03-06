<?php

use addons\edm\models\DictVTBTranchePaymentPeriod;
use kartik\widgets\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/** @var \addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm\Tranche $model */

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

$paymentPeriodCodeSelectOptions = ArrayHelper::map(
    DictVTBTranchePaymentPeriod::all(),
    'code',
    'name'
);

Modal::begin([
    'id' => 'tranche-form-modal',
    'header' => Html::tag('h4', Yii::t('edm', 'Tranche')),
    'footer' => Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success save-button']),
    'options' => [
        'data' => [
            'backdrop' => 'static',
        ],
    ],
]);

$form = ActiveForm::begin();

echo $form->field($model, 'amount')->widget(MaskedInput::class, $amountFieldMaskOptions);
echo $form->field($model, 'paymentPeriodCode')->dropDownList($paymentPeriodCodeSelectOptions, ['prompt' => '-']);
echo $form->field($model, 'receiptDate')->widget(DateTimePicker::classname(), $dateTimePickerOptions);
echo $form->field($model, 'id', ['template' => '{input}'])->hiddenInput();

ActiveForm::end();
Modal::end();
