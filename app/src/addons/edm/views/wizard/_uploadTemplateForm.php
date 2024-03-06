<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

ActiveForm::begin([
    'method'  => 'post',
    'action'  => Url::toRoute(['/edm/wizard/step2-upload']),
    'options' => [
        'enctype' => 'multipart/form-data',
        'style'   => 'display: inline-block;'
    ]
]);

echo Html::fileInput('payment-order-file', null, ['class' => 'hidden']);

echo Html::button(
    Yii::t('doc', 'Load from file'),
    [
        'class' => 'btn btn-success',
        'id'    => 'upload-payment-order-button',
        'style' => 'margin-right: 10px;'
    ]
);
ActiveForm::end();

$js = <<<JS
    $('input[name=payment-order-file]').closest('form').get(0).reset();
    
    $('#upload-payment-order-button').click(function() {
        $('input[name=payment-order-file]').trigger('click');
    });
    
    $('input[name=payment-order-file]').change(function() {
        if (this.val !== '') {
            $(this).closest('form').trigger('submit');
        }
    })
JS;

$this->registerJs($js, yii\web\View::POS_READY);
