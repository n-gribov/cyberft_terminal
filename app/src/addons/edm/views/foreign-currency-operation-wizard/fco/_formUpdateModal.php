<?php

use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationType;
use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\MaskedInput;

/** @var ForeignCurrencyOperationType $model */

$isPurchase = $model->operationType === ForeignCurrencyOperationType::OPERATION_PURCHASE;

if ($isPurchase) {
    $excepts = ['1', '2'];
    $urlDebitAccount = Url::to(['/edm/edm-payer-account/list?currency=1']);
    $urlCreditAccount = Url::to(['/edm/edm-payer-account/list?exceptCurrency=1']);
    $labelDebitAccount = 'Счет в рублях';
    $labelCreditAccount = 'Счет в валюте';

    $fieldCreditAmount = 'paymentOrderCurrAmount';
    $fieldDebitAmount = 'paymentOrderAmount';

    $title = 'Покупка валюты';
} else {
    $excepts = [];
    $urlDebitAccount = Url::to(['/edm/edm-payer-account/list?exceptCurrency=1']);
    $urlCreditAccount = Url::to(['/edm/edm-payer-account/list?currency=1']);
    $labelDebitAccount = 'Счет в валюте';
    $labelCreditAccount = 'Счет в рублях';

    $fieldCreditAmount = 'paymentOrderAmount';
    $fieldDebitAmount = 'paymentOrderCurrAmount';

    $title = 'Продажа валюты';
}

// Получение списка организаций
$organizations = Yii::$app->terminalAccess->query(DictOrganization::className())->all();
$organizationsList = ArrayHelper::map($organizations, 'id', 'name');

$availableBanks = \addons\edm\helpers\EdmHelper::getBanksAvailableToUser(Yii::$app->user->identity);

?>
<style type="text/css">
    .form-inline .form-group {
        width: 100%;
    }

    .form-inline .form-group .form-control {
        width: 100% ;
    }

    .form-inline-field {
        display: inline-block;
    }

    .form-inline-field input {
        border: 0;
        width: 100%;
    }

    .form-inline-subfield input {
        border: 0;
        width: 100%;
    }

    .form-label {
        display: block;
    }

    table > tbody tr.payment-order-bottom-row td {
        vertical-align: bottom;
        width: 14.28%;
    }

    <?php if (!$model->applicant->name) : ?>
    .org-info {
        display: none;
    }
    <?php endif ?>
    #fcoUpdateForm input:read-only {
        cursor: not-allowed;
    }
</style>
<?php
$form = ActiveForm::begin([
    'method'	=> 'post',
    'type'      => ActiveForm::TYPE_INLINE,
    'id'        => 'fcoUpdateForm',

    'enableClientValidation' => false,
    'enableAjaxValidation'   => true,

    'action'	=> '/edm/foreign-currency-operation-wizard/update?id=' . $id . '&type=' . $model->operationType,

    'options'	=> ['class' => 'edm-fco-wizard'],
    'formConfig' => [
        'labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_TINY,
        'showErrors' => true,
    ]
]);
?>

<input type="hidden" name="isRealSubmit" id="realUpdateSubmitFlag" value="0"/>
<?= Html::hiddenInput('id', $id) ?>

<?php DatePicker::widget([
    'id'         => 'foreigncurrencyoperationtype-date',
    'dateFormat' => 'dd.MM.yyyy',
]) ?>
<?php MaskedInput::widget([
    'id'            => 'foreigncurrencyoperationtype-date',
    'name'          => 'foreigncurrencyoperationtype-date',
    'mask'          => '99.99.9999',
    'clientOptions' => [
        'placeholder' => 'dd.MM.yyyy',
    ]
])?>
<h4>Операция</h4>
<div class="row">
    <div class="col-sm-12">
        <?= $form->field($model, 'operationType')->dropDownList(
            $model->getOperationTypes(),
            ['class' => 'body-select', 'disabled' => true]
        )?>
    </div>
</div>
<div class="body-info">
<div id="form-title"></div>
<div class="row">
    <div class="col-sm-3">
        <label><?= $model->getAttributeLabel('numberDocument') ?></label>
        <?= $form->field($model, 'numberDocument')
            ->widget(MaskedInput::className(), [
                'mask' => '9[9999999999]',
                'clientOptions' => [
                    'placeholder' => ''
                ]
            ])
        ?>
    </div>
    <div class="col-sm-3">
        <label><?= $model->getAttributeLabel('date') ?></label>
        <?= $form->field($model, 'date')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<h4><?= $model->getAttributeLabel('applicant.name') ?></h4>
