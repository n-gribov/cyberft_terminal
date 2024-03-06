<?php

use addons\edm\helpers\Dict;
use addons\edm\models\DictBeneficiaryContractor;
use common\widgets\Alert;
use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\MaskedInput;

$model->paymentPurposeNds = null;
$model->priority = 5;

?>

<?php

$form = ActiveForm::begin([
    'type'                   => ActiveForm::TYPE_INLINE,
    'id' => 'edm-template-wizard',
    'action' => '',
    'enableClientValidation' => true,
    'formConfig'             => [
        'labelSpan'  => 3,
        'deviceSize' => ActiveForm::SIZE_TINY,
        'showErrors' => true,
    ]
]);
?>

<?php DatePicker::widget([
    'id'         => 'paymentordertype-date',
    'dateFormat' => 'dd.MM.yyyy',
]) ?>
<?php MaskedInput::widget([
    'id'            => 'paymentordertype-date',
    'name'          => 'paymentordertype-date',
    'mask'          => '99.99.9999',
    'clientOptions' => [
        'placeholder' => "dd.MM.yyyy",
    ]
])?>

<div class="row">

    <div class="col-md-12" style="padding-top: 15px">
    <div class="payment-order-wrapper">
        <?= Alert::widget() ?>
        <div class="payment-order-header clearfix">
            <div class="payment-order-header-column payment-order-header-column-template-name-label">
                <?=Html::label(Yii::t('edm', 'Template name'))?>
            </div>
            <div class="payment-order-header-column payment-order-header-column-template-name">
                <?=$form->field($template, 'name')->textInput()?>
            </div>
            <div class="payment-order-header-column payment-order-header-number">
                <label>Номер документа</label>
            </div>
            <div class="payment-order-header-column payment-order-header-column-number payment-order-header-right-column" style="margin-right: 25px;">
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
                        echo $form->field($model, 'payerCheckingAccount', ['selectors' => ['input' => '#paymentordertype-payercheckingaccount-modal']])->widget(Select2::classname(), [
                            'options'       => ['placeholder' => 'Поиск плательщика по имени или счету ...', 'class' => 'has-success', 'id' => 'paymentordertype-payercheckingaccount-modal'],
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'pluginOptions' => [
                                'allowClear'         => true,
                                'minimumInputLength' => 0,
                                'ajax'               => [
                                    'url'      => Url::to(['edm-payer-account/list']),
                                    'dataType' => 'json',
                                    'delay'    => 250,
                                    'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
                                ],
                                'templateResult'     => new JsExpression('function(item) {
                                            if (!item.number) return item.text; return item.name + ", " + item.number + ", " + item.currencyInfo.name;
                                        }'),
                                'templateSelection'  => new JsExpression('function(item) {
                                            if (!item.number) return item.text; return item.name + ", " + item.number + ", " + item.currencyInfo.name;
                                        }'),
                            ],
                            'pluginEvents'  => [
                                'select2:select' => 'function(e) {
                                applyPayer(e.params.data);
                              }',
                                'select2:unselect' => 'function(e) {
                                resetPayer();
                              }'
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
                                <div class="col-md-6 no-padding-left">
                                    <label class="form-inline-label">ИНН</label>
                                    <div class="form-inline-field width100">
                                        <?=Html::activeTextInput($model, 'payerInn', ['readonly' => true])?>
                                    </div>
                                </div>
                                <div class="col-md-6 no-padding-right">
                                    <label class="form-inline-label">КПП</label>
                                    <div class="form-inline-field width100">
                                        <?=Html::activeTextInput($model, 'payerKpp', ['readonly' => true])?>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="form-inline-label">Наименование</label>
                                <div class="form-inline-field width400">
                                    <?=Html::activeTextInput($model, 'payerName', ['readonly' => true])?>
                                </div>
                                <?=Html::activeHiddenInput($model, 'payerName1', ['readonly' => true])?>
                            </div>

                            <div class="payer-bank-block">
                                <div class="payer-bank-block-header">
                                    <a href="#" class="payer-bank-link width130">Банк плательщика</a>
                                    <div class="form-inline-field width424">
                                        <?=Html::activeTextInput($model, 'payerBank1', ['readonly' => true])?>
                                    </div>
                                </div>

                                <div class="payer-bank-info">
                                    <div>
                                        <label class="form-inline-label">БИК</label>
                                        <div class="form-inline-field form-inline-subfield">
                                            <?=Html::activeTextInput($model, 'payerBik', ['readonly' => true])?>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="form-inline-label">Город</label>
                                        <div class="form-inline-field form-inline-subfield">
                                            <?=Html::activeTextInput($model, 'payerBank2', ['readonly' => true])?>
                                        </div>
                                    </div>

                                    <div>
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
                                'id' => 'contractorModalTemplate',
                                'tabindex' => false // important for Select2 to work properly
                            ],
                            'header' => '<h4>Новый получатель</h4>',
                            'footer' => '<button class="btn btn-success btn-beneficiary-submit">Создать</button>',
                            'toggleButton' => [
                                'label' => '',
                                'title' => 'Новый получатель',
                                'tag' => 'i',
                                'class' => 'fa fa-plus-square add-payer-button',
                                'style' => 'cursor: pointer; font-size: 34px; color: #2B8F0E; float: right; margin-right: 15px;'
                            ],
                        ]);
                        ?>
                        <iframe src="#" width="100%" height="480" frameborder="0" id="contractorIframeTemplate"></iframe>
                        <?php Modal::end();?>


                        <?php
                        Modal::begin([
                            'options' => [
                                'id' => 'contractorModalUpdateTemplate',
                                'tabindex' => false // important for Select2 to work properly
                            ],
                            'header' => '<h4>Редактировать получателя</h4>',
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
                        <iframe src="#" width="100%" height="480" frameborder="0" class="update-payer-src-template" id="contractorIframe"></iframe>
                        <?php Modal::end();?>

                        <?php
                        /** @var DictBeneficiaryContractor $item */
                        $item = DictBeneficiaryContractor::findOne(['account' => $model->beneficiaryCheckingAccount]);
                        echo $form->field($model, 'beneficiaryCheckingAccount', [
                            'options' => ['style' => 'width: 90%'],
                            'selectors' => ['input' => '#paymentordertype-beneficiarycheckingaccount-modal']])
                        ->widget(Select2::classname(), [
                            'initValueText' => isset($item) ?
                                'Счет: ' . $item->account . ' Название: ' . $item->name : null,
                            // set the initial display text
                            'options'       => ['placeholder' => 'Поиск получателя по имени или счету ...', 'id' => 'paymentordertype-beneficiarycheckingaccount-modal'],
                            'disabled' => empty($model->payerCheckingAccount),
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'pluginOptions' => [
                                'allowClear'         => true,
                                'minimumInputLength' => 0,
                                'ajax'               => [
                                    'url'      => Url::to(['dict-beneficiary-contractor/list']),
                                    'dataType' => 'json',
                                    'delay'    => 250,
                                    'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
                                    'processResults' => new JsExpression('function(data, query) {
                                    if (query.term) {
                                        data.results.push({
                                            id: query.term,
                                            text: query.term + " (Новое значение)"
                                        });
                                    }
                                    return data;
                                }'),
                                ],
                                'templateResult'     => new JsExpression('function(item) {
                                if (!item.account) return item.text; return "ИНН:" + item.inn  + ", " + item.name + ", " + item.account;
                            }'),
                                'templateSelection'  => new JsExpression('function(item) {
                                if (!item.account) return item.text; return "ИНН:" + item.inn  + ", " + item.name + ", " + item.account;
                            }'),
                            ],
                            'pluginEvents'  => [
                                'select2:select' => 'function(e) {
                                applyBeneficiary(e.params.data);
                            }',
                                'select2:unselect' => 'function(e) {
                                resetBeneficiary();
                            }',
                            ],
                        ])
                        ?>
                        <div class="beneficiary-info">
                            <div class="clearfix">
                                <div class="col-md-6 no-padding-left">
                                    <label class="form-inline-label">Счет №</label>
                                    <div class="form-inline-field width100 field-beneficiary-account"></div>
                                </div>
                            </div>
                            <div class="clearfix">
                                <div class="col-md-6 no-padding-left">
                                    <label class="form-inline-label">ИНН</label>
                                    <div class="form-inline-field width100">
                                        <?=Html::activeTextInput($model, 'beneficiaryInn', ['readonly' => true])?>
                                    </div>
                                </div>
                                <div class="col-md-6 no-padding-right">
                                    <label class="form-inline-label">КПП</label>
                                    <div class="form-inline-field width100">
                                        <?=Html::activeTextInput($model, 'beneficiaryKpp', ['readonly' => true])?>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="form-inline-label">Наименование</label>
                                <div class="form-inline-field width400">
                                    <?=Html::activeTextInput($model, 'beneficiaryName', ['id' => 'paymentordertype-beneficiaryname-modal', 'readonly' => true])?>
                                </div>
                            </div>

                            <div class="beneficiary-bank-block">
                                <div class="beneficiary-bank-block-header">
                                    <a href="#" class="beneficiary-bank-link width130">Банк получателя</a>
                                    <div class="form-inline-field width424">
                                        <?=Html::activeTextInput($model, 'beneficiaryBank1', ['readonly' => true])?>
                                    </div>
                                </div>

                                <div class="beneficiary-bank-info">
                                    <div>
                                        <label class="form-inline-label">БИК</label>
                                        <div class="form-inline-field form-inline-subfield">
                                            <?=Html::activeTextInput($model, 'beneficiaryBik', ['readonly' => true])?>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="form-inline-label">Город</label>
                                        <div class="form-inline-field form-inline-subfield">
                                            <?=Html::activeTextInput($model, 'beneficiaryBank2', ['readonly' => true])?>
                                        </div>
                                    </div>

                                    <div>
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
                                <?=$form->field($model, 'sum')->textInput(['maxlength' => true])?>
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
                            <div>
                                <?=$form
                                    ->field($model, 'paymentType')
                                    ->dropDownList(
                                        [null => $model->getAttributeLabel('paymentType')]
                                        + Dict::paymentType()
                                    )
                                ?>
                                <?= $form
                                    ->field($model, 'senderStatus', ['autoPlaceholder' => false])
                                    ->textInput(['maxlength' => true, 'placeholder' => 'Статус составителя'])
                                ?>
                            </div>

                            <div class="clearfix">
                                <div class="col-md-6 no-padding-left">
                                    <label>* Вид операции</label>
                                    <?=$form->field($model, 'payType')->textInput(['maxlength' => 2, 'value' => '01'])?>
                                </div>

                                <div class="col-md-6 no-padding-right">
                                    <label>Наз. пл.</label>
                                    <?= $form->field($model, 'paymentOrderPaymentPurpose')->textInput(['maxlength' => TRUE, 'readonly' => TRUE]); ?>
                                </div>
                            </div>

                            <div class="clearfix">
                                <div class="col-md-6 no-padding-left">
                                    <label>Код</label>
                                    <?= $form->field($model, 'code', ['autoPlaceholder' => false])
                                        ->textInput(['maxlength' => 25, 'placeholder' => 'Код']);
                                    ?>
                                </div>
                                <div class="col-md-6 no-padding-right">
                                    <label>Срок платежа</label>
                                    <?= $form->field($model, 'maturity')->textInput(['maxlength' => TRUE, 'readonly' => TRUE]); ?>
                                </div>
                            </div>

                            <div class="clearfix">
                                <div class="col-md-6 no-padding-left">
                                    <label>Очередность платежа</label>
                                    <?=$form->field($model, 'priority')->dropDownList(Dict::priority())?>
                                </div>

                                <div class="col-md-6 no-padding-right">
                                    <label>Результирующее поле</label>
                                    <?= $form->field($model, 'backingField')->textInput(['maxlength' => TRUE, 'readonly' => TRUE]); ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="budget-block">
                    <h4 class="budget-header">Бюджетный платеж&nbsp<?=Html::checkbox('budgetCheckbox', null, ['class' => 'budget-checkbox'])?></h4>

                    <div class="budget-info">
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
                                <label>Основание налогового платежа</label>
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

                        <div class="budget-bottom">
                            <label>Тип платежа</label>
                            <?=$form->field($model, 'indicatorType', ['autoPlaceholder' => false])->textInput(['maxlength' => true])?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<?=$form->field($template, 'id')->hiddenInput()?>

