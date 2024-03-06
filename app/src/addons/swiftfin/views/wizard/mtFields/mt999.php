<?php

use addons\swiftfin\models\documents\mt\MtBaseDocument;
use addons\swiftfin\models\documents\mt\Mt999Document;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/* @var $form ActiveForm */
/* @var $model Mt999Document */

$this->registerJs(<<<JS
multilineInputInit();
JS
, yii\web\View::POS_READY);

//$form->enableClientValidation = true;
?>

<?=$form->field($model, 'transactionReferenceNumber', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('transactionReferenceNumber')],
	],
])->textInput(['maxlength' => 16]);
?>

<?=$form->field($model, 'relatedReference', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('relatedReference')],
	],
])->textInput(['maxlength' => 16]);
?>

<?=$form->field($model, 'narrative', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('narrative')],
	],
])->textarea(['rows' => 35, 'data-limit' => '35,50']);
?>