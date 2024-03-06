<?php

use common\models\listitem\AttachedFile;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\jui\DatePicker;
use kartik\select2\Select2;
use addons\edm\models\DictCurrency;
use addons\edm\helpers\EdmHelper;
use yii\web\JsExpression;
use common\helpers\Html;

$codes = EdmHelper::getCdiCodes();
$types = EdmHelper::getCdiTypes();

$currencies = DictCurrency::getValues();

$currenciesList = [];

foreach($currencies as $currency) {
    $currenciesList[$currency->id] = $currency->name . ' - ' . $currency->description;
}

$form = ActiveForm::begin(['id' => 'document-form']);
?>
<div class="row document-data-margin-bottom">
    <?php DatePicker::widget([
        'id'            => 'date',
        'dateFormat' => 'dd.MM.yyyy',
    ]) ?>
    <div class="col-md-4">
        <?= $form->field($model, 'date')->textInput(['id' => 'date']) ?>
        <?php MaskedInput::widget([
            'id'            => 'date',
            'name'          => 'date',
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'number')->textInput() ?>
    </div>
    <div class="col-md-4" style="margin-top: 30px;">
        <?= $form->field($model, 'notRequiredNumber')->checkbox() ?>
    </div>
</div>

<div class="row document-data-margin-bottom">
    <div class="col-md-4">
        <?= $form
            ->field($model, 'originalDocumentNumber')
            ->textInput()
            ->label($model->getAttributeLabel('originalDocumentNumber'), ['style' => 'white-space: nowrap;'])
        ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($model, 'originalDocumentDate')
            ->textInput(['id' => 'originalDocumentDate'])
            ->label(Yii::t('edm', 'From'))
        ?>
        <?php MaskedInput::widget([
            'id'            => 'originalDocumentDate',
            'name'          => 'originalDocumentDate',
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
        <?php DatePicker::widget([
            'id'         => 'originalDocumentDate',
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
    </div>
</div>

<div class="row document-data-margin-bottom">
    <div class="col-md-12">
        <?= $form->field($model, 'code')->widget(Select2::class, [
            'data' => $codes,
            'options' => ['placeholder' => 'Выберите код'],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'pluginEvents' => [
                'change' => 'function() { changeCode() }',
            ]
        ])
        ?>
    </div>
</div>

<div class="row document-data-margin-bottom">
    <div class="col-md-4">
        <?= $form->field($model, 'sumDocument')->widget(MaskedInput::class, [
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
    <div class="col-md-8">
        <?= $form->field($model, 'currencyIdDocument')->widget(Select2::classname(), [
            'data' => $currenciesList,
            'options' => ['placeholder' => 'Выберите валюту'],
            'pluginOptions' => [
                'allowClear' => true
            ]])
        ?>
    </div>
</div>

<div class="row document-data-margin-bottom">
    <div class="col-md-4">
        <?= $form->field($model, 'sumContract')->widget(MaskedInput::class, [
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
    <div class="col-md-8">
        <?= $form->field($model, 'currencyIdContract')->widget(Select2::classname(), [
            'data' => $currenciesList,
            'options' => ['placeholder' => 'Выберите валюту'],
            'pluginOptions' => [
                'allowClear' => true
            ]])
        ?>
    </div>
</div>

<div class="row document-data-margin-bottom">
    <div class="col-md-12">
        <?= $form->field($model, 'type')->widget(Select2::class, [
            'data' => $types,
            'options' => ['placeholder' => 'Выберите тип'],
            'pluginOptions' => [
                'allowClear' => true
            ]])
        ?>
    </div>
</div>

<div class="row document-data-margin-bottom">
    <div class="col-md-4">
        <?= $form->field($model, 'expectedDate')->textInput(['id' => 'expectedDate']) ?>
        <?php MaskedInput::widget([
            'id'            => 'expectedDate',
            'name'          => 'expectedDate',
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
        <?php DatePicker::widget([
            'id' => 'expectedDate',
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
    </div>
    <div class="col-md-8">
        <?= $form->field($model, 'countryCode')->widget(Select2::class, [
            'data' => EdmHelper::countryCodesList(),
            'options' => ['placeholder' => 'Выберите код страны'],
            'pluginOptions' => [
                'allowClear' => true,
                'templateSelection' => new JsExpression('function(item) {
                    return item.id ? item.id : "Выберите код страны";
              }')
            ]
        ])
        ?>
    </div>
</div>

<div class="row operation-data-margin-bottom">
    <div class="col-md-12">
        <?= $form->field($model, 'comment')
            ->textarea([
                'maxlength' => true,
                'class' => 'form-control', 'rows' => '5'
            ])
        ?>
    </div>
</div>

<?php

if ($uuid) {
    echo Html::hiddenInput('uuid', $uuid);
}

$model->attachedFilesJson = common\models\listitem\NestedListItem::listToJson($model->attachedFiles);
echo $form->field($model, 'attachedFilesJson', ['template' => '{input}'])->hiddenInput();
ActiveForm::end();
?>

<h4><?= Yii::t('edm', 'Attached files') ?></h4>
<?php
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


<?php

$this->registerCss(<<<CSS

.operation-data-margin-bottom {
    margin-bottom: 10px;
}

.comment-textarea {
    width: 500px;
    resize: none;
}

#document-form input[type=text]:disabled {
    background-color: #9AA6AB;
}
CSS
);

$script = <<< JS
    // Создание операции
    $('body').on('click', '.btn-submit-form', function(e) {
        var formData = $('#document-form').serialize();

        $.ajax({
            url: '/edm/confirming-document-information/process-document-form',
            data: formData,
            type: 'post',
            success: function(result) {
                // Если успешно, закрываем форму и отображаем результат
                if (result.status === 'ok') {
                    $('#document-modal').modal('hide');

                    // Отображение таблицы операций
                    $('.documents').html(result.content);

                    // Кэширование данных документа
                    form = $('#document-form').serialize();
                    $.post('/wizard-cache/cdi', form);
                } else if (result.status === 'error') {
                    $('body').off('click', '.btn-submit-form');

                    // Иначе перерисовка формы
                    $('#document-modal .modal-body').html(result.content);
                }
            }
        });
     });

     function checkNumber(isChanged) {
        var checkbox = $('#confirmingdocumentinformationitem-notrequirednumber');
        var numberInput = $('#confirmingdocumentinformationitem-number');
        var isChecked = checkbox.is(':checked');
        numberInput.attr('readonly', isChecked);
        numberInput.attr('disabled', isChecked);
        
        if (isChecked) {
            numberInput.val('БН');
        } else if (isChanged) {
            numberInput.val('');
            numberInput.trigger('focus');
        }
    }

    // Обработка события изменения поля выбора кода
    function changeCode() {
        checkCountryCodeField();
        checkType();
    }

    // Проверка доступности поля выбора кода страны
    function checkCountryCodeField() {
        var code = $('#confirmingdocumentinformationitem-code').val();

        var codes = ['02_3', '02_4'];

        // если значение 02_3 или 02_4, поле кода страны доступно, иначе нет
        if (codes.includes(code)) {
           $('#confirmingdocumentinformationitem-countrycode').attr('disabled', false);
        } else {
           $('#confirmingdocumentinformationitem-countrycode').val('').trigger('change.select2');
           $('#confirmingdocumentinformationitem-countrycode').attr('disabled', true);
        }
    }

    // Проверка доступности выбора поля признака поставки
    function checkType() {
        var code = $('#confirmingdocumentinformationitem-code').val();

        var codes = ['01_3', '01_4', '02_3', '02_4', '03_3', '03_4', '04_3', '04_4', '15_3', '15_4'];

        if (codes.includes(code)) {
            $('#confirmingdocumentinformationitem-type').attr('disabled', false);
        } else {
            $('#confirmingdocumentinformationitem-type').val('').trigger('change.select2');
            $('#confirmingdocumentinformationitem-type').attr('disabled', true);
        }
    }

    // Управление доступностью полей
    $('#confirmingdocumentinformationitem-notrequirednumber').on('change', function() {
        checkNumber(true);
    });

    checkNumber();
    checkCountryCodeField();
    checkType();
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>