<?=Html::hiddenInput('createDocument', 0, ['class' => 'hidden-create-document'])?>

<?php $form->end();


$this->registerCss('

#edmTemplatePOModal .form-inline .form-group {
    width: 100%;
}

#edmTemplatePOModal .form-inline .form-group .form-control {
    width: 100% ;
}

#edmTemplatePOModal table tr.iespike {
    border: none;
}
#edmTemplatePOModal table tr.iespike td {
    border: none; padding: 0;
}
#edmTemplatePOModal table > tbody tr.payment-order-bottom-row td {
    vertical-align: bottom;
    width: 14.28%;
}
#edmTemplatePOModal .payment-order-bottom-row label.small {
    font-weight: normal;
}

/* Сетка платежного поручения */
#edmTemplatePOModal .payment-order-left-column {
    width: 559px;
}

#edmTemplatePOModal .payment-order-right-column {
    width: 607px;
}

#edmTemplatePOModal .payer-info {
    display: none;
}

#edmTemplatePOModal .beneficiary-info {
    display: none;
}

#edmTemplatePOModal .budget-info {
    display: none;
}

#edmTemplatePOModal .no-padding-left {
    padding-left: 0;
}

#edmTemplatePOModal .no-padding-right {
    padding-right: 0;
}

#edmTemplatePOModal .form-inline-field {
    display: inline-block;
}

