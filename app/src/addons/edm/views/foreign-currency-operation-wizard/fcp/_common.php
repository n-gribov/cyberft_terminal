<?php

use kartik\widgets\Select2;
use common\helpers\Html;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyPaymentType;
use yii\widgets\MaskedInput;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\StringHelper;

$modelClass = strtolower(StringHelper::basename(get_class($model)));
?>

<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'sum')
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
    <div class="col-md-2">
        <?=$form->field($model, 'currency')
            ->dropDownList(common\helpers\Currencies::getCodeLabels(),
                ['class' => 'form-control foreigncurrencypayment-currency']
            )->label(false)?>
    </div>
</div>

<hr class="fcp-hr">

<div class="row">
    <div class="col-md-8">
        <h4>Плательщик / Ordering customer</h4>
        <?php
        echo $form->field($model, 'payerAccountSelect')->widget(Select2::className(), [
            'options'       => ['placeholder' => 'Поиск плательщика по имени или счету ...', 'class' => 'has-success fcp-payer-account-select'],
            'theme' => Select2::THEME_BOOTSTRAP,
            'pluginOptions' => [
                'allowClear'         => true,
                'minimumInputLength' => 0,
                'ajax'               => [
                    'url'      => Url::to(['/edm/edm-payer-account/list?exceptCurrency=1']),
                    'dataType' => 'json',
                    'delay'    => 250,
                    'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
                ],
                'templateResult' => new JsExpression('function(item) {
                        if (!item.number) return item.text; return item.name + ", " + item.number;// + ", " + item.currencyInfo.name;
                    }'),
                'templateSelection' => new JsExpression('function(item) {
                        if (!item.number) return item.text; return item.name + ", " + item.number;// + ", " + item.currencyInfo.name;
                    }'),
            ],
            'pluginEvents' => [
                'select2:select' => 'function(e) { fcp_applyPayer(e.params.data, "' . $modelClass . '"); }',
                'select2:unselect' => 'function(e) { fcp_resetPayer("' . $modelClass . '"); }'
            ],
        ])->label(false);
        ?>
    </div>
</div>

<div class="fcp-payer-info">
    <div class="row fcp-padding-left-20">
        <div class="col-md-12">
            <div class="form-inline fcp-payer-block">
                <div>
                    <?=$form->field($model, 'payerAccount')->textInput(['readonly' => true])->label('Счет')?>
                </div>
                <div>
                    <?=$form->field($model, 'payerInn')->textInput(['readonly' => true])->label('ИНН')?>
                </div>
                <div>
                    <?=$form->field($model, 'payerName')->textInput(['readonly' => true])->label('Имя')?>
                </div>
                <div>
                    <?=$form->field($model, 'payerAddress')->textInput(['readonly' => true])->label('Адрес')?>
                </div>
                <div>
                    <?=$form->field($model, 'payerLocation')->textInput(['readonly' => true])->label('Город, страна')?>
                </div>
            </div>
        </div>
    </div>

    <hr class="fcp-hr">

    <div class="row">
        <div class="col-md-12">
            <h4>Банк плательщика / Ordering institution</h4>
            <div class="form-inline fcp-payer-bank-block fcp-padding-left-20">
                <div>
                    <?=$form->field($model, 'payerBank')->textInput(['readonly' => true])->label('SWIFT BIC')?>
                </div>
                <div>
                    <?=$form->field($model, 'payerBankName')->textInput(['readonly' => true])->label('Наименование')?>
                </div>
                <div>
                    <?=$form->field($model, 'payerBankAddress')->textInput(['readonly' => true])->label('Адрес')?>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="fcp-hr">

