<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model addons\edm\models\DictBankSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
	'action' => ['index'],
	'method' => 'get',
]); ?>

<?=$form->field($model, 'bik')?>

<?=$form->field($model, 'account')?>

<?=$form->field($model, 'name')?>

<?=$form->field($model, 'city')?>

<div class="form-group">
	<?=Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary'])?>
	<?=Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default'])?>
</div>

<?php ActiveForm::end(); ?>