#edmTemplatePOModal .form-inline-field input {
    border: 0;
    width: 100%;
}

#edmTemplatePOModal .form-inline-subfield input {
    border: 0;
    width: 100%;
}

#edmTemplatePOModal .payer-bank-info {
    display: none;
    padding-left: 133px;
}

#edmTemplatePOModal .beneficiary-bank-info {
    display: none;
    padding-left: 122px;
}

#edmTemplatePOModal .payer-bank-link {
    font-weight: bold;
}

#edmTemplatePOModal .beneficiary-bank-link {
    font-weight: bold;
}

#edmTemplatePOModal .payer-bank-block-header {
    margin-bottom: 5px;
}

#edmTemplatePOModal .beneficiary-bank-block-header {
    margin-bottom: 5px;
}

#edmTemplatePOModal .width100 input {
    width: 100px;
}

#edmTemplatePOModal .width400 input {
    width: 400px;
}

#edmTemplatePOModal .update-payer-button {
    display: none;
    z-index: 5;
}

#edmTemplatePOModal .beneficiary-account {
    position: relative;
}

#edmTemplatePOModal .payment-order-header-column {
    display: inline-block;
    position: relative;
}

#edmTemplatePOModal .payment-order-header-right-column {
    width: 100px;
}

#edmTemplatePOModal .form-label {
    display: block;
}

#edmTemplatePOModal .additional-info {
    padding-left: 35px;
}