<div class="row">
    <div class="col-md-12">
        <h4 class="fcp-intermediary-bank-title">Банк посредник / Intermediary institution</h4>
        <div class="form-inline fcp-intermediary-bank-block fcp-padding-left-20">

            <div class="fcp-intermediary-bank-radio-block">
                <?php
                if ($model->intermediaryBankNameAndAddress) {
                    $intermediaryBankCheckedVal = 'manual';
                } else {
                    $intermediaryBankCheckedVal = 'swiftbic';
                }
                ?>
                <?= Html::radioList('intermediary-bank-radio', $intermediaryBankCheckedVal, [
                    'swiftbic' => 'Ввести SWIFT BIC',
                    'manual' => ' Ввести наименование и адрес'
                ], ['class' => 'intermediary-bank-radio']) ?>
            </div>

            <div class="fcp-intermediary-bank-radio-swift">
                <?php
                echo $form->field($model, 'intermediaryBank')->widget(Select2::classname(), [
                    'options'       => ['placeholder' => 'Поиск банка плательщика ...', 'class' => 'has-success'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginOptions' => [
                        'allowClear'         => true,
                        'minimumInputLength' => 0,
                        'ajax'               => [
                            'url'      => Url::to(['/edm/foreign-currency-operation-wizard/get-swift-banks-list']),
                            'dataType' => 'json',
                            'delay'    => 250,
                            'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
                        ],
                        'templateResult' => new JsExpression('function(item) {
                            if (!item.swiftCode) return item.text;
                            return item.swiftCode + item.branchCode + " " + item.name;
                          }'),
                        'templateSelection'=> new JsExpression('function(item) {
                            if (!item.swiftCode) return item.text;
                            return item.swiftCode + item.branchCode;
                          }'),
                        'width' => '50%',
                    ],
                    'pluginEvents'  => [
                        'select2:select' => 'function(e) { fcp_applyIntermediaryBank(e.params.data, "' . $modelClass . '"); }',
                        'select2:unselect' => 'function(e) { fcp_resetIntermediaryBank("' . $modelClass . '"); }'
                    ],

                ])->label('BIC');
                ?>
                <div>
                    <span class="bank-info"></span>
                </div>
            </div>

            <div class="fcp-intermediary-bank-radio-manual">
                <?=$form->field($model, 'intermediaryBankNameAndAddress', ['options' => ['style' => 'width: 100%']])->textarea([
                    'rows' => 4,
                    'class' => 'form-control mtmultiline validate-mask',
                    'data' => ['limit' => '4,35'],
                ])->label('Наименование<br/>Адрес', ['style' => 'vertical-align: top; width: 127px;'])?>
            </div>
            <div>
                <?=$form->field($model, 'intermediaryBankAccount')->textInput(
                    [
                        'maxlength' => 34,
                        'class' => 'form-control validate-mask',
                        'data' => ['allowed-chars' => '[\dA-Za-z\/\-\?\:\(\)\.\,\'\+\{\}]']
                    ]
                )->label('Номер счета')?>
            </div>
        </div>
    </div>
</div>

<hr class="fcp-hr">

<div class="row">
    <div class="col-md-12">
        <h4>Получатель / Beneficiary customer</h4>
    </div>
    <div class="col-md-6">
        <?php
        echo $form->field($model, 'beneficiaryAccountSelect')->widget(Select2::className(), [
            'name' => 'beneficiary-account-select',
            'options'       => ['placeholder' => 'Поиск получателя ...', 'class' => 'has-success fcp-beneficiary-account-select'],
            'theme' => Select2::THEME_BOOTSTRAP,
            'pluginOptions' => [
                'allowClear'         => true,
                'minimumInputLength' => 0,
                'ajax'               => [
                    'url'      => Url::to(['/edm/dict-foreign-currency-payment-beneficiary/list']),
                    'dataType' => 'json',
                    'delay'    => 250,
                    'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
                ],
                'templateResult' => new JsExpression('function(item) {
                    if (item.name) {
                        return item.name + ", " + item.account;
                    }
                  }'),
                'templateSelection'=> new JsExpression('function(item) {
                    if (item.name) {
                        return item.name + ", " + item.account;
                    }
                  }'),
            ],
            'pluginEvents'  => [
                'select2:select' => 'function(e) { fcp_applyBeneficiaryAccount(e.params.data, "' . $modelClass . '"); }',
                'select2:unselect' => 'function(e) { fcp_resetBeneficiaryAccount("' . $modelClass . '"); }'
            ],
        ])->label(false);
        ?>
    </div>
</div>

<div class="row fcp-padding-left-20">
    <div class="col-md-12">
        <div class="form-inline fcp-beneficiary-block">
            <div>
                <?=$form->field($model, 'beneficiaryAccount')->textInput(
                    ['maxlength' => true, 'class' => 'form-control validate-mask']
                )->label('Счет')?>
            </div>
            <div>
                <?=$form->field($model, 'beneficiary')->textarea(
                    [
                        'rows' => 4,
                        'class' => 'form-control mtmultiline validate-mask',
                        'data' => ['limit' => '4,35']
                    ]
                )->label('Название<br/>Адрес', ['style' => 'vertical-align: top;'])?>
            </div>
        </div>
    </div>
</div>

<hr class="fcp-hr">

