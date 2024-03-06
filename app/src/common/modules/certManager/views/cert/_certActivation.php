<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CryptoproKey;

echo $this->render('@backend/views/cryptopro-keys/_certDetail', [
    'cert' => openssl_x509_parse($model->certData),
]);

?>

<div class="row">
    <div class="col-xs-12">
        <div>
            <h5>Статус: <?=$model->getStatusLabel();?></h5>
            <hr>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'activate-cert-form'
        ]); ?>

        <div class="msg-block alert alert-danger"></div>

        <?php
            if ($model->status == CryptoproKey::STATUS_READY) {
                $options = ['disabled' => true];
            } else {
                $options = [];
            }

            echo Html::hiddenInput('id', $model->id);
            echo $form->field($model, 'password')->passwordInput($options);
        ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>


<?php

$this->registerCss('
    .msg-block {
        display: none;
    }
');

$script = <<< JS
    var handleSubmit = function(e) {
        var data = {
            id: $('input[name="id"]').val(),
            password: $('#cryptoprokey-password').val()
        };

        $.ajax({
            url: '/certManager/cert/activate-key',
            type: 'POST',
            data: data,
            success: function(answer) {
                answer = JSON.parse(answer);

                $('.msg-block').hide();

                // Если ошибка, выводим сообщение
                if (answer.status == 'error') {
                    $('.msg-block').html(answer.text);
                    $('.msg-block').show();
                }
            }
        });
        return false;
    };

    $('#activate-cert-form').off('submit').on('submit', handleSubmit);
    $('.btn-submit-activate-form').off('click').on('click', handleSubmit);
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>