#edmTemplatePOModal .additional-info-link {
    text-decoration: underline;
    display: block;
    margin-bottom: 15px;
}

#edmTemplatePOModal .additional-info-content {
    display: none;
}

#edmTemplatePOModal #paymentordertype-number {
    width: 62px;
}

#edmTemplatePOModal .payment-order-header-column-number {
    width: 70px;
}

#edmTemplatePOModal .payment-order-header-number {
    margin-right: 5px;
    vertical-align: top;
    padding-top: 8px;
}

#edmTemplatePOModal .payment-order-header-column-label-date {
    margin-right: 5px;
    vertical-align: top;
    padding-top: 8px;
}

#edmTemplatePOModal #paymentordertype-date {
    width: 103px;
}

#edmTemplatePOModal .payment-order-header-column-date {
    width: 130px;
    vertical-align: top;
}

#edmTemplatePOModal .payment-order-header-template-name {
    margin-bottom: 15px;
}

#edmTemplatePOModal .payer-block {
    margin-bottom: 16px;
}

#edmTemplatePOModal .beneficiary-block {
    margin-bottom: 25px;
}

#edmTemplatePOModal #paymentordertype-paymentpurpose {
    resize: none;
}

#edmTemplatePOModal .width130 {
    width: 130px;
}

#edmTemplatePOModal .width424 {
    width: 424px;
}

#edmTemplatePOModal .add-payer-button {
    display: none;
}

#edmTemplatePOModal .modal-body {
    padding-top: 0;
}

#edmTemplatePOModal .modal-content {
    background-color: #eee;
}

#edmTemplatePOModal .modal-body {
    background-color: #fff;
}

#edmTemplatePOModal .modal-header {
    border-bottom: 0;
}

#edmTemplatePOModal .modal-footer {
    border-top: 0;
}

#edmTemplatePOModal .modal-dialog {
    width: 700px;
}

#edmTemplatePOModal .payment-order-wrapper {
    padding-left: 0;
}

#edmTemplatePOModal .field-paymentordertype-number .help-block {
    width: 146px;
}

#contractorModalTemplate {
    z-index: 9999;
    padding-left: 0 !important;
    margin-top: 30px;
}

#contractorModalUpdateTemplate {
    z-index: 9999;
    padding-left: 0 !important;
    margin-top: 30px;
}

.payment-order-header-column-template-name {
    width: 63%;
    margin-bottom: 20px;
}

.payment-order-header-column-template-name-label {
    vertical-align: top;
    padding-top: 8px;
}

#edmTemplatePOModal #contractorModalTemplate .modal-body {
    height: 470px;
}

#edmTemplatePOModal #contractorModalUpdateTemplate .modal-body {
    height: 470px;
}

#edmTemplatePOModal #contractorModalTemplate .modal-dialog {
    border: 2px solid #000;
    width: 670px;
}

#edmTemplatePOModal #contractorModalUpdateTemplate .modal-dialog {
    border: 2px solid #000;
    width: 670px;
}



');

$script = <<< JS
        
// Очистка связанных полей плательщика
var resetPayer = function() {
    // При любом изменении счета плательщика очищаем все выбранные значения
    $('#edmTemplatePOModal #paymentordertype-payername').val('');
    $('#edmTemplatePOModal #paymentordertype-payerinn').val('');
    $('#edmTemplatePOModal #paymentordertype-payerbank1').val('');
    $('#edmTemplatePOModal #paymentordertype-payerbank2').val('');
    $('#edmTemplatePOModal #paymentordertype-payerbik').val('');
    $('#edmTemplatePOModal #paymentordertype-payercorrespondentaccount').val('');
    $('#edmTemplatePOModal #paymentordertype-payerkpp').val('');

    // Очищаем номер счета
    $('#edmTemplatePOModal .field-payer-account').text('');

    // Скрываем информацию о счете
    $('#edmTemplatePOModal .payer-info').hide('slow');

    // Очищаем адрес адрес iframe создания нового получателя
    $('#edmTemplatePOModal #contractorIframeTemplate').attr('src', '#');

    // Скрываем кнопку добавления нового получателя платежа
    $('#edmTemplatePOModal .add-payer-button').hide();

    // Делаем недоступным поле выбора получателя
    $('#edmTemplatePOModal #paymentordertype-beneficiarycheckingaccount-modal').select2("val", "");
    $('#edmTemplatePOModal #paymentordertype-beneficiarycheckingaccount-modal').prop('disabled', true);
    resetBeneficiary();
};

