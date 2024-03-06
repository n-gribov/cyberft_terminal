<?php
/**
 * форма создания/изменения нерезидента
 */

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestNonresident $model */

use yii\widgets\ActiveForm;
use common\helpers\Html;
use addons\edm\helpers\EdmHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

// Тип нерезидента, в зависимости от его вида (обычный/кредитор)
if ($model->isCredit) {
    $formOptions = [
        'data' => [
            'type' => 'nonresidentsCredit'
        ]
    ];
} else {
    $formOptions = [
        'data' => [
            'type' => 'nonresidents'
        ]
    ];
}

$form = ActiveForm::begin(
    [
        'id' => 'related-data-form',
        'action' => '/edm/contract-registration-request/process-related-data-form',
        'options' => $formOptions
    ]
);
?>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'name')->textInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'countryCode')->widget(Select2::classname(), [
            'data' => EdmHelper::countryCodesList(),
            'options' => ['placeholder' => 'Выберите код страны'],
            'pluginOptions' => [
                'allowClear' => true,
                'templateSelection' => new JsExpression('function(item) {
                    return item.id;
              }')
            ],
        ]); ?>
    </div>
</div>
<?= Html::activeHiddenInput($model, 'isCredit') ?>
<?php
// Дополнительные поля для нерезидентов, которые представляют кредит
if ($model->isCredit): ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'amount')->widget(MaskedInput::className(), [
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
            <?= $form->field($model, 'percent')->widget(MaskedInput::className(), [
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
<?php endif ?>
<?php
if ($uuid) {
    echo Html::hiddenInput('uuid', $uuid);
}

ActiveForm::end();
?>