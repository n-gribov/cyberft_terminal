<?php

use addons\edm\helpers\Dict;
use addons\edm\models\DictBeneficiaryContractor;
use addons\edm\models\DictContractor;
use addons\edm\models\EdmPayerAccount;
use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

$model->paymentPurposeNds = null;
$model->priority = $model->priority ?: 5;
$model->payType = $model->payType ?: '01';
?>
<style type="text/css">
    .form-inline .form-group {
        width: 100%;
    }
    .form-inline .form-group .form-control {
        width: 100% ;
    }
    table tr.iespike {
        border: none;
    }
    table tr.iespike td {
        border: none; padding: 0;
    }
    table > tbody tr.payment-order-bottom-row td {
        vertical-align: bottom;
        width: 14.28%;
    }
    .payment-order-bottom-row label.small {
        font-weight: normal;
    }
    /* Сетка платежного поручения */
    .payment-order-left-column {
        width: 559px;
    }
    .payment-order-right-column {
        width: 607px;
    }
    .payer-info {
        display: none;
    }
    .beneficiary-info {
        display: none;
    }
    .budget-info {
        display: none;
    }
    .no-padding-left {
        padding-left: 0;
    }
    .no-padding-right {
        padding-right: 0;
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
    .payer-bank-info {
        display: none;
        padding-left: 133px;
    }
    .beneficiary-bank-info {
        display: none;
        padding-left: 122px;
    }
    .payer-bank-link {
        font-weight: bold;
    }
    .beneficiary-bank-link {
        font-weight: bold;
    }
    .payer-bank-block-header {
        margin-bottom: 5px;
    }
    .beneficiary-bank-block-header {
        margin-bottom: 5px;
    }
    .width100 input {
        width: 100px;
    }
    .width400 input {
        width: 400px;
    }
    .update-payer-button {
        display: none;
        z-index: 5;
    }
    .beneficiary-account {
        position: relative;
    }
    .payment-order-header-column {
        display: inline-block;
        position: relative;
    }
    .payment-order-header-right-column {
        width: 100px;
    }
    .form-label {
        display: block;
    }
    .additional-info {
        padding-left: 35px;
    }
    .additional-info-link {
        text-decoration: underline;
        display: block;
        margin-bottom: 15px;
    }
    .additional-info-content {
        display: none;
    }
    .payment-order-wrapper {
        padding-left: 79px;
    }
    #paymentordertype-number {
        width: 62px;
    }
    .payment-order-header-column-number {
        width: 70px;
    }
    .payment-order-header-number {
        margin-right: 5px;
        vertical-align: top;
        padding-top: 8px;
    }
    .payment-order-header-column-label-date {
        margin-right: 5px;
        vertical-align: top;
        padding-top: 8px;
    }
    #paymentordertype-date {
        width: 103px;
    }
    .payment-order-header-column-date {
        width: 130px;
    }
    .payer-block {
        margin-bottom: 16px;
    }
    .beneficiary-block {
        margin-bottom: 25px;
    }
    #paymentordertype-paymentpurpose {
        resize: none;
    }
    .width130 {
        width: 130px;
    }
    .width424 {
        width: 424px;
    }
    .add-payer-button {
        display: none;
    }
    .modal-body {
        padding-top: 0;
    }
    .field-error,
    .field-error:hover,
    .field-error:active {
        color: red;
    }
</style>
<?php
$form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_INLINE,
    'enableClientValidation' => false,
    'enableAjaxValidation'   => true,
    'validationUrl' => Url::to(['validate-ajax']),
    'action' => Url::to(['step2']),
    'formConfig' => [
        'labelSpan'  => 3,
        'deviceSize' => ActiveForm::SIZE_TINY,
        'showErrors' => true,
    ],
    'options' => ['class' => 'edm-paymentorder-wizard']
]);

DatePicker::widget([
    'id' => 'paymentordertype-date',
    'dateFormat' => 'dd.MM.yyyy',
]);

