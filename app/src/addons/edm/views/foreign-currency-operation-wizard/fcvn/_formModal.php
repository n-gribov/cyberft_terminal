<?php

use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\widgets\MaskedInput;

// если эта форма редактирования
if (isset($id)) {
    $formId = 'fcoUpdateForm';
    $formAction = "/edm/foreign-currency-operation-wizard/update?id={$id}&type={$model->operationType}";
} else {
    $formId = 'fcoForm';
    $formAction = "/edm/foreign-currency-operation-wizard/create?type={$model->operationType}";
}

$form = ActiveForm::begin([
    'id'        => $formId,
    'action' => $formAction,
    'enableClientValidation' => false,
    'enableAjaxValidation'   => true,
]);

$dateFieldId = 'foreigncurrencyconversion-date';

// Получение списка организаций
$organizations = Yii::$app->terminalAccess->query(DictOrganization::className())->all();
$organizationsList = ArrayHelper::map($organizations, 'id', 'name');

// Значение по умолчанию для поля телефона
$contactPersonPhoneOptions = ['class' => 'form-control'];

if ($model->contactPersonPhone) {
    $contactPersonPhoneOptions['value'] = $model->contactPersonPhone;
} else {
    $contactPersonPhoneOptions['value'] = '+7';
}

?>

<h4 class="fcp-main-header">Операция</h4>
<div class="row">
    <div class="col-sm-12">
        <?= $form->field($model, 'operationType')->dropDownList(
            ForeignCurrencyOperationFactory::getOperationTypes(),
            ['class' => 'body-select form-control', 'disabled' => true]
        )->label(false)?>
    </div>
</div>

<div class="row padding-left-20">
    <div class="col-md-4">
        <?=$form->field($model, "number")->textInput( [
            'maxlength' => true,
            'class' => 'form-control validate-mask'
        ]);?>
    </div>

    <div class="col-md-3">
        <?php MaskedInput::widget([
            'id'            => $dateFieldId,
            'name'          => $dateFieldId,
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
        <?php DatePicker::widget([
            'id'         => $dateFieldId,
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
        <?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<hr class="fcvn-hr">

<div class="row">
    <div class="col-md-12">
        <?php
        echo $form->field($model, 'organizationId')->widget(Select2::classname(), [
            'options'       => ['placeholder' => '', 'class' => 'has-success'],
            'theme' => Select2::THEME_BOOTSTRAP,
            'data' => $organizationsList,
            'pluginEvents'  => [
                'select2:select' => 'function(e) { fcvn_applyOrganization(); }',
            ],
        ]);
        ?>
    </div>

    <div class="organization-data-block clearfix form-group">
        <div class="form-group clearfix">
            <div class="col-md-3">
                <strong>ИНН</strong>
                <span class="fcvn-org-inn"></span>
            </div>
            <div class="col-md-3">
                <strong>КПП</strong>
                <span class="fcvn-org-kpp"></span>
            </div>
        </div>
        <div class="col-md-12">
            <strong>Адрес</strong>
            <span class="fcvn-org-address"></span>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'contactPersonName')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'contactPersonPhone')->widget(MaskedInput::className(), [
            'mask' => '+9 (999) 999-99-99',
            'options' => $contactPersonPhoneOptions,
            'clientOptions' => [
                'placeholder' => '+7 (___) ___-__-__',
            ]
        ]) ?>
    </div>
</div>

<hr class="fcvn-hr">

<div class="row">
    <div class="col-md-8">
        <?php
        if (isset($model->debitAccount)) {
            $debitAccount = EdmPayerAccount::findOne(['number' => $model->debitAccount]);
        } else {
            $debitAccount = null;
        }
        ?>

        <?= $form->field($model, 'debitAccount')->widget(Select2::className(), [
            'options'       => [
                'placeholder' => Yii::t('edm', 'Search by title or account number ...'),
            ],
            'initValueText' => (!empty($debitAccount) ? $debitAccount->name . ', ' . $debitAccount->number : ''
            ),
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => new JsExpression('fcvn_getDebitAccounts'),
                    'dataType' => 'json',
                    'delay'    => 250,
                    'data'     => new JsExpression('function(params) { return { q:params.term }; }'),
                ],
                'templateResult' => new JsExpression('function(item) {
                    if (!item.number) return item.text; return item.name + ", " + item.number;
                }'),
                'templateSelection'=> new JsExpression('function(item) {
                    if (!item.number) return item.text; return item.name + ", " + item.number;
                }'),
            ],
            'pluginEvents'  => [
                'select2:select' => 'function(e) { fcvn_applyDebitAccount(e.params.data); }',
                "select2:unselect" => "function() { fcvn_resetDebitAccount() }"
            ],
        ]) ?>
    </div>
    <div class="col-md-1">
        <div class="fcvn-debit-account-currency">
            <?=$debitAccount ? $debitAccount->edmDictCurrencies->name : '' ?>
        </div>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'debitAmount', ['enableClientValidation' => false, 'enableAjaxValidation' => false])->widget(MaskedInput::className(), [
            'options' => [
                'class' => 'form-control',
                'maxlength' => 19
            ],
            'clientOptions' => [
                'alias' => 'decimal',
                'digits' => 2,
                'digitsOptional' => false,
                'radixPoint' => '.',
                'groupSeparator' => ' ',
                'autoGroup' => true,
                'autoUnmask' => true,
                'removeMaskOnSubmit' => true,
            ]
        ]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <?php
        if (isset($model->creditAccount)) {
            $creditAccount = EdmPayerAccount::findOne(['number' => $model->creditAccount]);
        } else {
            $creditAccount = null;
        }
        ?>

        <?= $form->field($model, 'creditAccount')->widget(Select2::className(), [
            'options'       => [
                'placeholder' => Yii::t('edm', 'Search by title or account number ...'),
            ],
            'initValueText' => (!empty($creditAccount) ? $creditAccount->name . ', ' . $creditAccount->number : ''
            ),
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => new JsExpression('fcvn_getCreditAccounts'),
                    'dataType' => 'json',
                    'delay'    => 250,
                    'data'     => new JsExpression('function(params) { return { q:params.term }; }'),
                ],
                'templateResult' => new JsExpression('function(item) {
                    if (!item.number) return item.text; return item.name + ", " + item.number;
                }'),
                'templateSelection'=> new JsExpression('function(item) {
                    if (!item.number) return item.text; return item.name + ", " + item.number;
                }'),
            ],
            'pluginEvents'  => [
                'select2:select' => 'function(e) { fcvn_applyCreditAccount(e.params.data); }',
                "select2:unselect" => "function() { fcvn_resetCreditAccount() }"
            ],
        ]) ?>
    </div>
    <div class="col-md-1">
        <div class="fcvn-credit-account-currency">
            <?=$creditAccount ? $creditAccount->edmDictCurrencies->name : '' ?>
        </div>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'creditAmount', [
            'enableClientValidation' => false, 'enableAjaxValidation' => false])->widget(MaskedInput::className(), [
            'options' => [
                'class' => 'form-control',
                'maxlength' => 19
            ],
            'clientOptions' => [
                'alias' => 'decimal',
                'digits' => 2,
                'digitsOptional' => false,
                'radixPoint' => '.',
                'groupSeparator' => ' ',
                'autoGroup' => true,
                'autoUnmask' => true,
                'removeMaskOnSubmit' => true,
            ]
        ]) ?>
    </div>
