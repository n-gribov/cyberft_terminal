<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var \yii\web\View $this */
/** @var \backend\models\forms\UploadUserAuthCertForm $model */
/** @var integer $userId */

$form = ActiveForm::begin([
    'method' => 'post',
    'action' => Url::toRoute(['/user-auth-cert/create', 'id' => $userId]),
    'id' => 'upload-certificate-form',
    'fieldConfig' => ['template' => '{input}', 'options' => ['class' => '']],
    'options' => [
        'enctype' => 'multipart/form-data',
        'style'   => 'display: inline-block;'
    ]
]);

echo $form->field($model, 'certificateFile')->fileInput(['class' => 'hidden']);
echo $form->field($model, 'certId')->hiddenInput();

echo Html::button(
    Yii::t('app/user', 'Add certificate'),
    [
        'class' => 'btn btn-success',
        'id'    => 'upload-certificate-button',
        'style' => 'margin-right: 10px;',
        'type'  => 'button',
    ]
);
ActiveForm::end();

$js = <<<JS
    $('#upload-certificate-form').trigger('reset');
    
    var certificateFileInput = $('input:file[name="UploadUserAuthCertForm[certificateFile]"]');
    $('#upload-certificate-button').click(function() {
        certificateFileInput.trigger('click');
    });
    
    certificateFileInput.change(function() {
        if (this.val !== '') {
            $(this).closest('form').trigger('submit');
        }
    })
JS;

$this->registerJs($js, yii\web\View::POS_READY);