<div class="row">
    <div class="col-sm-12">
        <?= $form->field($model, 'applicant.name')->widget(Select2::class, [
            'options' => ['placeholder' => ''],
            'theme'   => Select2::THEME_BOOTSTRAP,
            'data'    => $organizationsList,
            'disabled' => true,
        ]) ?>
        <?= $form->field($model, 'applicant.name', ['options' => ['class' => 'hidden']])->hiddenInput() ?>
    </div>
</div>
<div class="row org-info">
    <div class="col-sm-6">
        <label><?= $model->getAttributeLabel('applicant.inn') ?></label>
        <div id="orgInn"><?= $model->applicant->inn ?><input
                type="hidden" name="<?=$model->formName() . '[applicant.inn]'?>"
                value="<?=$model->applicant->inn?>"/></div>
    </div>
    <div class="col-sm-6">
        <label><?= $model->getAttributeLabel('applicant.address') ?></label>
        <div id="orgAddress"><?= $model->applicant->address ?><input
                type="hidden" name="<?=$model->formName() . '[applicant.address]'?>"
                value="<?=$model->applicant->address?>"/></div>
    </div>
</div>
<div class="row" style="margin-top:5px">
    <div class="col-sm-6">
        <label><?= $model->getAttributeLabel('applicant.contactPerson') ?></label>
        <?= $form->field($model, 'applicant.contactPerson')->textInput() ?>
    </div>
    <div class="col-sm-6">
        <label><?= $model->getAttributeLabel('applicant.phone') ?></label>
        <?= $form->field($model, 'applicant.phone')
            ->widget(MaskedInput::class, [
                'mask' => '+9 (999) 999-99-99',
                'clientOptions' => [
                    'placeholder' => '+7 (___) ___-__-__',
                ]
            ])
        ?>
    </div>
</div>
<h4>Сделка</h4>
<div class="row">
    <div class="col-sm-9">
        <label><?= Yii::t('edm', 'Bank') ?></label>
        <?= $form
            ->field($model, 'recipientBankBik')
            ->dropDownList(
                ArrayHelper::map($availableBanks, 'bik', 'name'),
                count($availableBanks) === 1 ? [] : ['prompt' => '', 'disabled' => true]
            )
        ?>
        <?= $form->field($model, 'recipientBankBik', ['options' => ['class' => 'hidden']])->hiddenInput() ?>
    </div>
    <div class="col-sm-9">
        <label><?= $labelDebitAccount ?></label>
        <?php
        if (isset($model->debitAccount)) {
            $account = EdmPayerAccount::findOne(['number' => $model->debitAccount->number]);
        } else {
            $account = null;
        }
        echo $form->field($model, 'debitAccount.number')->widget(Select2::className(), [
            'options'       => [
                'placeholder' => Yii::t('edm', 'Search by title or account number ...'),
                'disabled' => empty($model->recipientBankBik),
            ],
            'initValueText' => (!empty($account)
                ? $account->name
                    . ', ' . $account->number
                    . ', ' . $account->edmDictCurrencies->name
                : ''
            ),
            'pluginOptions' => [
                'allowClear'         => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url'      => $urlDebitAccount,
                    'dataType' => 'json',
                    'delay'    => 250,
                    'data'     => new JsExpression('function(params) { return { q: params.term, bankBik: $("#foreigncurrencyoperationtype-recipientbankbik").val() }; }')
                ],
                'templateResult' => new JsExpression('function(item) {
                    if (!item.number) return item.text; return item.name + ", " + item.number + ", " + item.currencyInfo.name;
                }'),
                'templateSelection'=> new JsExpression('function(item) {
                    if (!item.number) return item.text; return item.name + ", " + item.number + ", " + item.currencyInfo.name;
                }'),
            ],
        ])
        ?>
    </div>
    <div class="col-sm-3">
        <label><?= $model->getAttributeLabel($fieldDebitAmount) ?></label>
        <?= $form->field($model, $fieldDebitAmount)
            ->widget(MaskedInput::className(), [
                'clientOptions' => [
                    'alias' => 'decimal',
                    'digits' => 4,
                    'digitsOptional' => false,
                    'radixPoint' => '.',
                    'groupSeparator' => ' ',
                    'autoGroup' => true,
                    'autoUnmask' => true,
                    'removeMaskOnSubmit' => true,
                ]
        ])?>
    </div>
