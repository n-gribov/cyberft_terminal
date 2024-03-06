<?php
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app/menu', 'Import settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'ISO20022'), 'url' => Url::toRoute(['/ISO20022/documents'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
    .body-data .row {margin-bottom:10px;}
</style>

<div class="panel panel-primary">
    <?php $form = ActiveForm::begin() ?>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-2">
                <?=Html::submitButton(Yii::t('app', 'Save'), ['name' => 'save', 'class' => 'btn btn-primary btn-block']) ?>
            </div>
        </div>
    </div>
    <div class="panel-body body-data">

        <div class="row">
            <div class="col-sm-4">
                <?=$form->field($settings, 'importSearchSenderReceiver', ['options' => ['class' => 'col-xs-12']])->checkbox()?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end()?>
</div>