MaskedInput::widget([
    'id' => 'paymentordertype-date',
    'name' => 'paymentordertype-date',
    'mask' => '99.99.9999',
    'clientOptions' => [
        'placeholder' => 'dd.MM.yyyy',
    ]
]);
?>
<div class="col-md-12">
    <div class="payment-order-wrapper">
        <div class="payment-order-header clearfix">
            <div class="payment-order-header-column payment-order-header-number">
                <label>Номер документа</label>
            </div>
            <div class="payment-order-header-column payment-order-header-column-number payment-order-header-right-column" style="margin-right: 10px;">
                <?=$form->field($model, 'number', ['autoPlaceholder' => false])->textInput(['maxlength' => true])?>
            </div>
            <div class="payment-order-header-column payment-order-header-column-label-date">
                <label>Дата</label>
            </div>
            <div class="payment-order-header-column payment-order-header-column-date payment-order-header-right-column">
                <?=$form->field($model, 'date')->textInput(['maxlength' => true])?>
            </div>
        </div>
        <div class="payment-order-columns clearfix">
            <div class="payment-order-left-column pull-left">
                <div class="payer-block">
                    <h4>Плательщик</h4>
                    <div class="payer-account">
                    <?php
                        if (isset($model->payerCheckingAccount)) {
                            $payerCheckingAccount = EdmPayerAccount::findOne(['number' => $model->payerCheckingAccount]);
                        } else {
                            $payerCheckingAccount = null;
                        }

                        echo $form->field($model, 'payerCheckingAccount')->widget(Select2::classname(), [
                            'initValueText' => (!empty($payerCheckingAccount)
                                ? $payerCheckingAccount->name
                                . ', ' . $payerCheckingAccount->number
                                . ', ' . $payerCheckingAccount->edmDictCurrencies->name
                                : ''
                            ),
                            'options' => ['placeholder' => 'Поиск плательщика по имени или счету ...', 'class' => 'has-success'],
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'pluginOptions' => [
                                'allowClear'         => true,
                                'minimumInputLength' => 0,
                                'ajax' => [
                                    'url'      => Url::to(['edm-payer-account/list']),
                                    'dataType' => 'json',
                                    'delay'    => 250,
                                    'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
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
                            'pluginEvents'  => [
                                'select2:select' => 'function(e) { applyPayer(e.params.data); }',
                                'select2:unselect' => 'function(e) { resetPayer(); }'
                            ],
                        ]);
                        ?>
                        <div class="payer-info">
                            <div class="clearfix">
                                <div class="col-md-6 no-padding-left">
                                    <label class="form-inline-label">Счет №</label>
                                    <div class="form-inline-field width100 field-payer-account"></div>
                                </div>
                            </div>
                            <div class="clearfix">
                                <div class="col-md-6 no-padding-left <?=$model->hasErrors('payerInn') ? 'field-error' : ''?>">
                                    <label class="form-inline-label">ИНН</label>
                                    <div class="form-inline-field width100">
                                        <?=Html::activeTextInput($model, 'payerInn', ['readonly' => true])?>
                                    </div>
                                </div>
                                <div class="col-md-6 no-padding-right <?=$model->hasErrors('payerKpp') ? 'field-error' : ''?>">
                                    <label class="form-inline-label">КПП</label>
                                    <div class="form-inline-field width100">
                                        <?=Html::activeTextInput($model, 'payerKpp', ['readonly' => true])?>
                                    </div>
                                </div>
                            </div>

                            <div class="<?=$model->hasErrors('payerName') ? 'field-error' : ''?>">
                                <label class="form-inline-label">Наименование</label>
                                <div class="form-inline-field width400">
                                    <?=Html::activeTextInput($model, 'payerName', ['readonly' => true])?>
                                </div>
                                <?=Html::activeHiddenInput($model, 'payerName1', ['readonly' => true])?>
                            </div>

                            <div class="payer-bank-block">
                                <div class="payer-bank-block-header">
                                    <a href="#" class="payer-bank-link width130 <?=$model->hasErrors('payerBank1') ||
                                    $model->hasErrors('payerBik') ||
                                    $model->hasErrors('payerBank2') ||
                                    $model->hasErrors('payerCorrespondentAccount') ? 'field-error' : ''?>">Банк плательщика</a>
                                    <div class="form-inline-field width424">
                                        <?=Html::activeTextInput($model, 'payerBank1', ['readonly' => true])?>
                                    </div>
                                </div>

                                <div class="payer-bank-info">
                                    <div class="<?=$model->hasErrors('payerBik') ? 'field-error' : ''?>">
                                        <label class="form-inline-label">БИК</label>
                                        <div class="form-inline-field form-inline-subfield">
                                            <?=Html::activeTextInput($model, 'payerBik', ['readonly' => true])?>
                                        </div>
                                    </div>

                                    <div class="<?=$model->hasErrors('payerBank2') ? 'field-error' : ''?>">
                                        <label class="form-inline-label">Город</label>
                                        <div class="form-inline-field form-inline-subfield">
                                            <?=Html::activeTextInput($model, 'payerBank2', ['readonly' => true])?>
                                        </div>
                                    </div>

                                    <div class="<?=$model->hasErrors('payerCorrespondentAccount') ? 'field-error' : ''?>">
                                        <label class="form-inline-label">Корсчет</label>
                                        <div class="form-inline-field form-inline-subfield">
                                            <?=Html::activeTextInput($model, 'payerCorrespondentAccount', ['readonly' => true])?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="beneficiary-block">
                    <h4>Получатель</h4>
                    <div class="beneficiary-account">
                    <?php
                        Modal::begin([
                            'options' => [
                                'id' => 'contractorModal',
                                'tabindex' => false // important for Select2 to work properly
                            ],
                            'header' => 'Новый получатель',
                            'footer' => '<button id="beneficiary-submit" class="btn btn-success btn-beneficiary-submit">Создать</button>',
                            'toggleButton' => [
                                'label' => '',
                                'title' => 'Новый получатель',
                                'tag' => 'i',
                                'class' => 'fa fa-plus-square add-payer-button',
                                'style' => 'cursor: pointer; font-size: 34px; color: #2B8F0E; float: right; margin-right: 15px;'
                            ],
                        ]);
                    ?>
                        <iframe src="about:blank" width="100%" height="514" frameborder="0" id="contractorIframe"></iframe>
                    <?php
                        Modal::end();
                        Modal::begin([
                            'options' => [
                                'id' => 'contractorModalUpdate',
                                'tabindex' => false // important for Select2 to work properly
                            ],
                            'header' => 'Редактировать получателя',
                            'footer' => '<button class="btn btn-success btn-beneficiary-update-submit">Сохранить</button>',
                            'toggleButton' => [
                                'label' => '',
                                'title' => 'Редактировать получателя',
                                'tag' => 'i',
                                'class' => 'fa fa-pencil update-payer-button',
                                'style' => 'cursor: pointer; font-size: 34px; color: #2B8F0E; position: absolute; top: 45px; right: 3px; margin-right: 15px;'
                            ],
                        ]);
                        ?>
                        <iframe src="about:blank" width="100%" height="514" frameborder="0" class="update-payer-src" id="contractorIframe"></iframe>
                        <?php
                        Modal::end();
                        /** @var DictBeneficiaryContractor $item */
                        $item = DictBeneficiaryContractor::findOne(['account' => $model->beneficiaryCheckingAccount]);
                        print $form->field($model, 'beneficiaryCheckingAccount', ['options' => ['style' => 'width: 90%']])->widget(Select2::classname(), [
                            'initValueText' => isset($item)
                                ? 'Счет: ' . $item->account . ' Название: ' . $item->name
                                : null,
                            // set the initial display text
                            'options' => ['placeholder' => 'Поиск получателя по имени или счету ...'],
                            'disabled' => empty($model->payerCheckingAccount),
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 0,
                                'ajax' => [
                                    'url' => Url::to(['dict-beneficiary-contractor/list']),
                                    'dataType' => 'json',
                                    'delay' => 250,
                                    'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                                    'processResults' => new JsExpression(<<<JS
                                        function(data, query) {
                                            if (query.term) {
                                                data.results.push({
                                                    id: query.term,
                                                    text: query.term + ' (Новое значение)'
                                                });
                                            }
                                            return data;
                                        }
                                    JS),
                                ],
                                'templateResult' => new JsExpression(<<<JS
                                    function(item) {
                                        if (!item.account) {
                                            return item.text;
                                        }
                                        return 'ИНН:' + item.inn  + ', ' + item.name + ', ' + item.account;
                                    }
                                JS),
                                'templateSelection'  => new JsExpression(<<<JS
                                    function(item) {
                                        if (!item.account) {
                                            return item.text;
                                        }
                                        return 'ИНН:' + item.inn  + ', ' + item.name + ', ' + item.account;
                                    }
                                JS),
                            ],
                            'pluginEvents'  => [
                                // Для корректной работы со счетами, у которых одинаковый номер
                                'select2:select' => <<<JS
                                    function(e) {
                                        $('#paymentordertype-beneficiarycheckingaccount').empty();
                                        $('#hidden-beneficiarycheckingaccount').val(e.params.data.account);
                                        applyBeneficiary(e.params.data);
                                    }
                                JS,
                                // Для корректной работы со счетами, у которых одинаковый номер
                                'select2:unselect' => <<<JS
                                    function(e) {
                                        $('#paymentordertype-beneficiarycheckingaccount').empty();
                                        resetBeneficiary();
                                    }
                                JS,
                            ],
                        ]);
                        echo Html::activeHiddenInput($model, 'beneficiaryCheckingAccount', ['id' => 'hidden-beneficiarycheckingaccount']);
                    ?>
                        <div class="beneficiary-info">
                            <div class="clearfix">
                                <div class="col-md-6 no-padding-left">
                                    <label class="form-inline-label">Счет №</label>
                                    <div class="form-inline-field width100 field-beneficiary-account"></div>
                                </div>
                            </div>
                            <div class="clearfix">
                                <div class="col-md-6 no-padding-left <?=$model->hasErrors('beneficiaryInn') ? 'field-error' : ''?>">
                                    <label class="form-inline-label">ИНН</label>
                                    <div class="form-inline-field width100">
                                        <?=Html::activeTextInput($model, 'beneficiaryInn', ['readonly' => true])?>
                                    </div>
                                </div>
                                <div class="col-md-6 no-padding-right <?=$model->hasErrors('beneficiaryKpp') ? 'field-error' : ''?>">
                                    <label class="form-inline-label">КПП</label>
                                    <div class="form-inline-field width100">
                                        <?=Html::activeTextInput($model, 'beneficiaryKpp', ['readonly' => true])?>
                                    </div>
                                </div>
                            </div>
                            <div class="<?=$model->hasErrors('beneficiaryName') ? 'field-error' : ''?>">
                                <label class="form-inline-label">Наименование</label>
                                <div class="form-inline-field width400">
                                    <?=Html::activeTextInput($model, 'beneficiaryName', ['readonly' => true])?>
                                </div>
                            </div>
                            <div class="beneficiary-bank-block">
                                <div class="beneficiary-bank-block-header">
                                    <a href="#" class="beneficiary-bank-link width130 <?=$model->hasErrors('beneficiaryBank1') ||
                                    $model->hasErrors('beneficiaryBik') ||
                                    $model->hasErrors('beneficiaryBank2') ||
                                    $model->hasErrors('beneficiaryCorrespondentAccount') ? 'field-error' : ''?>">Банк получателя</a>
                                    <div class="form-inline-field width424">
                                        <?=Html::activeTextInput($model, 'beneficiaryBank1', ['readonly' => true])?>
                                    </div>
                                </div>
                                <div class="beneficiary-bank-info">
                                    <div class="<?=$model->hasErrors('beneficiaryBik') ? 'field-error' : ''?>">
                                        <label class="form-inline-label">БИК</label>
                                        <div class="form-inline-field form-inline-subfield">
                                            <?=Html::activeTextInput($model, 'beneficiaryBik', ['readonly' => true])?>
                                        </div>
                                    </div>
                                    <div class="<?=$model->hasErrors('beneficiaryBank2') ? 'field-error' : ''?>">
                                        <label class="form-inline-label">Город</label>
                                        <div class="form-inline-field form-inline-subfield">
                                            <?=Html::activeTextInput($model, 'beneficiaryBank2', ['readonly' => true])?>
                                        </div>
                                    </div>
                                    <div class="<?=$model->hasErrors('beneficiaryCorrespondentAccount') ? 'field-error' : ''?>">
                                        <label class="form-inline-label">Корсчет</label>
                                        <div class="form-inline-field form-inline-subfield">
                                            <?=Html::activeTextInput($model, 'beneficiaryCorrespondentAccount', ['readonly' => true])?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="payment-purpose">
                    <div>
                        <div class="clearfix">
                            <div class="col-md-6 no-padding-left">
                                <label class="form-label">Сумма</label>
                                <?=$form->field($model, 'sum')
                                //->textInput(['maxlength' => true])
                                ->widget(MaskedInput::className(), [
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
                                ])?>
                            </div>
                            <div class="col-md-6 no-padding-right">
                                <label class="form-label">Ставка НДС</label>
                                <?=$form->field($model, 'vat')->dropDownList(
                                    [null => 'Не указывать']
                                    + Dict::paymentPurpose()
                                )?>
                            </div>
                        </div>
                        <label>Назначение платежа</label>
                        <?=$form->field($model, 'paymentPurpose')->textarea(['maxlength' => 210, 'rows' => 5])?>
                    </div>
                    <div class="additional-info">
                        <a href="#" class="additional-info-link">Дополнительная информация</a>
                        <div class="additional-info-content">
                            <div class="clearfix">
                                <div class="col-md-6 no-padding-left">
                                    <label>Вид платежа</label>
                                    <?=$form
                                        ->field($model, 'paymentType')
                                        ->dropDownList(
                                            [null => $model->getAttributeLabel('paymentType')]
                                            + Dict::paymentType()
                                        )
                                    ?>
                                </div>
                                <div class="col-md-6 no-padding-right">
                                    <label>Код вида дохода</label>
                                    <?=$form
                                        ->field($model, 'paymentOrderPaymentPurpose')
                                        ->dropDownList(
                                            [null => '', '1' => '1', '2' => '2', '3' => '3']
                                        )
                                    ?>
                                </div>
                            </div>
                            <div class="clearfix">
                                <div class="col-md-6 no-padding-left">
                                    <label>* Вид операции</label>
                                    <?= $form->field($model, 'payType')->textInput(['maxlength' => 2]) ?>
                                </div>
                                <div class="col-md-6 no-padding-right">
                                    <label>Код</label>
                                    <?= $form->field($model, 'code', ['autoPlaceholder' => false])
                                        ->widget(MaskedInput::className(), [
                                            'mask' => '9999999999999999999999999',
                                            'clientOptions' => ['placeholder' => ''],
                                        ])
                                    ?>
                                </div>
                            </div>
                            <div class="clearfix">
                                <div class="col-md-12 no-padding-left no-padding-right">
                                    <label>Очередность платежа</label>
                                    <?=$form->field($model, 'priority')->dropDownList(Dict::priority())?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="budget-block">
                    <h4 class="budget-header">Бюджетный платеж&nbsp<?=Html::checkbox('budgetCheckbox', null, ['class' => 'budget-checkbox'])?></h4>
                    <div class="budget-info">
                        <div>
                            <?= $form
                                ->field($model, 'senderStatus', ['autoPlaceholder' => false])
                                ->textInput(['maxlength' => true, 'placeholder' => 'Статус составителя'])
                            ?>
                        </div>
                        <div class="clearfix">
                            <div class="col-md-6 no-padding-left">
                                <label>Код бюджетной классификации</label>
                                <?=$form->field($model, 'indicatorKbk', ['autoPlaceholder' => false])->textInput(['maxlength' => true])?>
                            </div>
                            <div class="col-md-6 no-padding-right">
                                <label>Код ОКТМО</label>
                                <?=$form->field($model, 'okato', ['autoPlaceholder' => false])->textInput(['maxlength' => true])?>
                            </div>
                        </div>
                        <div class="clearfix">
                            <div class="col-md-6 no-padding-left">
                                <label>Основание налогового<br>платежа</label>
                                <?=$form->field($model, 'indicatorReason', ['autoPlaceholder' => false])->textInput(['maxlength' => true])?>
                            </div>
                            <div class="col-md-6 no-padding-right">
                                <label>Налоговый период или код таможенного органа</label>
                                <?=$form->field($model, 'indicatorPeriod', ['autoPlaceholder' => false])->textInput(['maxlength' => true])?>
                            </div>
                        </div>
                        <div class="clearfix">
                            <div class="col-md-6 no-padding-left">
                                <label>Номер документа</label>
                                <?=$form->field($model, 'indicatorNumber', ['autoPlaceholder' => false])->textInput(['maxlength' => true])?>
                            </div>
                            <div class="col-md-6 no-padding-right">
                                <label>Дата документа</label>
                                <?=$form->field($model, 'indicatorDate', ['autoPlaceholder' => false])->textInput(['maxlength' => true])?>
                            </div>
                        </div>
                        <div>
                            <label>Код выплат</label>
                            <?=$form->field($model, 'indicatorType', ['autoPlaceholder' => false])->textInput(['maxlength' => true])?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-offset-4 col-md-8">
    <?=$form->field($model, 'setTemplate')->checkbox(['class' => 'edm-paymentorder-wizard-set-template'])?>
    <?=$form->field($model, 'setTemplateName')->hiddenInput(['class' => 'edm-paymentorder-wizard-set-template-name'])?>
    <?=Html::submitButton(Yii::t('app', 'Next'), ['name' => 'send', 'class' => 'btn btn-primary edm-paymentorder-wizard-submit'])?>
