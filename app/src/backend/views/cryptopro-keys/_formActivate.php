<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CryptoproKey;

/* @var $this yii\web\View */
/* @var $model CryptoproKey */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-xs-4">
        <div>
            <h5>Статус: <?=$model->getStatusLabel();?></h5>
            <hr>
        </div>
        <?php $form = ActiveForm::begin(); ?>

        <?php
            if (CryptoproKey::STATUS_READY == $model->status) {
                $options = ['disabled' => true];
            } else {
                $options = [];
            }

            echo $form->field($model, 'password')->passwordInput($options);
        ?>

        <?= $form->field($model, 'active')->checkbox(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app/fileact', 'Create') : Yii::t('app/fileact', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
