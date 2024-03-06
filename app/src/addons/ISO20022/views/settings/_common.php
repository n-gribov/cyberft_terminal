<?php
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="panel panel-body">
<?php $form = ActiveForm::begin() ?>

<div class="row">
    <div class="col-sm-4">
        <?=$form->field($settings, 'importSearchSenderReceiver', ['options' => ['class' => 'col-xs-12']])->checkbox()?>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <?=$form->field($settings, 'keepOriginalFilename', ['options' => ['class' => 'col-xs-12']])->checkbox()?>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <?=$form->field($settings, 'exportIBankFormat', ['options' => ['class' => 'col-xs-12']])->checkbox()?>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <?=$form->field($settings, 'exportReceipts', ['options' => ['class' => 'col-xs-12']])->checkbox()?>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <?=$form->field($settings, 'useUniqueAttachmentName', ['options' => ['class' => 'col-xs-12']])->checkbox()?>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-2">
        <?=Html::submitButton(Yii::t('app', 'Save'), ['name' => 'save', 'class' => 'btn btn-primary btn-block']) ?>
    </div>
</div>

<?php ActiveForm::end()?>

</div>