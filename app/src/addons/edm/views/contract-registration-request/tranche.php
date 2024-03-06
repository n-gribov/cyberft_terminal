<?php
/**
 * Форма управления траншем
 */

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestTranche $model */

use yii\widgets\ActiveForm;
use common\helpers\Html;
use addons\edm\helpers\EdmHelper;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use yii\jui\DatePicker;

?>

<?php $form = ActiveForm::begin(
    [
        'id' => 'related-data-form',
        'action' => '/edm/contract-registration-request/process-related-data-form',
        'options' => [
            'data' => [
                'type' => 'tranches'
            ]
        ]
    ]
); ?>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, "amount")->widget(MaskedInput::className(), [
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
        <?= $form->field($model, 'termCode')->widget(Select2::classname(), [
            'data' => EdmHelper::fccTermInvolvementCodes(),
            'options' => ['prompt' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ]])
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'date')?>
        <?php MaskedInput::widget([
            'id'            => 'contractregistrationrequesttranche-date',
            'name'          => 'contractregistrationrequesttranche-date',
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
        <?php DatePicker::widget([
            'id'         => 'contractregistrationrequesttranche-date',
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
    </div>
</div>

<?php

if ($uuid) {
    echo Html::hiddenInput('uuid', $uuid);
}

ActiveForm::end();

?>