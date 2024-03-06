<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CryptoproKey */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-xs-4">
        <div>
            <h5>Статус: <?=$model->getStatusLabel();?></h5>
            <hr>
        </div>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'userId')->dropDownList($model->getUserList()); ?>
        <?= $form->field($model, 'keyId')->textInput(['maxlength' => true, 'disabled' => true]) ?>
        <?= $form->field($model, 'ownerName')->textInput(['maxlength' => true, 'disabled' => true]) ?>
        <?= $form->field($model, 'serialNumber')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'certData')->textarea(['rows' => 6, 'disabled' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app/fileact', 'Create') : Yii::t('app/fileact', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
