<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

ActiveForm::begin([
    'method'  => 'post',
    'action'  => Url::toRoute(['import-payment-register']),
    'options' => [
        'enctype' => 'multipart/form-data',
        'style'   => 'display: inline-block;'
    ]
]);
echo Html::fileInput('register_file', null, ['class' => 'hidden']);
echo Html::button(
    Yii::t('edm', 'Upload register'),
    [
        'class' => 'btn btn-success',
        'id'    => 'upload-register-button',
        'style' => 'margin-right: 10px;'
    ]
);
ActiveForm::end();

$js = <<<JS
    $('input[name=register_file]').closest('form').get(0).reset();
    
    $('#upload-register-button').click(function() {
        $('input[name=register_file]').trigger('click');
    });
    
    $('input[name=register_file]').change(function() {
        if (this.val !== '') {
            $(this).closest('form').trigger('submit');
        }
    })
JS;

$this->registerJs($js, yii\web\View::POS_READY);