<div class="row">
    <div class="col-md-12">
        <h4>Банк получателя / Beneficiary institution</h4>
        <div class="form-inline fcp-beneficiary-bank-block fcp-padding-left-20">

            <div class="fcp-beneficiary-bank-radio-block">
                <?php
                if ($model->beneficiaryBankNameAndAddress) {
                    $beneficiaryBankCheckedVal = 'manual';
                } else {
                    $beneficiaryBankCheckedVal = 'swiftbic';
                }
                ?>

                <?=Html::radioList('beneficiary-bank-radio', $beneficiaryBankCheckedVal, [
                    'swiftbic' => 'Ввести SWIFT BIC',
                    'manual' => ' Ввести наименование и адрес'
                ], ['class' => 'beneficiary-bank-radio'])?>
            </div>

            <div class="beneficiary-bank-radio-swift">
                <?php
                echo $form->field($model, 'beneficiaryBank')->widget(Select2::classname(), [
                    'options'       => ['placeholder' => 'Поиск банка получателя ...', 'class' => 'has-success'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginOptions' => [
                        'allowClear'         => true,
                        'minimumInputLength' => 0,
                        'ajax'               => [
                            'url'      => Url::to(['/edm/foreign-currency-operation-wizard/get-swift-banks-list']),
                            'dataType' => 'json',
                            'delay'    => 250,
                            'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
                        ],
                        'templateResult' => new JsExpression('function(item) {
                                if (!item.swiftCode) return item.text;
                                return item.swiftCode + item.branchCode + " " + item.name;
                             }'),
                        'templateSelection'=> new JsExpression('function(item) {
                               if (!item.swiftCode) return item.text;
                               return item.swiftCode + item.branchCode;
                             }'),
                        'width' => '50%',
                    ],
                    'pluginEvents'  => [
                        'select2:select' => 'function(e) { fcp_applyBeneficiaryBank(e.params.data, "' . $modelClass . '"); }',
                        'select2:unselect' => 'function(e) { fcp_resetBeneficiaryBank("' . $modelClass . '"); }'
                    ],
                ])->label('BIC');
                ?>
                <div>
                    <span class="bank-info"></span>
                </div>
            </div>

            <div class="beneficiary-bank-radio-manual">
                <?=$form->field($model, 'beneficiaryBankNameAndAddress', ['options' => ['style' => 'width: 100%']])->textarea([
                    'rows' => 4,
                    'class' => 'form-control mtmultiline validate-mask',
                    'data' => ['limit' => '4,35'],
                ])->label('Наименование<br/>Адрес', ['style' => 'vertical-align: top; width: 127px;'])?>
            </div>

            <div>
                <?=$form->field($model, 'beneficiaryBankAccount')->textInput(
                    [
                        'maxlength' => 34,
                        'class' => 'form-control validate-mask',
                        'data' => ['allowed-chars' => '[\dA-Za-z\/\-\?\:\(\)\.\,\'\+\{\}]']
                    ]
                )->label('Номер счета')?>
            </div>
        </div>
    </div>
</div>

<hr class="fcp-hr">

<div class="row fcp-margin-top-10">
    <div class="col-md-12">
        <?=Html::label('Информация / Remittance information')?>
        <div class="fcp-padding-left-20">
            <?=$form->field($model, 'information')->textarea([
                'class' => 'form-control mtmultiline validate-mask',
                'rows' => '4',
                'data' => ['limit' => '4,35'],
            ])->label(false)?>
        </div>
    </div>
</div>

<hr class="fcp-hr">

<div class="row fcp-margin-top-10">
    <div class="col-md-12">
        <?=Html::label('Комиссия / Commission')?>
        <div class="fcp-padding-left-20">
            <?=$form->field($model, 'commission')->dropDownList(ForeignCurrencyPaymentType::commissionLabels())->label(false)?>
        </div>
    </div>
<?php /* CYB-4195 */ if (false)  : ?>
        <div class="col-md-12 commission-info-block">
        <?=Html::label('Расходы отправителя / Customer expenses')?>
        <div class="fcp-padding-left-20 row">
            <div class="col-md-3">
                <?= $form->field($model, 'commissionSum')
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
                    ])->label(false)?>
            </div>
            <div class="col-md-2">
                <?=$form->field($model, 'currency')->textInput(
                    [
                        'id' => 'foreigncurrencypaymenttype-currency-commission',
                        'class' => 'form-control foreigncurrencypayment-currency-commission',
                        'readonly' => true
                    ]
                )->label(false)?>
            </div>
        </div>
        </div>
<?php /* CYB-4195 */ endif ?>
</div>

<hr class="fcp-hr">

<div class="row fcp-margin-top-10">
    <div class="col-md-12">
        <?=Html::label('Дополнительная информация / Additional information')?>
        <div class="fcp-padding-left-20">
            <?=$form->field($model, 'additionalInformation')->textarea([
                'class' => 'form-control mtmultiline validate-mask',
                'rows' => '4',
                'data' => ['limit' => '6,35'],
            ])->label(false)?>
        </div>
    </div>
</div>