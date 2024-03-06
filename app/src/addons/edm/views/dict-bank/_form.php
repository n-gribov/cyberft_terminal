<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model addons\edm\models\DictBank */
/* @var $form yii\widgets\ActiveForm */

$terminalIdList = [];
if (!empty($certs)) {
    foreach ($certs as $cert) {
        if ($cert->participant) {
            $terminalIdList[$cert->terminalAddress] = $cert->terminalAddress.' ('.$cert->participant->name.')';
        } else {
            $terminalIdList[$cert->terminalAddress] = $cert->terminalAddress;
        }
    }
}

?>

<?php $form = ActiveForm::begin(); ?>

<?=$form->field($model, 'bik')->textInput(['maxlength' => true, 'readonly' => true, 'disabled' => true])?>

<?=$form->field($model, 'account')->textInput(['maxlength' => true, 'readonly' => true, 'disabled' => true])?>

<?=$form->field($model, 'name')->textInput(['maxlength' => true, 'readonly' => true, 'disabled' => true])->label(Yii::t('app/participant', 'Name'))?>

<?=$form->field($model, 'city')->textInput(['maxlength' => true, 'readonly' => true, 'disabled' => true])?>

<?=$item = $form->field($model, 'terminalId')->widget(Select2::classname(), [
    'model' => $model,
    'attribute' => 'terminalId',
    'options'       => [
        'placeholder' => Yii::t('app', 'Search for a {label}', ['label' => Yii::t('edm', 'participant code and name')]),
        'style'       => 'width:100%',
    ],
    'id' => 'terminalIdList',
    'data' => $terminalIdList,
    'pluginOptions' => [
        'allowClear'         => true,
        'minimumInputLength' => 0
    ]
]);
?>

<div class="form-group">
	<?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
</div>

<?php ActiveForm::end(); ?>
