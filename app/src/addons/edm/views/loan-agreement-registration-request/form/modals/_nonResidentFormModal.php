<?php

use common\helpers\Countries;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var \addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm\NonResident $model */

$countrySelectOptions = ArrayHelper::map(Countries::getAll(), 'numericCode', 'name');

Modal::begin([
    'id' => 'non-resident-form-modal',
    'header' => Html::tag('h4', Yii::t('edm', 'Non-resident requisites')),
    'footer' => Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success save-button']),
    'options' => [
        'data' => [
            'backdrop' => 'static',
        ],
    ],
]);

$form = ActiveForm::begin();

echo $form->field($model, 'name')->textInput(['maxlength' => 255]);
echo $form->field($model, 'countryCode')
    ->dropDownList($countrySelectOptions, ['prompt' => ''])
    ->label(Yii::t('edm', 'Country'));
echo $form->field($model, 'id', ['template' => '{input}'])->hiddenInput();

ActiveForm::end();
Modal::end();