// Заполнение связанных полей плательщика
var applyPayer = function(contractor) {
    var kpp = $('#edmTemplatePOModal #paymentordertype-payerkpp');

    if (contractor.type == 'IND') {
        kpp.val(0);
        kpp.attr('readonly', true);
    } else {
        kpp.attr('readonly', false);
        kpp.val(contractor.contractor.kpp);
    }

    $('#edmTemplatePOModal #paymentordertype-payername').val(contractor.contractor.name);
    $('#edmTemplatePOModal #paymentordertype-payerinn').val(contractor.contractor.inn);
    $('#edmTemplatePOModal #paymentordertype-payerbank1').val(contractor.bank.name);
    $('#edmTemplatePOModal #paymentordertype-payerbank2').val(contractor.bank.city);
    $('#edmTemplatePOModal #paymentordertype-payerbik').val(contractor.bank.bik);
    $('#edmTemplatePOModal #paymentordertype-payercorrespondentaccount').val(contractor.bank.account);

    // Изменение состояния полей
    $('#edmTemplatePOModal #paymentordertype-payerinn').attr('readonly', true);
    $('#edmTemplatePOModal #paymentordertype-payername').attr('readonly', true);
    $('#edmTemplatePOModal #paymentordertype-payerbank1').attr('readonly', true);
    $('#edmTemplatePOModal #paymentordertype-payerbank2').attr('readonly', true);
    $('#edmTemplatePOModal #paymentordertype-payerbik').attr('readonly', true);
    $('#edmTemplatePOModal #paymentordertype-payercorrespondentaccount').attr('readonly', true);
    $('#edmTemplatePOModal #paymentordertype-payerkpp').attr('readonly', true);

    // Заполняем номер счета
    $('#edmTemplatePOModal .field-payer-account').text(contractor.number);

    // Отображаем скрытый блок с информацией по счету
    $('#edmTemplatePOModal .payer-info').show('slow');

    // Задаем путь для создания нового получателя платежа и видимость кнопки его добавления
    var srcNewBeneficiary = '/edm/dict-beneficiary-contractor/create?emptyLayout=1';

    // Получаем терминал, к которому относится организация-плательщик
    var terminalId = contractor.contractor.terminalId;

    // Новый адрес с учетом terminalId
    srcNewBeneficiary += '&terminalId=' + terminalId;

    // Задаем новый адрес iframe
    $('#edmTemplatePOModal #contractorIframeTemplate').attr('src', srcNewBeneficiary);

    // Делаем видимой кнопку добавления нового получателя платежа
    $('#edmTemplatePOModal .add-payer-button').show();

    // Делаем доступным поле выбора получателя
    $('#edmTemplatePOModal #paymentordertype-beneficiarycheckingaccount-modal').prop('disabled', false);
};

window.beneficiaryBank = [];

// Сброс значений при очистке получателя
var resetBeneficiary = function() {
    $('#edmTemplatePOModal #paymentordertype-beneficiaryname-modal').val('');
    $('#edmTemplatePOModal #paymentordertype-beneficiaryinn').val('');
    $('#edmTemplatePOModal #paymentordertype-beneficiarykpp').val('');

    // Скрытие всех полей получателя
    $('#edmTemplatePOModal .beneficiary-info').hide('slow');

    // Очищаем номер счета
    $('#edmTemplatePOModal .field-beneficiary-account').text('');

    // Скрытие кнопки редактирования получателя
    $('#edmTemplatePOModal .update-payer-button').hide();
};

var applyBeneficiary = function(item) {
    if (item.name != undefined && item.inn != undefined && item.kpp != undefined) {
        $('#edmTemplatePOModal #paymentordertype-beneficiaryname-modal').val(item.name);
        $('#edmTemplatePOModal #paymentordertype-beneficiaryinn').val(item.inn);
        var kpp = $('#edmTemplatePOModal #paymentordertype-beneficiarykpp');
        if (item.type == 'IND') {
            kpp.val(0);
            kpp.attr('readonly', true);
        } else {
            kpp.attr('readonly', false);
            kpp.val(item.kpp);
        }

        item.bank.id = item.bank.bik;
        window.beneficiaryBank = item.bank;
        $('#edmTemplatePOModal #select2-paymentordertype-beneficiarybik-container').text(item.bank.name);
        $('#edmTemplatePOModal #paymentordertype-beneficiarybik')
            .append('<option value="' + item.bank.bik + '"></option>')
            .val(item.bank.bik);

        // Выключение состояний полей
        $('#edmTemplatePOModal #paymentordertype-beneficiaryname-modal').attr('readonly', true);
        $('#edmTemplatePOModal #paymentordertype-beneficiaryinn').attr('readonly', true);
        $('#edmTemplatePOModal #paymentordertype-beneficiarykpp').attr('readonly', true);

        applyBeneficiaryBank(item.bank);

        // Отображение всех полей получателя
        $('#edmTemplatePOModal .beneficiary-info').show('slow');

        // Заполняем номер счета
        $('#edmTemplatePOModal .field-beneficiary-account').text(item.account);

        // Отображение кнопки редактирования счет
        $('#edmTemplatePOModal .update-payer-button').show();

        // Изменение пути для iframe изменения получателя
        var payerUpdateSrc = '/edm/dict-beneficiary-contractor/update?emptyLayout=1';

        if (item.objectId) {
            accountId = item.objectId;
        } else {
            accountId = item.id;
        }

        $('#edmTemplatePOModal .update-payer-src-template').attr('src', payerUpdateSrc + "&id=" + accountId);

        window.contractorIframeUpdate = $('#edmTemplatePOModal .update-payer-src-template');
        window.contractorIframeUpdateHref = window.contractorIframeUpdate[0].src;
    }
};

