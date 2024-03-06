<?php
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

?>
<div class="panel panel-body">
<?php $form = ActiveForm::begin() ?>
    <div class="row">
        <div class="col-sm-4">
            <?=$form->field($settings, 'sftpEnable', ['options' => ['class' => 'col-xs-12']])->checkbox()?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <?=$form->field($settings, 'sftpHost', ['options' => ['class' => 'col-xs-12']])?>
        </div>
        <div class="col-sm-1">
            <?=$form->field($settings, 'sftpPort', ['options' => ['class' => 'col-xs-12']])?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <?=$form->field($settings, 'sftpUser', ['options' => ['class' => 'col-xs-12']])?>
        </div>
        <div class="col-sm-2">
            <?=$form->field($settings, 'sftpPassword', ['options' => ['class' => 'col-xs-12']])->passwordInput()?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <?=$form->field($settings, 'sftpPath', ['options' => ['class' => 'col-xs-12']])?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2">
            <?=Html::submitButton(Yii::t('app', 'Save'), ['name' => 'save', 'class' => 'btn btn-primary btn-block']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end()?>