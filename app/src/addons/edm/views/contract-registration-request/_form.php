<?php

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt $model */

use addons\edm\models\DictOrganization;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\jui\DatePicker;
use kartik\select2\Select2;
use common\helpers\Html;
use yii\bootstrap\Modal;
use addons\edm\models\DictCurrency;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt;
use yii\helpers\ArrayHelper;
use addons\edm\helpers\EdmHelper;

$this->title = Yii::t('edm', 'Contract registration request');

// Получение списка организаций
$organizations = Yii::$app->terminalAccess->query(DictOrganization::className())->all();

foreach($organizations as $organization) {
    $organizationsList[$organization->id] = $organization->name . ', ИНН: ' . $organization->inn;
}

// Получение списка существующих номеров паспортов сделок
$numbersQuery = ContractRegistrationRequestExt::getRelatedPassportNumbers();

// Не учитывая текущий ПС
if ($model->passportNumber) {
    $numbersQuery->andWhere(['not in', 'passportNumber', $model->passportNumber]);
}

$numbers = $numbersQuery->all();
$passportNumbers = ArrayHelper::map($numbers, 'passportNumber', 'passportNumber');

// Субьекты РФ
$regionsData = EdmHelper::fccRegions();

$regions = [];

foreach($regionsData as $value) {
    $regions[$value] = $value;
}
?>
<?php
    // Вывод ошибок при создании документа
    if ($model->hasErrors()) :
?>
<div class="alert alert-danger">
    <ul>
        <?php
            foreach ($model->errors as $field => $errors) { echo '<li>' . implode(', ', $errors) .'</li>'; }
        ?>
    </ul>
</div>
<?php endif ?>
<?php
$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'edm-crr-form']]);
?>
<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'number')?>
    </div>
    <div class="col-md-1">
        <?= $form->field($model, 'date')?>
        <?php MaskedInput::widget([
            'id'            => 'contractregistrationrequestext-date',
            'name'          => 'contractregistrationrequestext-date',
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
        <?php DatePicker::widget([
            'id'         => 'contractregistrationrequestext-date',
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <?= $form->field($model, 'passportType')->radioList($model::passportTypeLabels(), ['class' => 'test'])->label(false) ?>
    </div>
</div>
<hr>
<h4><?=Yii::t('edm', 'Nonresident info')?></h4>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'organizationId')->widget(Select2::classname(), [
            'data' => $organizationsList,
            'options' => ['placeholder' => 'Выберите организацию', 'prompt' => ''],
            'pluginOptions' => [
                'allowClear' => true,
            ],
            'pluginEvents'  => [
                'select2:select' => 'function(e) {applyOrganization(e.params.data);}',
                'select2:unselect' => 'function(e) {resetOrganization();}'
            ],
        ])?>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'kpp')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'ogrn')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'dateEgrul')->textInput() ?>
        <?php MaskedInput::widget([
            'id'            => 'contractregistrationrequestext-dateegrul',
            'name'          => 'contractregistrationrequestext-dateegrul',
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
        <?php DatePicker::widget([
            'id'         => 'contractregistrationrequestext-dateegrul',
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'state')->widget(Select2::classname(), [
            'data' => $regions,
            'options' => ['prompt' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ]]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'locality')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'district')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'buildingNumber')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'building')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'apartment')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<hr>
<h4><?=Yii::t('edm', 'Details of the nonresidents')?></h4>
<div class="nonresidents">
    <?php
    // Вывести блок уже существующих нерезидентов
    echo $this->render('_nonresidents', ['childObjectData' => $model->nonresidents, 'credit' => false]);
    ?>
