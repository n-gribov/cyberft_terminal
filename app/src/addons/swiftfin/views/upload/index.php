<?php
/* @var $this View */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

$this->title = Yii::t('app/menu', 'Document from file');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-8">
        <?php $form = ActiveForm::begin([
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
            <label class="control-label col-md-2"><?=Yii::t('doc', 'Load from file')?>:</label>
            <?=kartik\file\FileInput::widget([
                'name'  => 'documentfile',
                'pluginOptions' => [
                    'showPreview' => false,
                    'showUpload' => false
                ]
            ]) ?>
        </div>
        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger">
                <p><?=nl2br($error)?></p>
            </div>
        <?php endif ?>
        <div class="form-group">
            <div class="col-md-2 col-md-offset-10">
                <?= Html::submitButton(Yii::t('app', 'Load'), ['class' => 'btn btn-primary btn-block']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>