var applyBeneficiaryBank = function(item) {
    $('#edmTemplatePOModal #paymentordertype-beneficiarybank1').val(item.name);
    $('#edmTemplatePOModal #paymentordertype-beneficiarybank2').val(item.city);
    $('#edmTemplatePOModal #paymentordertype-beneficiarycorrespondentaccount').val(item.account);

    // Статус полей банка
    $('#edmTemplatePOModal #paymentordertype-beneficiarybank1').attr('readonly', true);
    $('#edmTemplatePOModal #paymentordertype-beneficiarybank2').attr('readonly', true);
    $('#edmTemplatePOModal #paymentordertype-beneficiarycorrespondentaccount').attr('readonly', true);
};

var applyModalContractor = function(role) {
    $('#edmTemplatePOModal #dictcontractor-role').val(role);
};

// Событие выбора ставки НДС
$('#edmTemplatePOModal #paymentordertype-vat').on('change', function() {

    var percent = $(this).find(":selected").val();

    // Если не заполнена ставка НДС, завершаем событие
    if (percent.length === 0) {
        return false;
    }

    var sum = $('#edmTemplatePOModal #paymentordertype-sum').val();

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
        nds = Math.round(nds * 100)/100;

        // Итоговая строка для добавления
        var textToAdd = 'В т.ч. НДС ' + percent + '% - ' + nds + ' руб.';
    }

    // Добавление к существующему тексту назначения платежа
    var paymentPurposeField = $('#edmTemplatePOModal #paymentordertype-paymentpurpose');

    var paymentPurposeText = paymentPurposeField.val();

    paymentPurposeField.val(paymentPurposeText + ' ' + textToAdd);

});

window.contractorModalTemplate = $('#edmTemplatePOModal #contractorModalTemplate');
window.contractorModalUpdateTemplate = $('#edmTemplatePOModal #contractorModalUpdateTemplate');

window.contractorModalNeedReload = true;

window.contractorIframeTemplate = $('#edmTemplatePOModal #contractorIframeTemplate');
window.contractorIframeHref = window.contractorIframeTemplate[0].src;

window.contractorIframeUpdate = $('#edmTemplatePOModal .update-payer-src-template');
window.contractorIframeUpdateHref = window.contractorIframeUpdate[0].src;

window.contractorIframeTemplate.load(function () {
    window.contractorIframeForm = $(this).contents().find('#_DictContractor');
    window.contractorIframeView = $(this).contents().find('#dictContractorView');
    if (contractorIframeForm.length) {
        contractorIframeForm
            .find('#edmTemplatePOModal #cancelForm')
            .click(function () {
                contractorIframeForm[0].reset();
                contractorModalTemplate.modal('hide');
                return false;
            })
            .end()
            .find('#edmTemplatePOModal #dictcontractor-role')
            .val('<?=DictContractor::ROLE_BENEFICIARY?>')
            .end()

        ;
        contractorIframeForm.submit(function () {
            window.contractorModalNeedReload = true;
            return true;
        });
        // если загрузились на view, значит все норм и читаем данные
    } else if (contractorIframeView.length) {
        var data = contractorIframeView.data();
        data.bank = {};
        for (var item in data) {
            if (item.substr(0,5) == 'bank.' && data[item]) {
                eval('data.' + item + ' = \'' + data[item] + '\';');
                delete data[item];
            }
        }
        applyBeneficiary(data);
        $('#edmTemplatePOModal #paymentordertype-beneficiarycheckingaccount-modal')
            .append('<option value="' + data.account + '"></option>')
            .val(data.account)
        ;

        if(data.fullname) {
            $('#edmTemplatePOModal #select2-paymentordertype-beneficiarycheckingaccount-modal-container').text(data.fullname);
        } else {
            $('#edmTemplatePOModal #select2-paymentordertype-beneficiarycheckingaccount-modal-container').text(data.name);
        }

        $('#edmTemplatePOModal #contractorModalTemplate').modal('hide');
    }
});

window.contractorIframeUpdate.load(function () {
    window.contractorIframeUpdateForm = $(this).contents().find('#_DictContractor');
    window.contractorIframeUpdateView = $(this).contents().find('#dictContractorView');
    if (contractorIframeUpdateForm.length) {
        contractorIframeUpdateForm
            .find('#edmTemplatePOModal #cancelForm')
            .click(function () {
                contractorIframeUpdateForm[0].reset();
                contractorModalUpdateTemplate.modal('hide');
                return false;
            })
            .end()
            .find('#edmTemplatePOModal #dictcontractor-role')
            .val('<?=DictContractor::ROLE_BENEFICIARY?>')
            .end()

        ;
        contractorIframeForm.submit(function () {
            return true;
        });
        // если загрузились на view, значит все норм и читаем данные
    }

    if (contractorIframeUpdateView.length) {
        var data = contractorIframeUpdateView.data();
        data.bank = {};
        for (var item in data) {
            if (item.substr(0,5) == 'bank.' && data[item]) {
                eval('data.' + item + ' = \'' + data[item] + '\';');
                delete data[item];
            }
        }

        applyBeneficiary(data);
        $('#edmTemplatePOModal #paymentordertype-beneficiarycheckingaccount-modal')
            .append('<option value="' + data.account + '"></option>')
            .val(data.account)
        ;

        $('#edmTemplatePOModal #select2-paymentordertype-beneficiarycheckingaccount-modal-container').text("ИНН:" + data.inn  + " Название: " + data.name + " Счет: " + data.account);


        $('#contractorModalUpdateTemplate').modal('hide');
    }

});

