<?php

use yii\bootstrap\Modal;
use yii\web\View;

$header = Yii::t('app/autobot', 'Activating the controller key');
$footer = '<button type="button" id="btn-submit-activate" class="btn btn-primary">' . Yii::t('app/autobot', 'Activate') . '</button>';

Modal::begin([
    'id' => 'modal-activate-autobot',
    'size' => Modal::SIZE_SMALL,
    'header' => $header,
    'footer' => $footer
]);

?>

<form id="form-activate-autobot">
    <div class="form-group">
        <label for="passport"><?=Yii::t('app/autobot', 'Key password')?>:</label>
        <input id="passport" name="password" type="password" class="form-control" autofocus>
        <input id="keyId" type="hidden" name="keyId">
    </div>
</form>

<div class="activate-status-message text-danger"></div>

<?php Modal::end(); ?>

<?php

// JS
$script = <<<JS
    $('#modal-activate-autobot').on('shown.bs.modal', function() {
      $(this).find('[autofocus]').focus();
    });

    $('.btn-activate-autobot').on('click', function(e) {
        e.preventDefault();

        // Открытие формы
        $('#modal-activate-autobot').modal();

        // Заполнение id ключа для проверки пароля
        $('#keyId').val($(this).data('id'));

        // Удаление текста ошибки
        $('.activate-status-message').text('');
    });

    $('#btn-submit-activate').on('click', function(e) {
        $('.activate-status-message').text('');
        $.ajax({
            url: '/autobot/activate-autobot',
            type: 'post',
            data: $('#form-activate-autobot').serialize(),
            success: function(res){

              if (res.status === 'error') {
                $('.activate-status-message').text(res.msg);
              } else if (res.status === 'ok') {
                 window.location.reload();
                 return false;
              }
            }
        });
    });
JS;

$this->registerJs($script, View::POS_READY);

