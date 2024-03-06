<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
$this->params['breadcrumbs'][] = Yii::t('app/menu', 'Notification Settings');

$model = $data['model'];

?>
<?php
$form = ActiveForm::begin(
    [
        'id' => 'smtpSettingsForm',
        'method' => 'post',
    ]);
?>
<h3><?=Yii::t('monitor/mailer', 'Mail Server Settings')?></h3>

<div class="form-group">
    <?=$form->field($model, 'host')?>
</div>
<div class="form-group">
    <?=$form->field($model, 'port')?>
</div>
<div class="form-group">
    <?=$form->field($model, 'login')?>
</div>
<div class="form-group">
    <?=$form->field($model, 'password')->passwordInput()?>
</div>
<div class="row">
    <div class="col-lg-2">
        <?=$form->field($model, 'encryption')->dropDownList($model->getEncryptionTypes())?>
    </div>
</div>

<div class="form-group" style="clear:both">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    <?= Html::input('hidden', 'testNow', '0', ['id' => 'inputTestNow'])?>
    <span class="pull-right"><?=Html::label(Yii::t('monitor/mailer', 'Test address'), 'testAddress')?>
        <?= Html::textInput('testAddress', '')?>
        <?= Html::button(Yii::t('monitor/mailer', 'Test'), ['class' => 'btn btn-primary', 'onClick' => 'return testSettings()']) ?>
    </span>
</div>

<?php
ActiveForm::end();
?>

<script>
    function testSettings() {
        $('#inputTestNow').val(1);
        $('#smtpSettingsForm').submit();
        return false;
    }
</script>