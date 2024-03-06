<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use addons\edm\models\DictContractor;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictBeneficiaryContractor;

/* @var $this yii\web\View */
/* @var $model DictContractor */
/* @var $form yii\widgets\ActiveForm */
if ($model->bankBik) {
    $bank = \addons\edm\models\DictBank::findOne(['bik' => $model->bankBik]);
}

// Получение списка валют для передачи в форму выбора
$currencies = ArrayHelper::map(DictCurrency::find()->all(), 'id', 'name');

// Если у модели НЕ заполнен terminalId,
// значит считаем, что мы создаем новый элемент
// В этом случае проверяем соответствующий get-параметр

if (!$model->terminalId) {
    $get = Yii::$app->request->get();

    if (isset($get['terminalId'])) {
        $terminalId = $get['terminalId'];
        $model->terminalId = $terminalId;
    }
}
?>

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'bankBik')->widget(\kartik\select2\Select2::classname(), [
    'id'            => 'edmdictcontractor-bankBik',
    'initValueText' => isset($bank)
        ? 'БИК: ' . $bank->bik . ' Банк: ' . $bank->name
        : null,
    'options'       => [
        'placeholder' => Yii::t('app', 'Search for a {label}', ['label' => Yii::t('edm', 'bank by name or BIK')]),
        'style'       => 'width:100%',
    ],
    'pluginOptions' => [
        'allowClear'         => true,
        'minimumInputLength' => 0,
        'ajax'               => [
            'url'      => \yii\helpers\Url::to(['dict-bank/list']),
            'dataType' => 'json',
            'data'     => new JsExpression('function(params) { return {q:params.term}; }')
        ],
        'templateResult'     => new JsExpression(<<<JS
            function(item) {
                if (!item.bik) {
                    return item.text;
                }
                return 'БИК: ' + item.bik + ' Банк: ' + item.name;
            }
        JS),
        'templateSelection'  => new JsExpression(<<<JS
            function(item) {
                if (!item.bik) {
                    return item.text;
                }
                return 'БИК: ' + item.bik + ' Банк: ' + item.name;
            }
        JS),
    ],
    'pluginEvents'  => [
        'select2:select' => <<<JS
            function(e) {
                if ($('#dictbeneficiarycontractor-terminalid').val().length === 0) {
                   $('#dictbeneficiarycontractor-terminalid').val(e.params.data.terminalId);
                }
            }
        JS,
        'select2:unselect' => <<<JS
            function(e) {
                if ($('#dictbeneficiarycontractor-terminalid').val().length === 0) {
                    $('#dictbeneficiarycontractor-terminalid').val('');
                }
            }
        JS,
        'change' => 'function(e) { checkFieldAccessibility(); }',
    ],
]);?>

<div class="clearfix">
    <div class="col-md-11 col-xs-9 no-padding-left">
        <?= $form->field($model, 'account') ?>
        <?php MaskedInput::widget([
            'model' => $model,
            'attribute' => 'account',
            'mask'          => '99999.999.9.99999999999',
            'clientOptions' => [
                'placeholder' => '_____.___._.___________',
            ]
        ])?>
    </div>
    <div class="col-md-1 col-xs-3 no-padding-right">
        <?= $form->field($model, 'currencyId')->dropDownList($currencies, ['prompt' => '']) ?>
    </div>
</div>

<div class="clearfix">
    <div class="col-md-6 col-xs-6 no-padding-left">
        <?= $form->field($model, 'inn')->textInput(['maxlength' => '12']) ?>
        <?php
            // Определение маски в зависимости от типа получателя
            if ($model->type == DictBeneficiaryContractor::TYPE_ENTITY) {
                $maskedInnOptions = [
                    'mask' => '9999999999',
                    'placeholder' => '__________'
                ];
            } else {
                $maskedInnOptions = [
                    'mask' => '999999999999',
                    'placeholder' => '____________'
                ];
            }

            MaskedInput::widget([
                'model' => $model,
                'attribute' => 'inn',
                'mask'          => $maskedInnOptions['mask'],
                'clientOptions' => [
                    'placeholder' => $maskedInnOptions['placeholder']
                ]
            ]);
        ?>
    </div>
    <div class="col-md-6 col-xs-6 no-padding-right">
        <?= $form->field($model, 'kpp')->textInput(['maxlength' => 9]) ?>
        <?php MaskedInput::widget([
            'model' => $model,
            'attribute' => 'kpp',
            'mask'          => '999999999',
            'clientOptions' => [
                'placeholder' => '_________'
            ]
        ]); ?>
    </div>
</div>

<?php

// Вывод кнопок изменения только для вызова представления в всплывающих формах
$emptyLayout = Yii::$app->request->get('emptyLayout');

if (!$emptyLayout) { ?>
<div class="form-group">
    <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary'])?>
</div>
<?php } ?>

<?= $form->field($model, 'terminalId')->hiddenInput()->label(false) ?>

<div class="hidden">
    <?= $form->field($model, 'type')->dropDownList(DictContractor::typeValues()) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