</div>
<?=Html::a(Yii::t('app', 'Add'), '#', [
    'class' => 'btn btn-primary btn-new-nonresident',
    'data' => [
        'title' => Yii::t('edm', 'Nonresident')
    ]
])?>
<hr>
<h4><?=Yii::t('edm', 'General information about the contract/loan agreement')?></h4>
<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'passportTypeNumber')->textInput() ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'contractTypeCode')->widget(Select2::classname(), [
            'data' => EdmHelper::fccContractTypeCodes(),
            'options' => ['prompt' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ]])
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
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
    <div class="col-md-2">
        <?= $form->field($model, 'currencyId')->widget(Select2::classname(), [
            'data' => DictCurrency::getValuesFullFormat(),
            'options' => ['placeholder' => 'Выберите валюту', 'prompt' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ]])
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'signingDate')->textInput() ?>
        <?php MaskedInput::widget([
            'id'            => 'contractregistrationrequestext-signingdate',
            'name'          => 'contractregistrationrequestext-signingdate',
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
        <?php DatePicker::widget([
            'id'         => 'contractregistrationrequestext-signingdate',
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'completionDate')->textInput() ?>
        <?php MaskedInput::widget([
            'id'            => 'contractregistrationrequestext-completiondate',
            'name'          => 'contractregistrationrequestext-completiondate',
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
        <?php DatePicker::widget([
            'id'         => 'contractregistrationrequestext-completiondate',
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4 type-loan">
        <?= $form->field($model, 'creditedAccountsAbroad')->textInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4 type-loan">
        <?= $form->field($model, 'repaymentForeignCurrencyEarnings')->textInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4 type-loan">
        <?= $form->field($model, 'codeTermInvolvement')->widget(Select2::classname(), [
            'data' => EdmHelper::fccTermInvolvementCodes(),
            'options' => ['prompt' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ]])
        ?>
    </div>
</div>
<hr class="type-loan">
<h4 class="type-loan"><?=Yii::t('edm', 'Information about trenches')?></h4>
<div class="tranches type-loan">
    <?php
    // Вывести блок уже существующих траншей
    echo $this->render('_tranches', ['childObjectData' => $model->tranches]);
    ?>
</div>
<div class="type-loan">
    <?=Html::a(Yii::t('app', 'Add'), '#', [
        'class' => 'btn btn-primary btn-new-tranche',
        'data' => [
            'title' => Yii::t('edm', 'Tranche')
        ]
    ])?>
</div>
<hr>
<h4><?=$model->getAttributeLabel('existedPassport')?></h4>
<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'existedPassport')->widget(Select2::classname(), [
            'data' => $passportNumbers,
            'options' => ['prompt' => ''],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(false) ?>
    </div>
</div>
<hr>
<h4 class="type-loan"><?=Yii::t(
    'edm',
    'Interest payments under the credit agreement under the credit agreement (except for payments to repay the principal)'
)?></h4>
<div class="row type-loan">
    <div class="col-md-2">
        <?= $form->field($model, 'fixedRate')->widget(MaskedInput::className(), [
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
    <div class="col-md-2">
        <?= $form->field($model, 'codeLibor')->widget(Select2::classname(), [
            'data' => EdmHelper::fccLiborCodes(),
            'options' => ['prompt' => ''],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4 type-loan">
        <?= $form->field($model, 'otherMethodsDeterminingRate')->textarea(['rows' => 5]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-2 type-loan">
        <?= $form->field($model, 'bonusBaseRate')->widget(MaskedInput::className(), [
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
    <div class="col-md-4 type-loan">
        <?= $form->field($model, 'otherPaymentsLoanAgreement')->textarea(['rows' => 5]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4 type-loan">
        <?= $form->field($model, 'amountMainDebt')->widget(MaskedInput::className(), [
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
    <div class="col-md-2 type-loan">
        <?= $form->field($model, 'contractCurrencyId')->widget(Select2::classname(), [
            'data' => DictCurrency::getValuesFullFormat(),
            'options' => ['prompt' => ''],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-7 type-loan">
        <?= $form->field($model, 'reasonFillPaymentsSchedule')->radioList($model::reasonPaymentScheduleLabels()) ?>
    </div>
</div>
<h4 class="type-loan"><?=Yii::t('edm', 'Description of the schedule of payments to repay the principal and interest payments')?></h4>
<div class="payment-schedule type-loan">
    <?php
    // Вывести блок уже существующего график платежей
    echo $this->render('_paymentSchedule', ['childObjectData' => $model->paymentSchedule]);
    ?>
</div>
<div class="type-loan">
    <?=Html::a(Yii::t('app', 'Add'), '#', [
        'class' => 'btn btn-primary btn-new-payment-schedule',
        'data' => [
            'title' => Yii::t('edm', 'Payment schedule')
        ]
    ])?>
</div>
<hr class="type-loan">
<div class="row">
    <div class="col-md-4 type-loan">
        <?= $form->field($model, 'directInvestment')->checkbox() ?>
    </div>
</div>
<div class="row">
    <div class="col-md-2 type-loan">
        <?= $form->field($model, 'amountCollateral')->widget(MaskedInput::className(), [
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
<h4 class="type-loan"><?=Yii::t('edm', 'Information on credits granted by non-residents on a syndicated basis')?></h4>
<div class="nonresidents-credit type-loan">
    <?php
    // Вывести блок уже существующих нерезидентов
    echo $this->render('_nonresidents', ['childObjectData' => $model->nonresidentsCredit, 'credit' => true]);
    ?>
</div>
<div class="type-loan">
    <?=Html::a(Yii::t('app', 'Add'), '#', [
        'class' => 'btn btn-primary btn-new-nonresident-credit',
        'data' => [
            'title' => Yii::t('edm', 'Nonresident')
        ]
    ])?>
</div>
<hr class="type-loan">
<div class="row">
    <div class="col-md-8">
        <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), [
                'class' => 'btn btn-success'])
        ?>
    </div>
</div>
<?php
ActiveForm::end();

$header = "<h4 class='modal-title'></h4>";
$footer = "<button type='button' class='btn btn-default' data-dismiss='modal'>" .Yii::t('app', 'Close') . "</button>" .
    "<button type='button' class='btn btn-primary btn-submit-form'>" . Yii::t('app', 'Save') . "</button>";

$modal = Modal::begin([
    'id' => 'data-modal',
    'header' => $header,
    'footer' => $footer,
    'size' => Modal::SIZE_SMALL,
    'options' => [
        'tabindex' => false,
        'data' => [
            'backdrop' => 'static'
        ]
    ]
]);

$modal::end();

$this->registerCss('
    .type-loan {
        display: none;
    }

   .field-contractregistrationrequestext-othermethodsdeterminingrate textarea {
        resize: none;
    }
');
