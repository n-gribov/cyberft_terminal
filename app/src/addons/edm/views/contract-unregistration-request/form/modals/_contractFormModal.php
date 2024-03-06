<?php

use addons\edm\models\DictVTBContractUnregistrationGround;
use kartik\select2\Select2;
use kartik\widgets\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/** @var \addons\edm\models\ContractUnregistrationRequest\ContractUnregistrationRequestForm\Contract $model */

$uniqueNumberMask = '99999999 / 9999 / 9999 / 9 / 9';
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

$unregistrationGroundCodeSelectOptions = ArrayHelper::map(DictVTBContractUnregistrationGround::all(), 'code', 'name');

Modal::begin([
    'id' => 'contract-form-modal',
    'header' => Html::tag('h4', Yii::t('edm', 'Contract (loan agreement)')),
    'footer' => Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success save-button']),
    'options' => [
        'data' => [
            'backdrop' => 'static',
        ],
    ],
]);

$form = ActiveForm::begin();

echo $form
    ->field($model, 'uniqueContractNumber')
    ->widget(
    MaskedInput::class,
        [
            'mask' => $uniqueNumberMask,
            'clientOptions' => ['removeMaskOnSubmit' => true, 'autoUnmask' => true]
        ]
    );
echo $form->field($model, 'uniqueContractNumberDate')->widget(DateTimePicker::classname(), $dateTimePickerOptions);
echo $form
    ->field($model, 'unregistrationGroundCode')
    ->widget(
        Select2::classname(),
        [
            'hideSearch' => true,
            'data' => $unregistrationGroundCodeSelectOptions,
        ]
    );
echo $form->field($model, 'id', ['template' => '{input}'])->hiddenInput();

ActiveForm::end();
Modal::end();
