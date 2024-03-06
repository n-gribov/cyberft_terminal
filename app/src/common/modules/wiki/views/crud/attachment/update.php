<?php

use common\modules\wiki\WikiModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = WikiModule::t('default', 'Update attachment');
$this->params['breadcrumbs'][] = ['label' => WikiModule::t('default', 'Documentation'), 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label' => WikiModule::t('default', 'Page #{id}', ['id' => $model->page->id]), 'url' => ['crud/update', 'id' => $model->page->id]];
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);

echo $form->field($model, 'title')->textInput(['maxlength' => 50]);
echo $form->field($model, 'description')->textInput();
?>

<?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary'])?>

<?php ActiveForm::end();