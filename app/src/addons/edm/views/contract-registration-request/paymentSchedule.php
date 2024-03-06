<?php
/**
 * Форма управления элементами графика платежей
 */

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestPaymentSchedule $model */

use yii\widgets\ActiveForm;
use common\helpers\Html;
use yii\widgets\MaskedInput;
use yii\jui\DatePicker;

?>

<?php $form = ActiveForm::begin(
    [
        'id' => 'related-data-form',
        'action' => '/edm/contract-registration-request/process-related-data-form',
        'options' => [
            'data' => [
                'type' => 'paymentSchedule'
            ]
        ]
    ]
); ?>

<div class="row">
    <div class="col-md-12">
        <strong><?=Yii::t('edm', 'Repayment of principal')?></strong>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'mainDeptDate')?>
        <?php MaskedInput::widget([
            'id'            => 'contractregistrationrequestpaymentschedule-maindeptdate',
            'name'          => 'contractregistrationrequestpaymentschedule-maindeptdate',
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
        <?php DatePicker::widget([
            'id'         => 'contractregistrationrequestpaymentschedule-maindeptdate',
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, "mainDeptAmount")->widget(MaskedInput::className(), [
            'clientOptions' => [
                'alias' => 'decimal',
                'digits' => 2,
                'digitsOptional' => false,
                'radixPoint' => '.',
                'autoGroup' => true,
                'removeMaskOnSubmit' => true,
            ]
        ]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <strong><?=Yii::t('edm', 'On account of interest payments')?></strong>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'interestPaymentsDate')?>
        <?php MaskedInput::widget([
            'id'            => 'contractregistrationrequestpaymentschedule-interestpaymentsdate',
            'name'          => 'contractregistrationrequestpaymentschedule-interestpaymentsdate',
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
        <?php DatePicker::widget([
            'id'         => 'contractregistrationrequestpaymentschedule-interestpaymentsdate',
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, "interestPaymentsAmount")->widget(MaskedInput::className(), [
            'clientOptions' => [
                'alias' => 'decimal',
                'digits' => 2,
                'digitsOptional' => false,
                'radixPoint' => '.',
                'autoGroup' => true,
                'removeMaskOnSubmit' => true,
            ]
        ]) ?>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, "comment")->textarea(['rows' => 5]) ?>
    </div>
</div>

<?php

if ($uuid) {
    echo Html::hiddenInput('uuid', $uuid);
}

ActiveForm::end();

?>