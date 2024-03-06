<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

Modal::begin([
    'header' => '<h2>'.Yii::t('app/participant', 'Update request').'</h2>',
    'toggleButton' => [
        'tag' => 'button',
        'class' => 'btn btn-success',
        'label' => Yii::t('app/participant', 'Request update'),
    ]
]);

?>

<?php $form = ActiveForm::begin(['action' => Url::toRoute(['send-request'])]); ?>
    <div class="form-row">
        Будет отправлен запрос на полное обновление справочника
        <br/><br/>
    </div>
    <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>
<br/><br/>
