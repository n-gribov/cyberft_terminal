<?php
use common\models\Terminal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model addons\VTB\models\VTBCryptoproCert */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-xs-4">
        <?php $form = ActiveForm::begin([
            'method'	=> 'post',
            'options'	=> ['enctype' => 'multipart/form-data'],
        ]) ?>
        <?php
            // Форма выбора сертификата доступна только в режиме создания нового сертификата
            if ($model->isNewRecord) :
        ?>
            <?php
                $this->registerJsFile(
                    '/js/plugins/jasny-bootstrap.min.js',
                    ['depends' => [\yii\web\JqueryAsset::className()]]
                );
            ?>
            <div class="form-group">
                <label class="control-label"><?=$model->getAttributeLabel('certificate')?></label>
                <div class="">
                    <?=kartik\file\FileInput::widget([
                        'name'  => 'VTBCryptoproCert[certificate]',
                        'pluginOptions' => [
                            'showPreview' => false,
                            'showUpload' => false
                        ]
                    ]) ?>
                </div>
                <?php if ($model->hasErrors('certificate')) : ?>
                    <div class="alert-danger alert" style="clear: both">
                        <?=implode("\n<br/>", $model->getErrors('certificate'))?>
                    </div>
                <?php endif ?>
            </div>
        <?php endif ?>
        <?= $form->field($model, 'terminalId')->dropDownList(Terminal::getList('id', 'terminalId'));?>
        <?php
            // Если пользователь в режиме редактирования, то можно менять имя владельца
            if (!$model->isNewRecord) :
        ?>
            <?= $form->field($model, 'ownerName')->textInput(['maxlength' => 12]);?>
            <?php
                // Остальные поля доступны только в режиме просмотра
            ?>
            <?= $form->field($model, 'keyId')->textInput(['readonly' => 'readonly']);?>
            <?= $form->field($model, 'serialNumber')->textInput(['readonly' => 'readonly']);?>
            <?= $form->field($model, 'validBefore')->textInput(['readonly' => 'readonly']);?>
        <?php endif ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