</div>
<div class="row">
    <div class="col-sm-9">
        <label><?= $labelCreditAccount ?></label>
        <?php
        if (isset($model->creditAccount)) {
            $account = EdmPayerAccount::findOne(['number' => $model->creditAccount->number]);
        } else {
            $account = null;
        }
        echo $form->field($model, 'creditAccount.number')->widget(Select2::className(), [
            'initValueText' => (!empty($account)
                ? $account->name
                    . ', ' . $account->number
                    . ', ' . $account->edmDictCurrencies->name
                : ''
            ),
            'options'       => [
                'placeholder' => Yii::t('edm', 'Search by title or account number ...'),
                'disabled' => empty($model->recipientBankBik),
            ],
            'pluginOptions' => [
                'allowClear'         => true,
                'minimumInputLength' => 0,
                'ajax'               => [
                    'url'      => $urlCreditAccount,
                    'dataType' => 'json',
                    'delay'    => 250,
                    'data'     => new JsExpression('function(params) { return { q: params.term, bankBik: $("#foreigncurrencyoperationtype-recipientbankbik").val() }; }'),
                ],
                'templateResult'     => new JsExpression('function(item) {
                    if (!item.number) return item.text; return item.name + ", " + item.number + ", " + item.currencyInfo.name;
                }'),
                'templateSelection'  => new JsExpression('function(item) {
                    if (!item.number) return item.text; return item.name + ", " + item.number + ", " + item.currencyInfo.name;
                }'),
            ],
        ])
        ?>
    </div>
    <div class="col-sm-3">
        <label><?= $model->getAttributeLabel($fieldCreditAmount) ?></label>
        <?= $form->field($model, $fieldCreditAmount)
                ->widget(MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' => 'decimal',
                        'digits' => 4,
                        'digitsOptional' => false,
                        'radixPoint' => '.',
                        'groupSeparator' => ' ',
                        'autoGroup' => true,
                        'autoUnmask' => true,
                        'removeMaskOnSubmit' => true,
                ]
            ])
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <label><?= $model->getAttributeLabel('paymentOrderCurrExchangeRate') ?></label>
        <div style="margin-top:-8px">
            <input type="radio" id="rateType1" name="rateType" value="bank" <?= $model->paymentOrderCurrExchangeRate ? '' : 'checked' ?>> По курсу банка
            <input type="radio" id="rateType2" name="rateType" value="custom" <?= $model->paymentOrderCurrExchangeRate ? 'checked' : '' ?>> Значение
        </div>
        <div id="rateTypeField" <?= $model->paymentOrderCurrExchangeRate ? '' : 'style="display:none"' ?>><?=
                $form->field($model, 'paymentOrderCurrExchangeRate')
                ->widget(MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' => 'decimal',
                        'digits' => 4,
                        'digitsOptional' => false,
                        'radixPoint' => '.',
                        'groupSeparator' => ' ',
                        'autoGroup' => true,
                        'autoUnmask' => true,
                        'removeMaskOnSubmit' => true,
                    ]
            ])?>
        </div>
    </div>
</div>

<h4>Комиссия</h4>
<div class="row">
    <div class="col-sm-12">
        <label>Счет для удержания комиссии</label>
        <?php
        if (isset($model->commissionAccount)) {
            $account = EdmPayerAccount::findOne(['number' => $model->commissionAccount->number]);
        } else {
            $account = null;
        }
        echo $form->field($model, 'commissionAccount.number')->widget(Select2::className(), [
            'initValueText' => (!empty($account)
                ? $account->name
                    . ', ' . $account->number
                    . ', ' . $account->edmDictCurrencies->name
                : ''
            ),
            'options'       => [
                'placeholder' => Yii::t('edm', 'Search by title or account number ...'),
                'disabled' => empty($model->recipientBankBik),
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url'      => Url::to(['edm-payer-account/list']), // ?currency=1
                    'dataType' => 'json',
                    'delay'    => 250,
                    'data'     => new JsExpression('function(params) { return { q: params.term, bankBik: $("#foreigncurrencyoperationtype-recipientbankbik").val() }; }')
                ],

                'templateResult'     => new JsExpression('function(item) {
                    if (!item.number) return item.text; return item.name + ", " + item.number + ", " + item.currencyInfo.name;
                }'),
                'templateSelection'  => new JsExpression('function(item) {
                    if (!item.number) return item.text; return item.name + ", " + item.number + ", " + item.currencyInfo.name;
                }'),
            ],
        ])->label(Yii::t('edm', 'Account for the retention of the commission'));
        ?>
    </div>
</div>
</div>

