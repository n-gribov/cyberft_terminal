<?php

use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\web\View;
use kartik\widgets\ActiveForm;
use yii\widgets\MaskedInput;
?>
<style type="text/css">
    <?php if (!$model->organizationId) : ?>
    #org-info {
        display: none;
    }
    <?php endif ?>

</style>
<?php
// если это форма редактирования
if (isset($id)) {
    $formId = 'fcoUpdateForm';
    $formAction = "/edm/foreign-currency-operation-wizard/update?id={$id}&type={$model->operationType}";
} else {
    $formId = 'fcoForm';
    $formAction = "/edm/foreign-currency-operation-wizard/create?type={$model->operationType}";
}

$urlAccounts = '/edm/edm-payer-account/list';
$urlFcstAccount = Url::to([$urlAccounts, 'currency' => 1]);
$urlCommissionAccount = Url::to([$urlAccounts, 'currency' => 1]);
$urlTransitAccount = Url::to([$urlAccounts, 'exceptCurrency' => 1]);
$urlForeignAccount = Url::to([$urlAccounts, 'exceptCurrency' => 1]);

// Доступные пользователю счета
$allowedAccounts = EdmPayerAccountUser::getUserAllowAccounts(Yii::$app->user->id);

// Получение списка организаций
$organizations = Yii::$app->terminalAccess->query(DictOrganization::className())->all();
$organizationsList = ArrayHelper::map($organizations, 'id', 'name');
//$organizationsList['1 '] = 'AAA';

$banksByOrganization = EdmHelper::getAvailableBanksByOrganization(Yii::$app->user->identity);
$banksByOrganizationJson = empty($banksByOrganization) ? '{}' : json_encode($banksByOrganization);

$dateFieldId = 'foreigncurrencyselltransit-date';
$dateCurrencyIncoming = 'foreigncurrencyselltransit-currencyincomingdate';

$form = ActiveForm::begin([
    'id'     => $formId,
    'action' => $formAction,
    'enableClientValidation' => false,
    'enableAjaxValidation'   => true,
    'formConfig' => [
//        'labelSpan' => 3,
//        'deviceSize' => ActiveForm::SIZE_TINY,
        'showErrors' => true,
    ]
]);
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
        <?=$form->field($model, 'number')->textInput( [
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

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'organizationId')->widget(Select2::class, [
            'options' => [
                'placeholder' => '',
                'disabled' => isset($id),
            ],
            'theme'   => Select2::THEME_BOOTSTRAP,
            'data'    => $organizationsList,
        ]) ?>
    </div>

    <div id="org-info">
        <div class="col-md-12">
        <div class="col-md-2">
            <p><strong>ИНН</strong></p>
            <p id="fcst-org-inn"></p>
        </div>
        <div class="col-md-10">
            <p><strong>Адрес</strong></p>
            <p id="fcst-org-address"></p>
        </div>
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
            'options' => ['class' => 'form-control', 'value' => $model->contactPersonPhone ?: '+7'],
            'clientOptions' => [
                'placeholder' => '+7 (___) ___-__-__',
            ]
        ]) ?>
    </div>
</div>

<hr class="fcst-hr">

<h4>Сделка</h4>