// при закрытии модалки загружаем во фрейм исходную страницу
window.contractorModalTemplate.on('hidden.bs.modal', function(e) {

    payerSelectVal = $('#edmTemplatePOModal #paymentordertype-payercheckingaccount-modal').val();

    // Получаем данные по выбранному счету плательщика
    $.ajax({
        url: '/edm/edm-payer-account/list',
        type: 'get',
        success: function(answer){
            if (!answer) {
                return false;
            }

            if (!answer.results) {
                return false;
            }

            var objects = answer.results;
            // Перебираем объекты

            objects.forEach(function(object) {

                // Ищем выбранный счет среди доступных
                if (object.number == payerSelectVal) {

                    // Задаем путь для создания нового получателя платежа и видимость кнопки его добавления
                    var srcNewBeneficiary = '/edm/dict-beneficiary-contractor/create?emptyLayout=1';

                    // Получаем терминал, к которому относится организация-плательщик
                    var terminalId = object.contractor.terminalId;

                    // Новый адрес с учетом terminalId
                    srcNewBeneficiary += '&terminalId=' + terminalId;

                    // Задаем новый адрес iframe
                    window.contractorIframeTemplate[0].src = srcNewBeneficiary;
                }
            });
        }
    });
});

window.contractorModalUpdateTemplate.on('hidden.bs.modal', function(e) {

    beneficiarySelectVal = $('#edmTemplatePOModal #paymentordertype-beneficiarycheckingaccount-modal').val();

    $.ajax({
        url: '/edm/dict-beneficiary-contractor/list',
        type: 'get',
        success: function(answer) {

            if (!answer) {
                return false;
            }

            if (!answer.results) {
                return false;
            }

            var objects = answer.results;
            // Перебираем объекты

            objects.forEach(function(object) {

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
            });
        }
    });
});

// Событие сворачивания строк связанных с бюджетным платежом
$('#edmTemplatePOModal .budget-checkbox').on('click', function() {
    $('#edmTemplatePOModal .budget-info').toggle('slow');

    $('#edmTemplatePOModal .modal-body').stop().animate({
            scrollTop: $(".budget-bottom").offset().top
        }, 1000);
});

// Скрытие/отображение информации о банке плательщика
$('#edmTemplatePOModal .payer-bank-link').on('click', function(e) {
    e.preventDefault();
    $('#edmTemplatePOModal .payer-bank-info').toggle('slow');
});

// Скрытие/отображение информации о банке получателя
$('#edmTemplatePOModal .beneficiary-bank-link').on('click', function(e) {
    e.preventDefault();
    $('#edmTemplatePOModal .beneficiary-bank-info').toggle('slow');
});

// Скрытие/отображения блока с дополнительной информацией
$('#edmTemplatePOModal .additional-info-link').on('click', function(e) {
    e.preventDefault();
    $('#edmTemplatePOModal .additional-info-content').toggle('slow');

    $('#edmTemplatePOModal .modal-body').stop().animate({
            scrollTop: $("#edmTemplatePOModal .budget-header").offset().top
        }, 1000);
});

// Статус отображения блоков в зависимости от заполнения полей счетов
var payerSelectVal = $('#edmTemplatePOModal #paymentordertype-payercheckingaccount-modal').val();
var beneficiarySelectVal = $('#edmTemplatePOModal #paymentordertype-beneficiarycheckingaccount-modal').val();

if (payerSelectVal && payerSelectVal.length != 0) {
    $('#edmTemplatePOModal .field-payer-account').text(payerSelectVal);
    $('#edmTemplatePOModal .payer-info').show();

    // Делаем доступным поле выбора получателя
    $('#edmTemplatePOModal #paymentordertype-beneficiarycheckingaccount-modal').prop('disabled', false);

    // Получаем данные по выбранному счету плательщика
    $.ajax({
        url: '/edm/edm-payer-account/list',
        type: 'get',
        success: function(answer){
            if (!answer) {
                return false;
            }

            if (!answer.results) {
                return false;
            }

            var objects = answer.results;
            // Перебираем объекты

            objects.forEach(function(object) {

                // Ищем выбранный счет среди доступных
                if (object.number == payerSelectVal) {

                    // Задаем путь для создания нового получателя платежа и видимость кнопки его добавления
                    var srcNewBeneficiary = '/edm/dict-beneficiary-contractor/create?emptyLayout=1';

                    // Получаем терминал, к которому относится организация-плательщик
                    var terminalId = object.contractor.terminalId;

                    // Новый адрес с учетом terminalId
                    srcNewBeneficiary += '&terminalId=' + terminalId;

                    // Задаем новый адрес iframe
                    $('#edmTemplatePOModal #contractorIframeTemplate').attr('src', srcNewBeneficiary);

                    $('#edmTemplatePOModal .add-payer-button').show();
                }

            });

        }
    });
}