</div>

<hr class="fcvn-hr">

<div class="row">
    <div class="col-md-12">
        <?php
        if (isset($model->commissionAccount)) {
            $commissionAccount = EdmPayerAccount::findOne(['number' => $model->commissionAccount]);
        } else {
            $commissionAccount = null;
        }
        ?>

        <?= $form->field($model, 'commissionAccount')->widget(Select2::className(), [
            'options'       => [
                'placeholder' => Yii::t('edm', 'Search by title or account number ...'),
            ],
            'initValueText' => (!empty($commissionAccount) ? $commissionAccount->name . ', ' . $commissionAccount->number : ''
            ),
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => new JsExpression('fcvn_getAccounts'),
                    'dataType' => 'json',
                    'delay'    => 250,
                    'data'     => new JsExpression('function(params) { return { q:params.term }; }'),
                ],
                'templateResult' => new JsExpression('function(item) {
                    if (!item.number) return item.text; return item.name + ", " + item.number;
                }'),
                'templateSelection'=> new JsExpression('function(item) {
                    if (!item.number) return item.text; return item.name + ", " + item.number;
                }'),
            ]
        ]) ?>
    </div>
</div>

<?php if (isset($id)) { ?>
    <input type="hidden" name="isRealSubmit" id="realUpdateSubmitFlag" value="0"/>
<?php } else { ?>
    <input type="hidden" name="isRealSubmit" id="realCreateSubmitFlag" value="0"/>
<?php } ?>

<?php ActiveForm::end(); ?>

<?php

$script = <<< JS
    var isNew = '$model->isNew';
    $('#fcoCreateModalButtons').show();
    initFCVNDocument('foreigncurrencyconversion', isNew);

    $('#fcoForm').change(function() {
        $.post('/wizard-cache/fcvn', $(this).serialize());

        var type = $('#foreigncurrencyconversion-operationtype').val();

        var queryParams = parseQueryString();
        var url = window.location.pathname + '?tabMode=' + queryParams.tabMode + '&withCache=1&cacheType=' + type;

        window.history.replaceState({}, '', url);
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>
