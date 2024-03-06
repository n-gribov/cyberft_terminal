<?php

use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictCurrency;
use common\helpers\Html;
use common\models\listitem\AttachedFile;
use kartik\select2\Select2;
use yii\jui\DatePicker;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$currencies = DictCurrency::getValues();

$currenciesList = [];
foreach($currencies as $currency) {
    $currenciesList[$currency->id] = $currency->name . ' - ' . $currency->description;
}

$paymentTypeList = [
    '1' => '1 - Зачисление',
    '2' => '2 - Списание'
];

$form = ActiveForm::begin([
    'id' => 'operation-form',
    'method' => 'POST',
    'action' => 'process-operation-form',
]);
?>
<div class="operation-block">
    <div class="row operation-data-margin-bottom">
        <div class="col-md-3">
            <?= $form->field($model, 'docDate')->textInput(['id' => 'docDate', 'style' => 'text-align:right']) ?>
            <?php MaskedInput::widget([
                'id'            => 'docDate',
                'name'          => 'docDate',
                'mask'          => '99.99.9999',
                'clientOptions' => [
                    'placeholder' => 'dd.MM.yyyy',
                ]
            ])?>
            <?php DatePicker::widget([
                'id'         => 'docDate',
                'dateFormat' => 'dd.MM.yyyy',
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'number')->textInput(['style' => 'text-align:right']) ?>
        </div>
        <div class="col-md-3" style="margin-top:2em">
            <?= $form->field($model, 'notRequiredSection1')->checkbox()?>
        </div>
    </div>
    <div class="row operation-data-margin-bottom">
        <div class="col-md-6">
            <?= $form->field($model, 'paymentType')->dropDownList($paymentTypeList) ?>
        </div>
    </div>
    <div class="row operation-data-margin-bottom">
        <div class="col-md-12">
            <?= $form->field($model, 'codeFCO')->widget(Select2::classname(), [
                    'data' => EdmHelper::fcoCodesList(),
                    'options' => ['placeholder' => 'Выберите код ВО', 'prompt' => ''],
                    'pluginOptions' => ['allowClear' => true]
                ])
            ?>
        </div>
    </div>
    <div class="row operation-data-margin-bottom">
        <div class="col-md-3">
            <?= $form->field($model, 'operationDate')->textInput(['id' => 'operationDate', 'style' => 'text-align:right']) ?>
            <?php MaskedInput::widget([
                'id'            => 'operationDate',
                'name'          => 'operationDate',
                'mask'          => '99.99.9999',
                'clientOptions' => [
                    'placeholder' => 'dd.MM.yyyy',
                ]
            ])?>
            <?php DatePicker::widget([
                'id'         => 'operationDate',
                'dateFormat' => 'dd.MM.yyyy',
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'operationSum')->widget(MaskedInput::className(), [
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
        <div class="col-md-5">
            <?= $form->field($model, 'currencyId')->widget(Select2::classname(), [
                    'data' => $currenciesList,
                    'options' => ['placeholder' => 'Выберите валюту', 'prompt' => ''],
                    'pluginOptions' => ['allowClear' => true]
                ])
            ?>
        </div>
    </div>
    <hr>
    <div class="row operation-data-margin-bottom">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-1">
                    <?= Html::radio('fci-document', true, ['value' => 'passport', 'id' => 'fci-document-passport'])?>
                </div>
                <div class="col-md-11">
                    <?= $form->field($model, 'contractPassport')->widget(
                    MaskedInput::class, [
                        'mask' => '99999999/9999/9999/9/9',
//                        'clientOptions' => [
//                            'autoUnmask' => true,
//                        ]
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    <div class="row operation-data-margin-bottom">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-1">
                    <?=Html::radio('fci-document', false, ['value' => 'contract', 'id' => 'fci-document-contract'])?>
                </div>
                <div class="col-md-11">
                    <?= $form->field($model, 'contractNumber')->textInput() ?>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="margin-top:2em">
            <?= $form->field($model, 'notRequiredSection2')->checkbox()?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'contractDate')->textInput(['style' => 'text-align:right'])
            //->label('<br/>' . $model->getAttributeLabel('contractDate')) ?>
            <?php MaskedInput::widget([
                'id'            => 'foreigncurrencyoperationinformationitem-contractdate',
                'name'          => 'contractDate',
                'mask'          => '99.99.9999',
                'clientOptions' => [
                    'placeholder' => 'dd.MM.yyyy',
                ]
            ])?>
            <?php DatePicker::widget([
                'id'         => 'foreigncurrencyoperationinformationitem-contractdate',
                'dateFormat' => 'dd.MM.yyyy',
            ]) ?>
        </div>
    </div>
    <hr>
    <div class="row operation-data-margin-bottom">
        <div class="col-md-6">
            <?= $form->field($model, 'operationSumUnits')->widget(MaskedInput::className(), [
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
        <div class="col-md-6">
            <?= $form->field($model, 'currencyUnitsId')->widget(Select2::classname(), [
                'data' => $currenciesList,
                'options' => ['placeholder' => 'Выберите валюту'],
                'pluginOptions' => ['allowClear' => true]])
            ?>
        </div>
    </div>
    <div class="row operation-data-margin-bottom">
        <div class="col-md-6">

                <?= $form->field($model, 'refundDate')->textInput(['id' => 'refundDate', 'style' => 'text-align:right']) ?>
                <?php MaskedInput::widget([
                    'id'            => 'foreigncurrencyoperationinformationitem-refundDate',
                    'name'          => 'refundDate',
                    'mask'          => '99.99.9999',
                    'clientOptions' => [
                        'placeholder' => 'dd.MM.yyyy',
                    ]
                ])?>
                <?php DatePicker::widget([
                    'id'         => 'refundDate',
                    'dateFormat' => 'dd.MM.yyyy',
                ]) ?>

        </div>
        <div class="col-md-6">
                <?= $form->field($model, 'expectedDate')->textInput(['id' => 'expectedDate', 'style' => 'text-align:right']) ?>
                <?php MaskedInput::widget([
                    'id'            => 'expectedDate',
                    'name'          => 'operationDate',
                    'mask'          => '99.99.9999',
                    'clientOptions' => [
                        'placeholder' => 'dd.MM.yyyy',
                    ]
                ])?>
                <?php DatePicker::widget([
                    'id'         => 'expectedDate',
                    'dateFormat' => 'dd.MM.yyyy',
                ]) ?>

        </div>
    </div>
    <div class="row operation-data-margin-bottom">
        <div class="col-md-12">
            <?php
                $data = [
                //    '' => '',
                    1 => '1 - документы не представлены в соответствии с пунктами 2.7 и 2.15 Инструкции 181-И',
                    2 => '2 - документы не представлены в соответствии с пунктами 2.6, 2.8, 2.14 и 2.16 Инструкции 181-И, a также в случае зачисления валюты РФ по договору, не требующего постановки на учет',
                    3 => '3 - документы не представлены в соответствии с пунктом 2.2 Инструкции 181-И',
                    4 => '4 - документы представлены'
                ];
            ?>
            <?= $form->field($model, 'docRepresentation')->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => 'Выберите', 'prompt' => ''],
                'pluginOptions' => ['allowClear' => true]
            ])
            ?>

        </div>
    </div>
    <hr>
    <div class="row operation-data-margin-bottom">
        <div class="col-md-12">
            <?= $form->field($model, 'comment')
                ->textarea([
                    'maxlength' => true,
                    'rows' => '5',
                    'class' => 'form-control',
                    'style' => 'width:100%',
                ])
            ?>
        </div>
    </div>
</div>
<?php

if ($uuid != '') {
    echo Html::hiddenInput('uuid', $uuid);
}

$model->attachedFilesJson = common\models\listitem\NestedListItem::listToJson($model->attachedFiles);
echo $form->field($model, 'attachedFilesJson', ['template' => '{input}'])->hiddenInput();
ActiveForm::end();
?>
<h4><?= Yii::t('edm', 'Attached files') ?></h4>
<?php
    // Вывести блок вложенных файлов
    echo $this->render('@common/views/document/_attachedFiles', [
        'models' => $model->attachedFiles,
        'modelClass' => AttachedFile::class
    ]);
    echo Html::button(
        Yii::t('app', 'Add'),
        [
            'id' => 'add-attached-file-button',
            'class' => 'btn btn-primary',
            'data' => ['loading-text' => '<i class=\'fa fa-spinner fa-spin\'></i> ' . Yii::t('app', 'Add')]
        ]
    );
?>
<form action="upload-attached-file" id="upload-attached-file-form" enctype="multipart/form-data">
    <input type="file" name="file" class="hidden" />
</form>
</div>
<?php
$this->registerCss(<<<CSS
    .operation-data-margin-bottom {
        margin-bottom: 10px;
    }

    .comment-textarea {
        width: 500px;
        resize: none;
    }

    #operation-form input[type=text]:disabled {
        background-color: #9AA6AB;
    }
CSS);

$script = <<<JS
    function checkSection1() {
        var disabledStatus = '';

        if ($('#foreigncurrencyoperationinformationitem-notrequiredsection1').prop('checked')) {
            disabledStatus = true;
            $('#foreigncurrencyoperationinformationitem-number').val('БН');
        } else {
            disabledStatus = false;
        }

        $('#foreigncurrencyoperationinformationitem-number').attr('disabled', disabledStatus);
    }

    function checkSection2() {

        var disabledStatus = true;

        var contractPassport = $('#foreigncurrencyoperationinformationitem-contractpassport');
        var contractNumber = $('#foreigncurrencyoperationinformationitem-contractnumber');

        if ($('#foreigncurrencyoperationinformationitem-notrequiredsection2').prop('checked')) {
            disabledStatus = true;
            contractNumber.val('БН');
        } else {
            disabledStatus = false;
        }

        contractNumber.attr('disabled', disabledStatus);
        contractPassport.attr('disabled', disabledStatus);

        $('#fci-document-passport').attr('disabled', disabledStatus);
        $('#fci-document-contract').attr('disabled', disabledStatus);
    }

    function checkDocumentType() {

        var value = '';

        var contactPassport = $('#foreigncurrencyoperationinformationitem-contractpassport');
        var contractNumber = $('#foreigncurrencyoperationinformationitem-contractnumber');
        var contractDate = $('#foreigncurrencyoperationinformationitem-contractdate');

        if (contactPassport.val().length > 0) {
            value = 'passport';
            $('input[name=fci-document]').filter('[value=passport]').prop('checked', true);
        } else if (contractNumber.val().length > 0 || contractDate.val().length > 0) {
            value = 'contract';
            $('input[name=fci-document]').filter('[value=contract]').prop('checked', true);
        } else {
            value = $('input[name=fci-document]:checked').val();
        }

        if (value === 'passport') {
            contactPassport.attr('disabled', false);
            contractNumber.attr('disabled', true);
            contractDate.attr('disabled', true);
        } else if (value === 'contract') {
            contactPassport.attr('disabled', true);
            if ($('#foreigncurrencyoperationinformationitem-notrequiredsection2').prop('checked')) {
                contractNumber.attr('disabled', true);
            } else {
                contractNumber.attr('disabled', false);
            }
            contractDate.attr('disabled', false);
        }
    }

    // Управление доступностью полей
    $('#foreigncurrencyoperationinformationitem-notrequiredsection1').on('change', function() {
        $('#foreigncurrencyoperationinformationitem-number').val('');
        checkSection1();
    });

    $('#foreigncurrencyoperationinformationitem-notrequiredsection2').on('change', function() {
        $('#foreigncurrencyoperationinformationitem-contractpassport').val('');
        $('#foreigncurrencyoperationinformationitem-contractnumber').val('');
        //$('#foreigncurrencyoperationinformationitem-contractdate').val('');
        checkSection2();

        if ($(this).prop('checked') === false) {
            checkDocumentType();
        }
    });

    // Обработка выбора типа документа
    $('input[name=fci-document]').on('change', function() {
        $('#foreigncurrencyoperationinformationitem-contractpassport').val('');
        $('#foreigncurrencyoperationinformationitem-contractnumber').val('');
        $('#foreigncurrencyoperationinformationitem-contractdate').val('');
        checkDocumentType();
    });

    checkSection1();
    checkSection2();
    checkDocumentType();
JS;

$this->registerJs($script, View::POS_READY);
