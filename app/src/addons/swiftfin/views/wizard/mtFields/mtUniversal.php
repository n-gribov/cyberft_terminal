<?php

use addons\swiftfin\models\documents\mt\MtUniversalDocument;
use kartik\widgets\ActiveForm;
use yii\web\View;

/* @var $form ActiveForm */
/* @var $model MtUniversalDocument */
?>

<?php
if ($model->formable) {
	print '<h4>' . (string) $model->label . '</h4>';
	print $form->field($model, 'data')->hiddenInput()->label('');
	print ($model->toHtmlForm($form));

	return;
}
?>

<div class="alert alert-dismissable alert-warning">
	<?=Yii::t('doc/mt', 'The document is available only through text input')?>
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
</div>
