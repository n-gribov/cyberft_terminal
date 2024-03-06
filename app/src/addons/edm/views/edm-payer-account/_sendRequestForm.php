<?php

use addons\edm\models\StatementRequest\StatementRequestType;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

Modal::begin([
    'header' => '<h2>'.Yii::t('edm', 'Send statement request').'</h2>',
    'toggleButton' => [
        'tag' => 'button',
        'class' => 'btn btn-success',
        'label' => Yii::t('edm', 'Statement request'),
    ]
]);


$model            = new StatementRequestType(['accountNumber' => $account->number]);
$model->startDate = date('Y-m-d');
$model->endDate   = date('Y-m-d');
?>

<?php $form           = ActiveForm::begin(['action' => Url::to(['send-request', 'from' => 'view', 'id' => $account->id])]); ?>
<?= $form->field($model, 'accountNumber')->hiddenInput()->label(false) ?>
<?=
$form->field($model, 'startDate')->widget(DatePicker::className(),
    [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ]
    ])
?>
<?=
$form->field($model, 'endDate')->widget(DatePicker::className(),
    [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ]
    ])
?>

<?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>