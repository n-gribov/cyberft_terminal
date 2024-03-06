<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
	'action' => ['index'],
	'method' => 'get',
]); ?>

<?= $form->field($model, 'id') ?>

<?= $form->field($model, 'email') ?>

<?= $form->field($model, 'lastName') ?>

<?= $form->field($model, 'firstName') ?>

<?= $form->field($model, 'middleName') ?>


<?php echo $form->field($model, 'role') ?>

<?php echo $form->field($model, 'status') ?>

<?php echo $form->field($model, 'created_at') ?>

<?php echo $form->field($model, 'updated_at') ?>

<div class="form-group">
	<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
	<?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
</div>

<?php ActiveForm::end(); ?>
