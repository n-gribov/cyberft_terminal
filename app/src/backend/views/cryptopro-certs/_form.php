<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
$modelClass = $model->formName();
?>

<div class="row">
    <div class="col-xs-4">
        <?php $form = ActiveForm::begin([
            'method'	=> 'post',
            'options'	=> ['enctype' => 'multipart/form-data'],
        ]); ?>
        <?php
            if ($model->isNewRecord) {
                // Форма выбора сертификата доступна
                // только в режиме создания нового сертификата

                $this->registerJsFile(
                    '/js/plugins/jasny-bootstrap.min.js',
                    ['depends' => [\yii\web\JqueryAsset::className()]]
                );
        ?>
                <div class="form-group">
                    <label class="control-label"><?= $model->getAttributeLabel('certificate') ?></label>
                    <div>
                        <?=kartik\file\FileInput::widget([
                            'name'  => $modelClass . '[certificate]',
                            'pluginOptions' => [
                                'showPreview' => false,
                                'showUpload' => false
                            ]
                        ]) ?>
                    </div>
                    <?php if ($model->hasErrors('certificate')) : ?>
                        <div class="alert-danger alert" style="clear: both">
                            <?=implode("\n<br/>",$model->getErrors('certificate'))?>
                        </div>
                    <?php endif ?>
                </div>
        <?php
            }

            echo $form->field($model, 'terminalId')->widget(Select2::classname(), [
                'data' => $receiversLists,
                'options' => ['prompt' => ''],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]);

            if ($modelClass != 'VTBCryptoproCert') {
                echo $form->field($model, 'senderTerminalAddress')->widget(Select2::classname(), [
                    'data' => $sendersList,
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);
            }

            // Если пользователь в режиме редактирования, то можно менять имя владельца
            if (!$model->isNewRecord) {
                echo $form->field($model, 'ownerName')->textInput(['maxlength' => 255]);
                // Остальные поля доступны только в режиме просмотра
                echo $form->field($model, 'keyId')->textInput(['readonly' => 'readonly']);
                echo $form->field($model, 'serialNumber')->textInput(['readonly' => 'readonly']);
                echo $form->field($model, 'validBefore')->textInput(['readonly' => 'readonly']);
            }
        ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>