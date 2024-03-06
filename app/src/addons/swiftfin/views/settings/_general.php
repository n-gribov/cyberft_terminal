<?php

use yii\helpers\Html;

/**
 * @var $this View
  */
?>

<?php
    $form = \yii\bootstrap\ActiveForm::begin();
?>
<div class="panel-heading">
    <?= Yii::t('app/terminal', 'Outbox documents') ?>
</div>
<div class="panel-body">
    <div>
        <?=$form->field($settings, 'validateOnImport')->checkbox()?>
        <?=$form->field($settings, 'swiftRouting')->checkbox()?>
        <?=$form->field($settings, 'swiftRoutePath',[
            'options' => [
                'class' => 'col-xs-3'
            ]
        ])?>
    </div>
</div>
<div class="panel-heading">
    <?= Yii::t('app/terminal', 'Inbox documents') ?>
</div>
<div class="panel-body">
    <div class="">
        <?=$form->field($settings, 'exportExtension', [
            'options' => [
                'class' => 'col-xs-3'
            ],
            'inputOptions' => [
                'maxlength' => 5
            ]
        ])?>
        <div style="clear: both"></div>
        <?=$form->field($settings, 'exportChecksum')->checkbox()?>
        <div style="clear: both"></div>
    </div>
</div>

<div class="panel-body">
        <?=$form->field($settings, 'deliveryExport')->checkbox()?>
        <div style="clear: both"></div>
        <?=$form->field($settings, 'deliveryExportTTL', [
            'options' => [
                'class' => 'col-xs-3'
            ],
            'inputOptions' => [
                'maxlength' => 10
            ]
        ])?>
        <div style="clear: both"></div>
</div>

<div class="panel-body">
    <div class="">
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
