<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\certManager\models\Cert */

$this->title = Yii::t('app/cert', 'Add certificate');
?>

<div class="row">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin([
            'id' => 'manage-cert',
            'method'	=> 'post',
            'options'	=> ['enctype' => 'multipart/form-data'],
            'type' 		=> ActiveForm::TYPE_HORIZONTAL
        ]); ?>

        <?php
        $this->registerJsFile(
            '/js/plugins/jasny-bootstrap.min.js',
            ['depends' => [\yii\web\JqueryAsset::className()]]
        );
        ?>
        <div class="form-group">
            <label class="col-xs-2 control-label"><?= $model->getAttributeLabel('certificate') ?></label>
            <div class="col-xs-10">
                <?=kartik\file\FileInput::widget([
                    'name'  => 'Cert[certificate]',
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showUpload' => false
                    ]
                ]) ?>
            </div>
            <?php if($model->hasErrors('certificate')): ?>
                <div class="alert-danger alert" style="clear: both">
                    <?=implode("\n<br/>",$model->getErrors('certificate'))?>
                </div>
            <?php endif; ?>
        </div>

        <?= $form->field($model, 'terminalId')->textInput(['maxlength' => 12]) ?>
        <?= $form->field($model, 'role')->dropDownList($model->roleLabels())?>
        <?= $form->field($model, 'lastName')->textInput(['maxlength' => 64]) ?>
        <?= $form->field($model, 'firstName')->textInput(['maxlength' => 64]) ?>
        <?= $form->field($model, 'middleName')->textInput(['maxlength' => 64]) ?>
        <?= $form->field($model, 'post')->textInput(['maxlength' => 64]) ?>
        <?= $form->field($model, 'email')->input('email'); ?>
        <?= $form->field($model, 'phone')->textInput(['maxlength' => 64]) ?>

        <p class="text-right">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'),
                ['id' => 'btn-manage-cert', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </p>

        <?php ActiveForm::end(); ?>
    </div>
</div>