if (beneficiarySelectVal.length != 0) {
    $('#edmTemplatePOModal .field-beneficiary-account').text(beneficiarySelectVal);
    $('#edmTemplatePOModal .beneficiary-info').show();

    // Получаем данные по счету получателя
    // Получаем данные по выбранному счету плательщика
    $.ajax({
        url: '/edm/dict-beneficiary-contractor/list',
        type: 'get',
        success: function(answer){

            if (!answer) {
                return false;
            }

            if (!answer.results) {
                return false;
            }

            var objects = answer.results;
            // Перебираем объекты

            objects.forEach(function(object) {

                // Ищем выбранный счет среди доступных
                if (object.account === beneficiarySelectVal) {

                    // Изменение пути для iframe изменения получателя
                    var beneficiaryUpdateSrc = '/edm/dict-beneficiary-contractor/update?emptyLayout=1';

                    if (object.objectId) {
                        accountId = object.objectId;
                    } else {
                        accountId = object.id;
                    }

                    $('#edmTemplatePOModal .update-payer-src-template').attr('src', beneficiaryUpdateSrc + "&id=" + accountId);
                    $('#edmTemplatePOModal .update-payer-button').show();

                }
            });

        }
    });
    //
}

// Ввод в поле суммы только числовых значений
$('#edmTemplatePOModal #paymentordertype-sum').on('keypress', function(e) {
    // спец. сочетания - не обрабатываем
    if (e.ctrlKey || e.altKey || e.metaKey) {
        return true;
    }

    var reg = new RegExp('^[0-9.]', 'g');
    var char = String.fromCharCode(e.keyCode || e.charCode);

    if (!char.match(reg)) {
        e.preventDefault();
    }
});

$('#edmTemplatePOModal #paymentordertype-number').on('keypress', function(e) {
    // спец. сочетания - не обрабатываем
    if (e.ctrlKey || e.altKey || e.metaKey) {
        return true;
    }

    var reg = new RegExp('^[0-9]', 'g');
    var char = String.fromCharCode(e.keyCode || e.charCode);

    if (!char.match(reg)) {
        e.preventDefault();
    }
});

// Отправка формы
$('#edmTemplatePOModal .btn-submit-template').on('click', function (e) {
    e.preventDefault();
    $('#edmTemplatePOModal #edm-template-wizard').trigger('submit');
});

// Отправка формы и создание нового документа
$('#edmTemplatePOModal .btn-submit-create-template').on('click', function(e) {
    e.preventDefault();
    $('#edmTemplatePOModal .hidden-create-document').val(1);
    $('#edmTemplatePOModal #edm-template-wizard').trigger('submit');
});

$('body #edm-template-wizard').on('submit', function (e) {
    e.preventDefault();

    var template_name = $('#paymentregisterpaymentordertemplate-name').val();

    if (template_name.length == 0) {
        $('.field-paymentregisterpaymentordertemplate-name').addClass('require');
        $('.field-paymentregisterpaymentordertemplate-name').addClass('has-error');
        $('.field-paymentregisterpaymentordertemplate-name .help-block').html("Необходимо заполнить \"Название Шаблона\"");
    }

    $.ajax({
        type: "post",
        url: '/edm/payment-order-templates/payment-order-save-template',
        data: $('#edm-template-wizard').serialize(),
        success: function(data) {}
    });
});

$('body #paymentregisterpaymentordertemplate-name').on('keypress', function() {
    $('.field-paymentregisterpaymentordertemplate-name').removeClass('require');
    $('.field-paymentregisterpaymentordertemplate-name').removeClass('has-error');
    $('.field-paymentregisterpaymentordertemplate-name .help-block').html("");
});

$('.close').on('click', function(e) {
    if ($('#contractorModalTemplate.in').length > 0) {
        e.preventDefault();
        $('#contractorModalTemplate').modal('hide');
        return false;
    } else if ($('#contractorModalUpdateTemplate.in').length > 0) {
        e.preventDefault();
        $('#contractorModalUpdateTemplate').modal('hide');
        return false;
    }
});

$('body #paymentregisterpaymentordertemplate-name').focusout(function() {
    var template_name = $(this).val();

    if (template_name.length == 0) {
        $('.field-paymentregisterpaymentordertemplate-name').addClass('require');
        $('.field-paymentregisterpaymentordertemplate-name').addClass('has-error');
        $('.field-paymentregisterpaymentordertemplate-name .help-block').html("Необходимо заполнить \"Название Шаблона\"");
    }
});

$('#edmTemplatePOModal').on('hidden.bs.modal', function(e) {
    if ($('#edmTemplatePOModal.in').length === 0) {
        $('.modal-backdrop').remove();
    }
});

$('.btn-beneficiary-submit').on('click', function(e) {
    e.preventDefault();
    var form = $("#contractorIframeTemplate").contents().find("form#w0");
    form.submit();
});

$('.btn-beneficiary-update-submit').on('click', function(e) {
    e.preventDefault();
    var form = $("#contractorIframe").contents().find("form#w0");
    form.submit();
});

JS;

$this->registerJs($script, View::POS_READY);

?>