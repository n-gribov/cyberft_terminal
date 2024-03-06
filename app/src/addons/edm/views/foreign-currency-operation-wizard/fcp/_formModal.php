<?php

use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use addons\swiftfin\models\SwiftFinDictBank;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use yii\jui\DatePicker;
use yii\widgets\MaskedInput;

$dateFieldId = 'foreigncurrencypaymenttype-date';

// если это форма редактирования
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

// Получение данных по банку плательщика
$payerBankModel = SwiftFinDictBank::findOne($model->payerBank);
?>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info fcp-alert-info"></div>
    </div>
</div>

<h4 class="fcp-main-header">Операция</h4>
<div class="row">
    <div class="col-sm-12">
        <?= $form->field($model, 'operationType')->dropDownList(
            ForeignCurrencyOperationFactory::getOperationTypes(),
            ['class' => 'body-select form-control', 'disabled' => true]
        )->label(false)?>
    </div>
</div>

<h4 class="fcp-main-header">Шаблоны</h4>
<div class="row">
    <div class="col-sm-12">

        <?php
            echo $form->field($model, 'templateSelect')->widget(Select2::className(), [
                'options'       => ['placeholder' => '...', 'class' => 'has-success'],
                'theme' => Select2::THEME_BOOTSTRAP,
                'pluginOptions' => [
                    'allowClear'         => true,
                    'minimumInputLength' => 0,
                    'ajax'               => [
                        'url'      => Url::to(['/edm/payment-order-templates/get-fcp-templates-list']),
                        'dataType' => 'json',
                        'delay'    => 250,
                        'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
                    ],
                    'templateResult' => new JsExpression('function(item) {
                        if(item.templateName) return item.templateName + " - " + item.beneficiaryName;
                  }'),
                    'templateSelection' => new JsExpression('function(item) {
                        if(item.templateName) return item.templateName + " - " + item.beneficiaryName;
                  }'),
                ],
                'pluginEvents' => [
                    'select2:select' => 'function(e) { applyTemplate(e.params.data); }',
                ],
            ])->label(false);
        ?>

    </div>
</div>

<div class="row padding-left-20">
    <div class="col-md-4">
        <?= $form->field($model, 'number')->textInput( [
            'maxlength' => true,
            'class' => 'form-control validate-mask'
        ]); ?>
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

<?=$this->render('_common', ['form' => $form, 'model' => $model]);?>

<div class="row fcp-margin-top-10">
    <div class="col-md-12">
        <?=$form->field($model, 'saveTemplate')->checkbox() ?>
    </div>
    <div class="col-md-6">
        <div class="fcp-padding-left-20">
            <?=$form->field($model, 'templateName')->textInput()->label(false) ?>
        </div>
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
    $('#fcoCreateModalButtons').show();

    initFCPDocument('foreigncurrencypaymenttype');

    $('#fcoForm').change(function() {
        $.post('/wizard-cache/fcp', $(this).serialize());

        var type = $('#foreigncurrencypaymenttype-operationtype').val();
        var queryParams = parseQueryString();
        var url = window.location.pathname + '?tabMode=' + queryParams.tabMode + '&withCache=1&cacheType=' + type;

        window.history.replaceState({}, '', url);
    });

JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>
