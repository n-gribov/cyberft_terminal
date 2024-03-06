<?php

use yii\bootstrap\Modal;
use yii\web\View;

$blockText = Yii::t('app/autobot', 'It is impossible to block the controller key when the exchange with the network is enabled! Stop the exchange with the network for the terminal {terminalId} and block the key?');

$headerblock = Yii::t('app/autobot', 'Controller key blocking error');
$footerblock = "<a href='#' class='btn btn-success btn-stop-block'>Выполнить</a><button class='btn btn-default' data-dismiss='modal'>" . Yii::t('app', 'Cancel') . "</button>";

Modal::begin([
    'id' => 'modal-block-autobot',
    'header' => $headerblock,
    'footer' => $footerblock
]);

?>

<p class="block-text"></p>

<?php Modal::end(); ?>

<?php

// JS
$script = <<<JS

$('.btn-block-autobot').on('click', function(e) {
    e.preventDefault();

    var blockUrl = $(this).data('block-url');
    var blockStopUrl = $(this).data('block-stop-url');
    var terminalId = $(this).data('terminal-Id');
    var status = $(this).data('status');

    if (status == 'active') {
        window.location.replace(blockUrl);
    } else {
        $.ajax({
            url: '/autobot/multiprocesses/check-terminal-running',
            type: 'get',
            data: {'terminalId': terminalId},
            success: function(data) {
                if (data['status'] === 'ok') {
                    window.location.replace(blockUrl);
                } else {
                    blockText = '$blockText';
                    blockText = blockText.replace('{terminalId}', terminalId);
                    $('.block-text').html(blockText);

                    $('.btn-stop-block').attr('href', blockStopUrl);
                    $('#modal-block-autobot').modal();
                }
            }
        });
    }
});


JS;
$this->registerJs($script, View::POS_READY);

?>
