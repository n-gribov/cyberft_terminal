<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = $this->title;

$model = $data;

?>

<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            <?php
            $form = ActiveForm::begin();
            echo $form->field($model, 'maxLoginAttemptsCount');
            echo $form->field($model, 'passwordExpireDaysCount');
            echo $form->field($model, 'additionalEncryptCert')->dropDownList($model->getCertificateList(), ['prompt' => '']);
            echo $form->field($model, 'strongPasswordRequired')->checkbox();
            echo $form->field($model, 'userPasswordHistoryLength');
            echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']);
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>