// JS-скрипты для представления
$script = <<<JS
    var modelClassPrefix = 'dictbeneficiarycontractor';
    checkFieldAccessibility();

    // Проверка доступности полей
    function checkFieldAccessibility() {
        var bankValue = $('#' + modelClassPrefix +'-bankbik').val();

        if (bankValue.length === 0) {
            fields = ['currencyid', 'account', 'inn', 'kpp'];

            fields.forEach(function(element) {
                field = $('#' + modelClassPrefix + '-' + element);
                field.attr('disabled', true).val('');
                field.parent('div').removeClass('has-error');
            });

            return;
        }

        var accountValue = $('#' + modelClassPrefix +'-account').val().replace(/[_.]/g, '');

        $('#' + modelClassPrefix + '-account').attr('disabled', false);
        $('#' + modelClassPrefix + '-currencyid').attr('disabled', false);

        if (accountValue.length === 20) {
            disabledStatus = false;
        } else {
            disabledStatus = true;
            $('#' + modelClassPrefix + '-currencyid').val('');
        }

        $('#' + modelClassPrefix + '-inn').attr('disabled', disabledStatus);
        $('#' + modelClassPrefix + '-kpp').attr('disabled', disabledStatus);
    }

    // Событие выбора типа юридического лица из списка
    $('#dictbeneficiarycontractor-type').on('change', function() {

        // Очищаем текущее значение поля ИНН
        $('#dictbeneficiarycontractor-inn').val('');

        // Очищаем текущее значение поля КПП
        $('#dictbeneficiarycontractor-kpp').val('');

        // Проверка маски поля ИНН
        checkInnField();

        // Проверка доступности поля КПП
        checkKppReadonly();
    });

    function checkInnField() {
        // Меняем маску в зависимости от тип организации
        var type = $('#dictbeneficiarycontractor-type').find(':selected').val();
        var innField = $('#dictbeneficiarycontractor-inn');

        if (type === 'ENT') {
            // Юр. лицо
            innField.inputmask('9999999999', { placeholder: '__________' });
        } else if (type === 'IND') {
            // Физ. лицо
            innField.inputmask('999999999999', { placeholder: '____________' });
        }
    }

    // Проверка доступности поля КПП при инициализации формы
    function checkKppReadonly() {
        var type = $('#dictbeneficiarycontractor-type').find(":selected").val();

        var accountValue = $('#dictbeneficiarycontractor-account').val();

        if (accountValue.length !== 0) {
            if (type === 'ENT') {
                // Юр. лицо
                // КПП доступен для ввода
                $('#dictbeneficiarycontractor-kpp').attr('disabled', false);
            } else if (type === 'IND') {
                // Физ. лицо
                // КПП недоступен для ввода
                $('#dictbeneficiarycontractor-kpp').attr('disabled', true);
            }
        }
    }

    // Получение валюты по номеру счета при изменении номера счета
    $('#dictbeneficiarycontractor-account').on('change', function() {
        checkFieldAccessibility();

        // Получаем текущее значение из поля номера счета
        var accountNumber = $(this).val();

        // Удаляем лишние символы из строки
        var accountRaw = accountNumber.replace(/[_.]/g, '');

        // Количество символов получившегося номера счета
        var accountLength = accountRaw.length;

        // Если у нас полный номер счета (20 символов),
        // пытаемся подобрать для него валюту
        if (accountLength !== 20) {
            return false;
        }

        // Получаем числовой код валюты из счета (символы 5-8)
        var currencyCode = accountRaw.substring(5,8);

        $.ajax({
            url: '/edm/dict-beneficiary-contractor/get-currency',
            type: 'get',
            data: 'code=' + currencyCode,
            success: function(res){

                // Если валюта найдена, автоматически выбираем её в списке валют
                if (res) {
                    $('#dictbeneficiarycontractor-currencyid').val(res);
                } else {
                    // Если валюта не найдена, очищаем поле выбора
                    $('#dictbeneficiarycontractor-currencyid').val('');
                }
            }
        });

        // Получаем тип юридического лица по номеру счета
        $.get('/edm/dict-beneficiary-contractor/get-type-by-number', {number: accountRaw})
         .done(function(type) {
            $('#dictbeneficiarycontractor-type').val(type);
            $('#dictbeneficiarycontractor-type').trigger('change');
        });
    });

    // Скрытие значений выбора валюты
    $('#dictbeneficiarycontractor-currencyid').children('option').hide();

    // Проверка маски поля ИНН
    checkInnField();

    checkKppReadonly();

    // Запрет ввода пробела в качестве первого символа
    $('#dictbeneficiarycontractor-name').on('keypress', function(e) {
       if (this.selectionStart == 0 && e.keyCode == 32) {
            return false;
       }
    });

    $('#dictbeneficiarycontractor-name').on('paste', function(e) {
        var self = this;

        setTimeout(function(e) {
            $('#dictbeneficiarycontractor-name').val($(self).val().replace(/^\s+/g, ''));
        }, 0);
    });
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);

// Необходимые CSS-стили
$this->registerCss('
    .no-padding-left {
        padding-left: 0;
    }

    .no-padding-right {
        padding-right: 0;
    }

    .field-dictbeneficiarycontractor-terminalid {
        display: none;
    }

    .form-group {
        margin-bottom: 5px;
    }

    #w0 [disabled] {
        background-color: #eee;
    }
');

?>