</div>
<?php
    $form->end();
?>
<div class="modal fade" id="template-name-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Укажите название шаблона платежного поручения</h4>
            </div>
            <div class="modal-body">
                <textarea class="form-control edm-template-name" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary set-template-name">Применить</button>
            </div>
        </div>
    </div>
</div>
<script>
    function cacheWizardFormData() {
        const form = $('.edm-paymentorder-wizard').serialize();
        $.post('/wizard-cache/payment-order/', form);
    }

    // Очистка связанных полей плательщика
    function resetPayer() {
        // При любом изменении счета плательщика очищаем все выбранные значения
        $('#paymentordertype-payername').val('');
        $('#paymentordertype-payerinn').val('');
        $('#paymentordertype-payerbank1').val('');
        $('#paymentordertype-payerbank2').val('');
        $('#paymentordertype-payerbik').val('');
        $('#paymentordertype-payercorrespondentaccount').val('');
        $('#paymentordertype-payerkpp').val('');
        $('#paymentordertype-payercheckingaccount').val('');

        // Очищаем номер счета
        $('.field-payer-account').text('');

        // Скрываем информацию о счете
        $('.payer-info').hide('slow');

        // Очищаем адрес адрес iframe создания нового получателя
        $('#contractorIframe').attr('src', '#');

        // Скрываем кнопку добавления нового получателя платежа
        $('.add-payer-button').hide();

        // Делаем недоступным поле выбора получателя
        $('#paymentordertype-beneficiarycheckingaccount').select2('val', '');
        $('#paymentordertype-beneficiarycheckingaccount').prop('disabled', true);
        resetBeneficiary();
        cacheWizardFormData();
    }

    // Добавление счета в select выбора счета получателя
    // нужно для корректной работы выбора одинаковых счетов
    function addBeneficiaryAccount(data) {
        $('#paymentordertype-beneficiarycheckingaccount')
            .append('<option value="' + data.account +  '"></option>')
	    .val(data.account);
    }

    // Заполнение связанных полей плательщика
    function applyPayer(contractor) {
        var kpp = $('#paymentordertype-payerkpp');
        if (contractor.type === 'IND') {
            kpp.val(0);
            kpp.attr('readonly', true);
        } else {
            kpp.attr('readonly', false);
            kpp.val(contractor.contractor.kpp);
        }

        if (contractor.payerName && contractor.payerName.length > 0) {
            payerName = contractor.payerName;
        } else {
            payerName = contractor.contractor.name;
        }

        $('#paymentordertype-payername').val(payerName);
        $('#paymentordertype-payerinn').val(contractor.contractor.inn);
        $('#paymentordertype-payerbank1').val(contractor.bank.name);
        $('#paymentordertype-payerbank2').val(contractor.bank.city);
        $('#paymentordertype-payerbik').val(contractor.bank.bik);
        $('#paymentordertype-payercorrespondentaccount').val(contractor.bank.account);

        // Заполняем номер счета
        $('.field-payer-account').text(contractor.number);
        // Отображаем скрытый блок с информацией по счету
        $('.payer-info').show('slow');
        // Задаем путь для создания нового получателя платежа и видимость кнопки его добавления
        var srcNewBeneficiary = '/edm/dict-beneficiary-contractor/create?emptyLayout=1';
        // Получаем терминал, к которому относится организация-плательщик
        var terminalId = contractor.contractor.terminalId;
        // Новый адрес с учетом terminalId
        srcNewBeneficiary += '&terminalId=' + terminalId;
        // Задаем новый адрес iframe
        $('#contractorIframe').attr('src', srcNewBeneficiary);
        // Делаем видимой кнопку добавления нового получателя платежа
        $('.add-payer-button').show();
        // Делаем доступным поле выбора получателя
        $('#paymentordertype-beneficiarycheckingaccount').prop('disabled', false);
        // Кэшируем данные формы
        cacheWizardFormData();
    }
    window.beneficiaryBank = [];

    // Сброс значений при очистке получателя
    function resetBeneficiary() {
	$('#paymentordertype-beneficiarycheckingaccount').val('');
	$('#paymentordertype-beneficiaryname').val('');
	$('#paymentordertype-beneficiaryinn').val('');
        $('#paymentordertype-beneficiarykpp').val('');

        // Скрытие всех полей получателя
        $('.beneficiary-info').hide('slow');

        // Очищаем номер счета
        $('.field-beneficiary-account').text('');

        // Скрытие кнопки редактирования получателя
        $('.update-payer-button').hide();

        // Очищаем данные по банку-получателю
        $('#paymentordertype-beneficiarybank1').val('');
        $('#paymentordertype-beneficiarybank2').val('');
        $('#paymentordertype-beneficiarycorrespondentaccount').val('');
        $('#paymentordertype-beneficiarybik').val('');
        $('#hidden-beneficiarycheckingaccount').val('');

        // Кэшируем данные формы
        cacheWizardFormData();
    }

    function applyBeneficiary(item) {
        if (item.name !== undefined && item.inn !== undefined && item.kpp !== undefined) {
            $('#paymentordertype-beneficiaryname').val(item.name);
            $('#paymentordertype-beneficiaryinn').val(item.inn);
            var kpp = $('#paymentordertype-beneficiarykpp');
            if (item.type === 'IND') {
                kpp.val(0);
                kpp.attr('readonly', true);
            } else {
                kpp.attr('readonly', false);
                kpp.val(item.kpp);
            }
            item.bank.id = item.bank.bik;
            window.beneficiaryBank = item.bank;
            $('#select2-paymentordertype-beneficiarybik-container').text(item.bank.name);
            $('#paymentordertype-beneficiarybik')
                .append('<option value="' + item.bank.bik + '"></option>')
                .val(item.bank.bik);
            applyBeneficiaryBank(item.bank);
            // Отображение всех полей получателя
            $('.beneficiary-info').show('slow');
            // Заполняем номер счета
            $('.field-beneficiary-account').text(item.account);
            // Отображение кнопки редактирования счет
            $('.update-payer-button').show();
            // Изменение пути для iframe изменения получателя
            var payerUpdateSrc = '/edm/dict-beneficiary-contractor/update?emptyLayout=1';
            if (item.objectId) {
                accountId = item.objectId;
            } else {
                accountId = item.id;
            }
            $('.update-payer-src').attr('src', payerUpdateSrc + '&id=' + accountId);
            window.contractorIframeUpdate = $('.update-payer-src');
            window.contractorIframeUpdateHref = window.contractorIframeUpdate[0].src;
            // Кэшируем данные формы
            cacheWizardFormData();
        }
    }

    function applyBeneficiaryBank(item) {
        $('#paymentordertype-beneficiarybank1').val(item.name);
        $('#paymentordertype-beneficiarybank2').val(item.city);
        $('#paymentordertype-beneficiarycorrespondentaccount').val(item.account);
    }

    function applyModalContractor(role) {
        $('#dictcontractor-role').val(role);
    }

    function resetNds() {
        // Если в назначении платежа уже присутствует НДС, то заменяем на новое значение
        var ndsRegExp = /(В т\.ч\. НДС \d+% - [\d\.]* руб\.|Без НДС)/gm;
        var paymentPurposeField = $('#paymentordertype-paymentpurpose');
        var paymentPurposeText = paymentPurposeField.val().replace(ndsRegExp, '');
        paymentPurposeField.val(paymentPurposeText.trim());
    }
    
    function onPaymentOrderTypeChange() {
        cacheWizardFormData();
        var percent = $(this).find(':selected').val();

        // Если не заполнена ставка НДС, удаляем из назначения платежа упоминание об НДС и завершаем событие
        if (percent.length === 0) {
            resetNds();
            return false;
        }

        var sum = $('#paymentordertype-sum').val();

        // Если сумма не заполнена или равна 0, завершаем событие
        if (sum.length === 0 || sum === 0) {
            return false;
        }

        if (percent === '0') {
            var textToAdd = 'Без НДС';
        } else {
            // Высчитываем значение НДС
            sum = parseFloat(sum);
            percent = parseFloat(percent);

            var nds = (sum / ((100 + percent) / 100)) * (percent / 100);
            nds = Math.round(nds * 100) / 100;

            // Итоговая строка для добавления
            var textToAdd = 'В т.ч. НДС ' + percent + '% - ' + nds + ' руб.';
        }

        // Добавление к существующему тексту назначения платежа
        var paymentPurposeField = $('#paymentordertype-paymentpurpose');

        resetNds();

        var ndsRegExp = /(В т\.ч\. НДС \d+% - [\d\.]* руб\.|Без НДС)/gm;
        var paymentPurposeText = paymentPurposeField.val().replace( ndsRegExp, '' ) + ' ' + textToAdd;

        paymentPurposeField.val(paymentPurposeText.trim());

        // Кэшируем данные формы
        cacheWizardFormData();
    }

    function onContractorIframeLoad() {
        window.contractorIframeForm = $(this).contents().find('#_DictContractor');
        window.contractorIframeView = $(this).contents().find('#dictContractorView');
        if (contractorIframeForm.length) {
            contractorIframeForm
                .find('#cancelForm')
                .click(function () {
                    contractorIframeForm[0].reset();
                    contractorModal.modal('hide');
                    return false;
                })
                .end()
                .find('#dictcontractor-role')
                .val('<?= DictContractor::ROLE_BENEFICIARY ?>')
                .end()
            ;
            contractorIframeForm.submit(function () {
                window.contractorModalNeedReload = true;
                return true;
            });
        // если загрузились на view, значит всё норм и читаем данные
        } else if (contractorIframeView.length) {
            var data = contractorIframeView.data();
            data.bank = {};
            for (var item in data) {
                if (item.substr(0,5) === 'bank.' && data[item]) {
                    eval('data.' + item + ' = \'' + data[item] + '\';');
                    delete data[item];
                }
            }

            applyBeneficiary(data);
            $('#paymentordertype-beneficiarycheckingaccount').val(data.account);
            $("#hidden-beneficiarycheckingaccount").val(data.account);

            if (data.fullname) {
                $('#select2-paymentordertype-beneficiarycheckingaccount-container').text(data.fullname);
            } else {
                $('#select2-paymentordertype-beneficiarycheckingaccount-container').text(data.name);
            }

            $('#contractorModal').modal('hide');

            // Кэшируем данные формы
            cacheWizardFormData();
        }
    }

    function onContractorIframeUpdate() {
        window.contractorIframeUpdateForm = $(this).contents().find('#_DictContractor');
	window.contractorIframeUpdateView = $(this).contents().find('#dictContractorView');
        if (contractorIframeUpdateForm.length) {
            contractorIframeUpdateForm
                .find('#cancelForm')
                .click(function () {
                    contractorIframeUpdateForm[0].reset();
                    contractorModalUpdate.modal('hide');
                    return false;
                })
                .end()
                .find('#dictcontractor-role')
                .val('<?=DictContractor::ROLE_BENEFICIARY?>')
                .end();
            contractorIframeForm.submit(function () {
                return true;
            });
        }
        // если загрузились на view, значит все норм и читаем данные
        if (contractorIframeUpdateView.length) {
            var data = contractorIframeUpdateView.data();
            data.bank = {};
            for (var item in data) {
                if (item.substr(0,5) === 'bank.' && data[item]) {
                    eval('data.' + item + ' = \'' + data[item] + '\';');
                    delete data[item];
                }
            }

            applyBeneficiary(data);
            $('#paymentordertype-beneficiarycheckingaccount').val(data.account);
            $('#hidden-beneficiarycheckingaccount').val(data.account);
            $('#select2-paymentordertype-beneficiarycheckingaccount-container')
                    .text('ИНН:' + data.inn  + ', ' + data.name + ', ' + data.account);
            $('#contractorModalUpdate').modal('hide');

            // Кэшируем данные формы
            cacheWizardFormData();
        }
    }

    function onContractorModalHide() {
        payerSelectVal = $('#paymentordertype-payercheckingaccount').val();
        // Получаем данные по выбранному счету плательщика
        $.ajax({
            url: '/edm/edm-payer-account/list',
            type: 'get',
            success: function(answer){
                if (!answer || !answer.results) {
                    return false;
                }
                // Перебираем объекты
                for (let i in answer.results) {
                    const object = answer.results[i];
                    // Ищем выбранный счет среди доступных
                    if (object.number === payerSelectVal) {
                        // Задаем путь для создания нового получателя платежа и видимость кнопки его добавления
                        var srcNewBeneficiary = '/edm/dict-beneficiary-contractor/create?emptyLayout=1';
                        // Получаем терминал, к которому относится организация-плательщик
                        var terminalId = object.contractor.terminalId;
                        // Новый адрес с учетом terminalId
                        srcNewBeneficiary += '&terminalId=' + terminalId;
                        // Задаем новый адрес iframe
                        window.contractorIframe[0].src = srcNewBeneficiary;
                    }
                }
            }
        });
    }

    function onContractorModalUpdate() {
        beneficiarySelectVal = $('#paymentordertype-beneficiarycheckingaccount').val();
        $.ajax({
            url: '/edm/dict-beneficiary-contractor/list',
            type: 'get',
            success: function(answer){
                if (!answer || !answer.results) {
                    return false;
                }
                for (let i in answer.results) {
                    const object = answer.results[i];

                    // Ищем выбранный счет среди доступных
                    if (object.account === beneficiarySelectVal) {
                        // Изменение пути для iframe изменения получателя
                        var beneficiaryUpdateSrc = '/edm/dict-beneficiary-contractor/update?emptyLayout=1';
                        if (object.objectId) {
                            accountId = object.objectId;
                        } else {
                            accountId = object.id;
                        }
                        window.contractorIframeUpdate[0].src = beneficiaryUpdateSrc + "&id=" + accountId;
                    }
                }
            }
        });
    }