<div class="row padding-left-20">
    <div class="col-md-6">
        <?= $form->field($model, 'currencyIncomingNumber')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-3">
        <?php MaskedInput::widget([
            'id'            => $dateCurrencyIncoming,
            'name'          => $dateCurrencyIncoming,
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
        <?php DatePicker::widget([
            'id'         => $dateCurrencyIncoming,
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
        <?= $form->field($model, 'currencyIncomingDate')->textInput() ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'amount')->widget(MaskedInput::className(), [
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
<div class="row padding-left-20">
    <div class="col-md-9">
        <?php
            $bankSelectOptions = $banksByOrganization[$model->organizationId] ?? [];
            $bankFieldOptions = [];
            if (count($bankSelectOptions) > 1) {
                $bankFieldOptions['prompt'] = '';
            }
            if (!$model->organizationId || isset($id)) {
                $bankFieldOptions['disabled'] = true;
            }
        ?>
        <?= $form
            ->field($model, 'bankBik')
            ->dropDownList(
                $bankSelectOptions,
                $bankFieldOptions
            )
        ?>
    </div>
</div>
<div class="row padding-left-20">
    <div class="col-md-9">
    <?php
        if (isset($model->transitAccount)) {
            $account = EdmPayerAccount::findOne(['number' => $model->transitAccount, 'id' => $allowedAccounts]);
        } else {
            $account = null;
        }
        echo $form->field($model, 'transitAccount')->widget(Select2::className(), [
            'options' => [
                'placeholder' => Yii::t('edm', 'Search by title or account number ...'),
                'disabled' => empty($model->bankBik),
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
                    'url'      => $urlTransitAccount,
                    'dataType' => 'json',
                    'delay'    => 250,
                    'data'     => new JsExpression(<<<JS
                        function(params) {
                            return {
                                q: params.term, bankBik: $('#foreigncurrencyselltransit-bankbik').val()
                            };
                        }
                    JS),
                ],
                'templateResult' => new JsExpression(<<<JS
                    function(item) {
                        if (!item.number) {
                            return item.text;
                        }
                        return item.name + ', ' + item.number + ', ' + item.currencyInfo.name;
                    }
                JS),
                'templateSelection'=> new JsExpression(<<<JS
                    function(item) {
                        if (!item.number) {
                            return item.text;
                        }
                        return item.name + ', ' + item.number + ', ' + item.currencyInfo.name;
                    }
                JS),
            ],
        ]) ?>
    </div>
</div>
<div class="row padding-left-20">
    <div class="col-md-9">
        <?php
            if (isset($model->foreignAccount)) {
                $foreignAccount = EdmPayerAccount::findOne(['number' => $model->foreignAccount]);
            } else {
                $foreignAccount = null;
            }
        ?>
        <?= $form->field($model, 'foreignAccount')->label('Перевести на текущий валютный счет')->widget(Select2::className(), [
            'options'       => [
                'placeholder' => Yii::t('edm', 'Search by title or account number ...'),
                'disabled' => empty($model->bankBik),
            ],
            'initValueText' => (!empty($foreignAccount)
                ? $foreignAccount->name . ', ' . $foreignAccount->number . ', ' . $foreignAccount->edmDictCurrencies->name : ''
            ),
            'pluginOptions' => [
                'allowClear'         => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => $urlForeignAccount,
                    'dataType' => 'json',
                    'delay'    => 250,
                    'data'     => new JsExpression(<<<JS
                        function(params) {
                            return {
                                q: params.term, bankBik: $('#foreigncurrencyselltransit-bankbik').val()
                            };
                        }
                    JS),
                ],
                'templateResult' => new JsExpression(<<<JS
                    function(item) {
                        if (!item.number) {
                            return item.text;
                        }
                        return item.name + ', ' + item.number + ', ' + item.currencyInfo.name;
                    }
                JS),
                'templateSelection' => new JsExpression(<<<JS
                    function(item) {
                        if (!item.number) {
                            return item.text;
                        }
                        return item.name + ', ' + item.number + ', ' + item.currencyInfo.name;
                    }
                JS),
            ],
        ]) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'amountTransfer')->label('Сумма перевода')->widget(MaskedInput::className(), [
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
<div class="row padding-left-20">
    <div class="col-md-9 fcst-sell-currency-label">
        <?= $form->field($model, 'sellOnMarket')->checkbox() ?>
    </div>
</div>
<div id="sell_block">
<div class="row padding-left-20">
    <div class="col-md-3">
        <?= $form->field($model, 'amountSell')->widget(MaskedInput::className(), [
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
<div class="row padding-left-20">
    <div class="col-md-9">
        <?php
            if (isset($model->account)) {
                $account = EdmPayerAccount::findOne(['number' => $model->account]);
            } else {
                $account = null;
            }
        ?>
        <?= $form->field($model, 'account')
                ->label('Средства от продажи зачислить на расчетный счет')
                ->widget(Select2::className(), [
            'options' => [
                'placeholder' => Yii::t('edm', 'Search by title or account number ...'),
                'disabled' => empty($model->bankBik),
            ],
            'initValueText' => (!empty($account)
                ? $account->name . ', ' . $account->number . ', ' . $account->edmDictCurrencies->name : ''
            ),
            'pluginOptions' => [
                'allowClear'         => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => $urlFcstAccount,
                    'dataType' => 'json',
                    'delay'    => 250,
                    'data'     => new JsExpression(<<<JS
                        function(params) {
                            return {
                                q: params.term, bankBik: $('#foreigncurrencyselltransit-bankbik').val()
                            };
                        }
                    JS),
                ],
                'templateResult' => new JsExpression(<<<JS
                    function(item) {
                        if (!item.number) {
                            return item.text;
                        }
                        return item.name + ', ' + item.number + ', ' + item.currencyInfo.name;
                    }
                JS),
                'templateSelection'=> new JsExpression(<<<JS
                    function(item) {
                        if (!item.number) {
                            return item.text;
                        }
                        return item.name + ', ' + item.number + ', ' + item.currencyInfo.name;
                    }
                JS),
            ],
        ]) ?>
    </div>
</div>
</div>
<div class="row">
    <div class="col-md-12">
        <p>
            <strong>Комиссию и расходы списать со счета</strong>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-9">
    <?php
        if (isset($model->commissionAccount)) {
            $commissionAccount = EdmPayerAccount::findOne(['number' => $model->commissionAccount]);
        } else {
            $commissionAccount = null;
        }
        ?>

        <?= $form->field($model, 'commissionAccount')->label(false)->widget(Select2::className(), [
            'options'       => [
                'placeholder' => Yii::t('edm', 'Search by title or account number ...'),
                'disabled' => empty($model->bankBik),
            ],
            'initValueText' => (!empty($commissionAccount)
                ? $commissionAccount->name . ', ' . $commissionAccount->number . ', ' . $commissionAccount->edmDictCurrencies->name : ''
            ),
            'pluginOptions' => [
                'allowClear'         => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => $urlCommissionAccount,
                    'dataType' => 'json',
                    'delay'    => 250,
                    'data'     => new JsExpression(<<<JS
                        function(params) {
                            return {
                                q: params.term, bankBik: $('#foreigncurrencyselltransit-bankbik').val()
                            };
                        }
                    JS),
                ],
                'templateResult' => new JsExpression(<<<JS
                    function(item) {
                        if (!item.number) {
                            return item.text;
                        }
                        return item.name + ', ' + item.number + ', ' + item.currencyInfo.name;
                    }
                JS),
                'templateSelection'=> new JsExpression(<<<JS
                    function(item) {
                        if (!item.number) {
                            return item.text;
                        }
                        return item.name + ', ' + item.number + ', ' + item.currencyInfo.name;
                    }
                JS),
            ],
        ])  ?>
    </div>
</div>

<?php if (isset($id)) : ?>
    <input type="hidden" name="isRealSubmit" id="realUpdateSubmitFlag" value="0"/>
<?php else : ?>
    <input type="hidden" name="isRealSubmit" id="realCreateSubmitFlag" value="0"/>
<?php endif ?>

<?php ActiveForm::end(); ?>

<?php

$script = <<<JS
    var fieldBankBik = '#foreigncurrencyselltransit-bankbik';
    var fieldOrgId = '#foreigncurrencyselltransit-organizationid';

    function clearSelect2(selector) {
        $(selector).val(null).trigger('change.select2');
    }

    function reloadAccountFields(setDisabled) {
        clearSelect2('#foreigncurrencyselltransit-transitaccount');
        clearSelect2('#foreigncurrencyselltransit-foreignaccount');
        clearSelect2('#foreigncurrencyselltransit-account');
        clearSelect2('#foreigncurrencyselltransit-commissionaccount');
        $('#foreigncurrencyselltransit-transitaccount').prop('disabled', setDisabled);
        $('#foreigncurrencyselltransit-foreignaccount').prop('disabled', setDisabled);
        $('#foreigncurrencyselltransit-commissionaccount').prop('disabled', setDisabled);
        $('#foreigncurrencyselltransit-account').prop('disabled', setDisabled);
    }

    function updateBankOptions() {
        $(fieldBankBik + ' option').remove();
        var orgId = $(fieldOrgId).val();
        var banksByOrganization = $banksByOrganizationJson;
        var banks = banksByOrganization[orgId];
        if (Object.keys(banks) !== 1) {
            $(fieldBankBik).append(new Option('', '', false, true));
        }
        for (var bik in banks) {
            $(fieldBankBik).append(new Option(banks[bik], bik, false, false));
        }
    }

    // При изменении организации автоматически подставляем ИНН в соответствующее поле
    $(fieldOrgId).on('change', function(e) {

        e.preventDefault();

        // Получаем id текущей выбранной организации
        var selected = $(this).find(':selected').val();

        $('#fcst-org-inn').text('');
        $('#fcst-org-address').text('');

        $(fieldBankBik)
            .val('')
            .trigger('change.select2')
            .prop('disabled', !selected);
        updateBankOptions();
        reloadAccountFields(true);

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
                $('#fcst-org-inn').text(organization.inn);
                $('#fcst-org-address').text(organization.address);
        	    $('#org-info').show();
            }
        });
    });

    $(fieldBankBik).change(function (event) {
        reloadAccountFields(!event.target.value);
    });

    $('#fcoForm').change(function() {
        $.post('/wizard-cache/fcst', $(this).serialize());

        var type = $('#foreigncurrencyselltransit-operationtype').val();
        var queryParams = parseQueryString();
        var url = window.location.pathname + '?tabMode=' + queryParams.tabMode + '&withCache=1&cacheType=' + type;

        window.history.replaceState({}, '', url);
    });

    $('#foreigncurrencyselltransit-sellonmarket').change(function() {
        var active = $('#foreigncurrencyselltransit-sellonmarket').prop('checked');
        if (active) {
            $('#sell_block').show();
        } else {
            $('#sell_block').hide();
        }
    });

    if (!$('#foreigncurrencyselltransit-sellonmarket').prop('checked')) {
        $('#sell_block').hide();
    }

    $('#fcoCreateModalButtons').show();
JS;

$this->registerJs($script, View::POS_READY);