<?php

ActiveForm::end();

$formName = $model->formName();
$labelPaymentOrderCurrAmount = $model->getAttributeLabel('paymentOrderCurrAmount');
$labelPaymentOrderAmount = $model->getAttributeLabel('paymentOrderAmount');

$script = <<<JS
    function clearSelect2(selector) {
        $(selector).val(null).trigger('change.select2');
    }

    var formName = '$formName';
    var labelPaymentOrderCurrAmount = '$labelPaymentOrderCurrAmount';
    var labelPaymentOrderAmount = '$labelPaymentOrderAmount';

    var paymentOrderCurrAmount = $('[name="' + formName + '[paymentOrderCurrAmount]"]');
    var paymentOrderAmount = $('[name="' + formName + '[paymentOrderAmount]"]');

    paymentOrderCurrAmount.on('change', function(){
        var value = $(this).val();
        if (value && parseFloat(value) > 0) {
            paymentOrderAmount.prop('readonly', true);
            paymentOrderAmount.attr('title', 'Заполнено поле  «' + labelPaymentOrderCurrAmount + '»');
        } else {
            paymentOrderAmount.prop('readonly', false);
            paymentOrderAmount.attr('title', '');
        }
    });

    paymentOrderAmount.on('change', function(){
        var value = $(this).val();
        if (value && parseFloat(value) > 0) {
            paymentOrderCurrAmount.prop('readonly', true);
            paymentOrderCurrAmount.attr('title', 'Заполнено поле  «' + labelPaymentOrderAmount + '»');
        } else {
            paymentOrderCurrAmount.prop('readonly', false);
            paymentOrderCurrAmount.attr('title', '');
        }
    });

    paymentOrderAmount.trigger('change');
    paymentOrderCurrAmount.trigger('change');

    // При изменении организации автоматически подставляем ИНН в соответствующее поле
    $('#foreigncurrencyoperationtype-applicant-name').on('change', function(e) {

        e.preventDefault();

        // Получаем id текущей выбранной организации
        var selected = $(this).find(":selected").val();

        $('#foreigncurrencyoperationtype-applicant-inn').val('');
        $('#orgInn').html('');
        $('#foreigncurrencyoperationtype-applicant-address').val('');
        $('#orgAddress').html('');

        // Если выбрано пустое значение, приостанавливаем выполнение события
        if (!selected) {
            return false;
        }

        // Ajax-запрос для получения ИНН организации
        $.ajax({
            url: '/edm/dict-organization/get-organization-data',
            type: 'get',
            data: 'id=' + selected,
            success: function(result) {

                // Преобразование json-ответа в объект
                var organization = JSON.parse(result);

                // Заполнение поля поля ИНН соответствующим значением
                $('#foreigncurrencyoperationtype-applicant-inn').val(organization.inn);
                $('#foreigncurrencyoperationtype-applicant-address').val(organization.address);
                $('#orgInn').html(organization.inn);
                $('#orgAddress').html(organization.address);
        	    $('.org-info').show();
            }
        });
    });

    $('#foreigncurrencyoperationtype-recipientbankbik').change(function (event) {
        clearSelect2('#foreigncurrencyoperationtype-debitaccount-number');
        clearSelect2('#foreigncurrencyoperationtype-creditaccount-number');
        clearSelect2('#foreigncurrencyoperationtype-commissionaccount-number');
        $('#foreigncurrencyoperationtype-debitaccount-number,#foreigncurrencyoperationtype-creditaccount-number,#foreigncurrencyoperationtype-commissionaccount-number').prop('disabled', !event.target.value);
    });

    $('#rateType1').on('click', function() {
        $('#rateTypeField').hide();
	    $('#foreigncurrencyoperationtype-paymentordercurrexchangerate').val('');

        return true;
	});

   $('#rateType2').on('click', function() {
        $('#rateTypeField').show();

        return true;
	});

    $('#fcoUpdateModalTitle').html('$title');
    $('#fcoUpdateModalButtons').show();

    $('.body-select').on('change', function(e) {
        var type = $('#foreigncurrencyoperationtype-operationtype').val();
        $("#fcoUpdateModal .modal-body").html('');

        $.ajax({
            url: '/edm/foreign-currency-operation-wizard/update?id={$id}&type=' + type,
            type: 'get',
            success: function(answer) {
                // Добавляем html содержимое на страницу формы
                $('#fcoUpdateModal .modal-body').html(answer);
            }
        });
    });
JS;

$this->registerJs($script, View::POS_READY);

?>