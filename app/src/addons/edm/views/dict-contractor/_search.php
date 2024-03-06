<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model addons\edm\models\DictContractorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
	'action' => ['index'],
	'method' => 'get',
]); ?>

<?=$form->field($model, 'id')?>

<?=$form->field($model, 'bankBik')?>

<?=$form->field($model, 'type')?>

<?=$form->field($model, 'kpp')?>

<?=$form->field($model, 'inn')?>

<?php // echo $form->field($model, 'account') ?>

<?php // echo $form->field($model, 'currency') ?>

<?php // echo $form->field($model, 'name') ?>

<div class="form-group">
	<?=Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary'])?>
	<?=Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default'])?>
</div>

<?php ActiveForm::end(); ?>
