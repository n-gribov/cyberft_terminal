<?php

use yii\widgets\ActiveForm;
use common\helpers\Html;
use addons\swiftfin\models\SwiftFinDictBank;

$form = ActiveForm::begin([
    'id' => 'edm-template-fcp-wizard',
    'enableClientValidation' => true,
]);

// Получение данных по банку плательщика
$payerBankModel = SwiftFinDictBank::findOne($model->payerBank)
?>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info fcp-alert-info"></div>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <?=$form->field($model, 'templateName')->textInput( [
            'maxlength' => true,
        ]);?>
    </div>
</div>
<?php
// Вывести страницу
echo $this->render('@addons/edm/views/foreign-currency-operation-wizard/fcp/_common', [
    'form' => $form, 'model' => $model
]);

echo $form->field($model, 'id')->hiddenInput()->label(false);

echo Html::hiddenInput('createDocument', 0, ['class' => 'hidden-create-document']);

ActiveForm::end();

$script = <<<JS
    initFCPTemplate('foreigncurrencypaymenttemplate');
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>


