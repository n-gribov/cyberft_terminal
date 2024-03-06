<?php

use common\models\Terminal;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$model = $data['model'];


?>

<div class="terminal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'terminalId')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList(Terminal::getstatusLabels()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <hr>

    <?php if(!$model->isNewRecord): ?>
        <div class="remote-ids-block">
            <?=$this->render('_terminalRemoteIds', compact('data'));?>
        </div>
    <?php endif; ?>
</div>
