<?php

use kartik\widgets\ActiveForm;

/* @var $form ActiveForm */
/* @var $model MtXXXDocument */
$this->registerJs('mtOptionsInit()', yii\web\View::POS_READY);
?>

<h4><?=(string)$model->label?></h4>

<?php
if ($model->formable) {
	foreach ($model->attributes() as $v) {
		print $form
			->field($model, $v, [
				'addon' => [
					'prepend' => ['content' => $model->getAttributeTag($v)],
				],
			])
			->{$model->attributeField($v)}();
	}
	return;
}
?>

<div class="alert alert-dismissable alert-warning">
	<?=Yii::t('doc/mt', 'The document is available through text input.')?>
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
</div>
