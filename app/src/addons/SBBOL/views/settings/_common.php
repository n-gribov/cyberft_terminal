<?php
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="panel panel-body">
<?php $form = ActiveForm::begin() ?>

<div class="row">
    <div class="col-sm-4">
        <?=$form->field($settings, 'gatewayUrl', ['options' => ['class' => 'col-xs-12']])->textInput() ?>
    </div>
</div>
<br/>
<div class="form-group">
    <div class="col-sm-2">
        <?=Html::submitButton(Yii::t('app', 'Save'), ['name' => 'save', 'class' => 'btn btn-primary btn-block']) ?>
    </div>
</div>

<?php ActiveForm::end()?>

</div>