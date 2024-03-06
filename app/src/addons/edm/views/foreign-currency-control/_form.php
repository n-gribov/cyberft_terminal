<?php

use addons\edm\helpers\EdmHelper;
use common\helpers\Html;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\jui\DatePicker;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$this->title = Yii::t('edm', 'Foreign currency information');
$submitCreateText = Yii::t('app', 'Create');

$form = ActiveForm::begin([
    'method' => 'POST',
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'edm-fcc-form']
]);
?>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'number')->textInput(['maxlength' => 35]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'date')?>
        <?php MaskedInput::widget([
            'id'            => 'foreigncurrencyoperationinformation-date',
            'name'          => 'foreigncurrencyoperationinformation-date',
            'mask'          => '99.99.9999',
            'clientOptions' => ['placeholder' => 'dd.MM.yyyy']
        ])?>
        <?php DatePicker::widget([
            'id'         => 'foreigncurrencyoperationinformation-date',
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'correctionNumber')->textInput(['maxlength' => 35]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'countryCode')->widget(Select2::class, [
            'data' => EdmHelper::countryCodesList(),
            'options' => ['placeholder' => 'Выберите код страны', 'prompt' => 'Выберите код страны'],
            'pluginOptions' => [
                'allowClear' => true,
                'templateSelection' => new JsExpression('function(item) { return item.id; }')
            ],
        ])
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <?php
            $fieldOptions = [
                'data' => $organizations,
                'options' => ['placeholder' => 'Выберите организацию', 'prompt' => 'Выберите организацию'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ];
        ?>
        <?= $form->field($model, 'organizationId')->widget(Select2::class, $fieldOptions) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <?= $form->field($model, 'authorizedBankId')->widget(Select2::class, [
            //'data' => $banks,
            'options' => ['placeholder' => 'Выберите банк', 'prompt' => 'Выберите банк'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])?>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <?= $form->field($model, 'accountId')->widget(Select2::class, [
            'options' => ['placeholder' => 'Выберите счет', 'prompt' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])?>
    </div>
</div>

<div class="operations" style="margin: 25px 0">
<?php
    // Вывести блок уже существующих операций
    echo $this->render('_operations', ['childObjectData' => $model->operations]);
?>
</div>
<div class="row">
    <div class="col-md-8">
        <a href="#" class="btn btn-primary btn-new-operation">Добавить строку</a>
        <div style="margin-top:1em"><?=
            Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), [
                'id' => 'btn-submit-fcc',
                'class' => 'btn btn-success',
                'data' => [
                    'toggle' => 'tooltip',
                    'placement' => 'right',
                    'title' => 'Добавьте операции перед созданием документа'
                ]
            ])
        ?></div>
    </div>
</div>
<?php
ActiveForm::end();

$modal = Modal::begin([
    'id' => 'operation-modal',
    'header' => Html::tag('h4', 'Добавить строку', ['class' => 'modal-title']),
    'footer' => Html::button(
        Yii::t('app', 'Create'), [
            'id' => 'operation-submit', 'class' => 'btn btn-success',
            'data' => ['loading-text' => '<i class="fa fa-spinner fa-spin"></i> ' . Yii::t('app', 'Create')]
        ])
        . Html::button(
            Yii::t('app', 'Cancel'), [
                'id' => 'cancel-button', 'class' => 'btn btn-default',
                'data' => ['dismiss' => 'modal']
            ]
        ),
    'options' => [
        'tabindex' => false,
        'data' => ['backdrop' => 'static']
    ]
]);
$modal::end();

$this->registerCss(<<<CSS
    .operations-table {
        border: 2px solid #00529C;
    }
    .operations-table th {
        padding: 10px;
        border: 2px solid #00529C;
    }
    #operation-modal .modal-content {
        width: 720px;
    }
CSS);

$opcount = count($model->operations);

$script = <<<JS
    // Вызов модального окна для добавления новой строки
    $('.btn-new-operation').on('click', function(e) {
        e.preventDefault();

        $.ajax({
            url: '/edm/foreign-currency-control/add-operation',
            type: 'get',
            success: function(result) {
                $('#operation-modal .modal-body').html(result);
                $('#operation-submit').html('{$submitCreateText}');
                attachFileController.initialize();
                $('#operation-modal').modal('show');
            }
        });
    });

    // сохранение операции
    $('#operation-submit').on('click', function(e) {
        var formData = $('#operation-form').serialize();
        $.ajax({
            url: '/edm/foreign-currency-control/process-operation-form',
            data: formData,
            type: 'post',
            success: function(result) {
                // Если успешно, закрываем форму и отображаем результат
                if (result.status === 'ok') {
                    $('#operation-modal').modal('hide');

                    // Отображение таблицы операций
                    $('.operations').html(result.content);

                    checkCreateButton(result.opcount);

                    // Кэширование данных документа
                    form = $('#operation-form').serialize();
                    $.post('/wizard-cache/fcc', form);
                } else if (result.status === 'error') {
                    $('body').off('click', '#operation-submit');

                    // Иначе перерисовка формы
                    $('#operation-modal .modal-body').html(result.content);
                }
            }
        });
    });

    function clearList(element) {
        // Удаление текущего выбранного значения
        element.select2('val', '');
        // Удаляем существующие элементы списка
        element.empty();
    }

    function fillList(element, result) {
        var html = '';
        var keys = Object.keys(result);
        var cnt = keys.length;
        if (cnt > 1) {
            html += '<option value=""></option>';
        }
        for (var i = 0; i < cnt; i++) {
            html += '<option value="' + keys[i] + '">' + result[keys[i]] + '</option>';
        }
        element.append(html);

        return cnt;
    }

    function updateBankList() {
        var orgId = getOrgId();
        if (!orgId) {
            return;
        }
        var bankElement = $('#foreigncurrencyoperationinformationext-authorizedbankid');
        var accountElement = $('#foreigncurrencyoperationinformationext-accountid');
        clearList(bankElement);
        clearList(accountElement);

        // Получение banks по выбранной организации
        $.ajax({
            url: '/edm/dict-organization/get-banks?orgId=' + orgId,
            type: 'get',
            success: function(result) {
                var cnt = fillList(bankElement, result);
                if (cnt == 1) {
                    updateAccountList();
                }
            }
        });
    }

    function updateAccountList() {
        var bankElement = $('#foreigncurrencyoperationinformationext-authorizedbankid');
        var accountElement = $('#foreigncurrencyoperationinformationext-accountid');
        var bankBik = bankElement.val();
        var orgId = getOrgId();

        clearList(accountElement);

        // Получение счетов по selected bank
        $.ajax({
            url: '/edm/dict-organization/get-accounts?orgId=' + orgId + '&bankBik=' + bankBik,
            type: 'get',
            success: function(result) {
                fillList(accountElement, result);
            }
        });
    }

    function getOrgId() {
        return $('#foreigncurrencyoperationinformationext-organizationid').val();
    }

    function cacheWizardFormData() {
        form = $('.edm-fcc-form').serialize();
        $.post('/wizard-cache/fcc', form);
    }

    $('.edm-fcc-form').change(function() {
        cacheWizardFormData();
    });

    // Событие изменения организации
    $('body').on('change', '#foreigncurrencyoperationinformationext-organizationid', function() {
        updateBankList();
    });

    $('body').on('change', '#foreigncurrencyoperationinformationext-authorizedbankid', function() {
        updateAccountList();
    });

    checkCreateButton({$opcount});

    updateBankList();
JS;

// Если документ уже сформирован, выдаем предупреждение перед созданием
if ($model->documentId && $model->document->signaturesCount > 0) {
    $script .= <<<JS
    $('#btn-submit-fcc').on('click', function() {
        var result = confirm('Внимание! Документ подписан!'
        + ' В случае изменения документа подписи будут автоматически отозваны! Редактировать документ?');

        if (result === false) {
            return false;
        }
    });
JS;
}

$this->registerJs($script, View::POS_READY);

$this->registerJsFile(
    '@web/js/edm/fco-information-item/fco-information-item-form.js',
    ['depends' => [JqueryAsset::class]]
);

?>
<script>
    // Доступность кнопки создания документа
    function checkCreateButton(opcount) {
        var submitButton = $('#btn-submit-fcc');

        if (opcount > 0) {
            submitButton.removeClass('disabled');
            submitButton.tooltip('destroy');
            submitButton.off('click');
        } else {
            submitButton.addClass('disabled');
            submitButton.tooltip();
            submitButton.on('click', function(e) {
                return false;
            });
        }
    }
</script>