</script>
<?php
$js = <<<JS
    // Событие выбора ставки НДС
    $('#paymentordertype-vat').on('change', onPaymentOrderTypeChange);
    $('#paymentordertype-sum').change(function() {
        // Кэшируем данные формы
        cacheWizardFormData();
        $('#paymentordertype-vat').trigger('change');
    });

    $('.additional-info-content input').change(cacheWizardFormData);
    $('.additional-info-content select').change(cacheWizardFormData());
    $('.budget-info input').change(cacheWizardFormData());

    window.contractorModal = $('#contractorModal');
    window.contractorModalUpdate = $('#contractorModalUpdate');
    window.contractorModalNeedReload = true;
    window.contractorIframe = $('#contractorIframe');
    window.contractorIframeHref = window.contractorIframe[0].src;
    window.contractorIframeUpdate = $('.update-payer-src');
    window.contractorIframeUpdateHref = window.contractorIframeUpdate[0].src;
    window.contractorIframe.load(onContractorIframeLoad);

    window.contractorIframeUpdate.load(onContractorIframeUpdate);
    // при закрытии модалки загружаем во фрейм исходную страницу
    window.contractorModal.on('hidden.bs.modal', onContractorModalHide);
    window.contractorModalUpdate.on('hidden.bs.modal', onContractorModalUpdate);
    // Событие сворачивания строк связанных с бюджетным платежом
    $('.budget-checkbox').on('click', function() {
        $('.budget-info').toggle('slow');
    });
    // Скрытие/отображение информации о банке плательщика
    $('.payer-bank-link').on('click', function(e) {
        e.preventDefault();
        $('.payer-bank-info').toggle('slow');
    });
    // Скрытие/отображение информации о банке получателя
    $('.beneficiary-bank-link').on('click', function(e) {
        e.preventDefault();
        $('.beneficiary-bank-info').toggle('slow');
    });
    // Скрытие/отображения блока с дополнительной информацией
    $('.additional-info-link').on('click', function(e) {
        e.preventDefault();
        $('.additional-info-content').toggle('slow');
    });

    // Статус отображения блоков в зависимости от заполнения полей счетов
    var payerSelectVal = $('#paymentordertype-payercheckingaccount').val();
    var beneficiarySelectVal = $('#paymentordertype-beneficiarycheckingaccount').val();

    if (payerSelectVal && payerSelectVal.length !== 0) {
        $('.field-payer-account').text(payerSelectVal);
        $('.payer-info').show();

        // Делаем доступным поле выбора получателя
        $('#paymentordertype-beneficiarycheckingaccount').prop('disabled', false);
        // Получаем данные по выбранному счету плательщика
        $.ajax({
            url: '/edm/edm-payer-account/list',
            type: 'get',
            success: function(answer){
                if (!answer || !answer.results) {
                    return false;
                }
                for (let i in answer.results) {
                    // Ищем выбранный счет среди доступных
                    const object = answer.results[i];
                    if (object.number === payerSelectVal) {
                        // Задаем путь для создания нового получателя платежа и видимость кнопки его добавления
                        var srcNewBeneficiary = '/edm/dict-beneficiary-contractor/create?emptyLayout=1';
                        // Получаем терминал, к которому относится организация-плательщик
                        var terminalId = object.contractor.terminalId;
                        // Новый адрес с учетом terminalId
                        srcNewBeneficiary += '&terminalId=' + terminalId;
                        // Задаем новый адрес iframe
                        $('#contractorIframe').attr('src', srcNewBeneficiary);
                        $('.add-payer-button').show();
                    }
                }
            }
        });
    }

    if (beneficiarySelectVal && beneficiarySelectVal.length !== 0) {
        $('.field-beneficiary-account').text(beneficiarySelectVal);
        $('.beneficiary-info').show();

        // Получаем данные по счету получателя
        // Получаем данные по выбранному счету плательщика
        $.ajax({
            url: '/edm/dict-beneficiary-contractor/list',
            type: 'get',
            success: function(answer){
                if (!answer || !answer.results) {
                    return false;
                }

                // Перебираем объекты
                for (let i in answer.results) {
                    const object = answer.results[i];
                    // Ищем выбранный счет среди доступных
                    if (object.account === beneficiarySelectVal) {
                        // Изменение пути для iframe изменения получателя
                        var beneficiaryUpdateSrc = '/edm/dict-beneficiary-contractor/update?emptyLayout=1';
                        if (object.objectId) {
                            accountId = object.objectId;
                        } else {
                            accountId = object.id;
                        }
                        $('.update-payer-src').attr('src', beneficiaryUpdateSrc + "&id=" + accountId);
                        $('.update-payer-button').show();
                    }
                };
            }
        });
    }
    // Перенос строки в поле назначения платежа запрещен
    $('#paymentordertype-paymentpurpose').on('keypress', function(e) {
	    if (e.keyCode === 13) {
            return false;
        }
    });
    // Нельзя вставить текст с
    // переносом строк в поле назначения платежа
    $('#paymentordertype-paymentpurpose').on('change', function(e) {
        value = $(this).val().replace(/\\n/g, " ");
        $(this).val(value);

        // Кэшируем данные формы
        cacheWizardFormData();
    });
    $('.edm-paymentorder-wizard').on('afterValidate', function() {
        // Проверка на ошибки в скрытых областях мастера
        // Бюджетный платеж
        if ($('.budget-block:has(.has-error)').length > 0) {
            // Делаем доступной область бюджетного платежа
            $('.budget-info').show();
            $('.budget-checkbox').attr('checked', true);
        }

        // Дополнительная информация
        if ($('.additional-info:has(.has-error)').length > 0) {
            $('.additional-info-content').show();
        }
    });

    deprecateSpaceSymbol('#paymentordertype-paymentpurpose');

    // Внедряем обработчик события submit формы
    // Перед отправлением формы, если стоит флаг сохранения шаблона,
    // пользователь должен ввести его название
    $('.edm-paymentorder-wizard-submit').on('click', function(e) {
        e.preventDefault();
        // Проверка необходимости сохранения шаблона
        var template = $('.edm-paymentorder-wizard-set-template').prop('checked');
        // Если необходимо задать имя шаблона,
        // то открываем модальное окно с полем ввода
        if (template) {
            $('#template-name-modal').modal();
        } else {
            // Иначе просто отправляем форму
            $('.edm-paymentorder-wizard').submit();
        }
    });

    // Обработка нажатия кнопки применения имени шаблона
    $('.set-template-name').on('click', function(){
        // Имя нового шаблона из модальной формы
        var templateName = $('.edm-template-name').val();

        // Записываем имя шаблона в скрытый параметр формы
        $('.edm-paymentorder-wizard-set-template-name').val(templateName);

        // Закрываем открытое модальное окно
        $('#template-name-modal').modal('hide');

        // Делаем submit формы
        $('.edm-paymentorder-wizard').submit();
    });

    $('.btn-beneficiary-submit').on('click', function(e) {
        e.preventDefault();
        var form = $('#contractorIframe').contents().find('form#w0');
        form.submit();
    });

    $('.btn-beneficiary-update-submit').on('click', function(e) {
        e.preventDefault();
        var form = $('.update-payer-src#contractorIframe').contents().find('form#w0');
        form.submit();
    });
JS;
$this->registerJS($js);
