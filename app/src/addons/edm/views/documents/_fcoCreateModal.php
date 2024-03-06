<?php
use yii\bootstrap\Modal;

$header = '<h4 class="modal-title" id="fcoCreateModalTitle"></h4>';
$footer = '<div id="fcoCreateModalButtons" style="display:none">
        <button id="fcoCreateButton" type="button" class="btn btn-success">Создать</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
</div>';

$modal = Modal::begin([
    'id' => 'fcoCreateModal',
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => false,
    ],
    'options' => [
        'tabindex' => false
    ],
    'headerOptions' => ['id' => 'fcoCreateModalHeader'],
    'footerOptions' => ['id' => 'fcoCreateModalFooter'],
    'header' => $header,
    'footer' => $footer,
]);

$modal::end();

// CSS
$this->registerCss('
    #fcoCreateModal .modal-body {
        /* height: 800px; */
        /* overflow: auto; */
        padding: 0 2em 0 2em;
    }

    #fcoCreateModal {
        /* overflow: hidden !important; */
    }

    #fcoCreateModal .modal-content {
        width: 720px;
    }
');

$currentTabMode = Yii::$app->request->get('tabMode');

// JS
$script = <<< JS
    $('#fcoCreateButton').on('click', function(e) {

        $('#realCreateSubmitFlag').attr('value', 1);

        var form = $('#fcoForm');

        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            type: 'POST',
            format: 'JSON',
            success: function(result) {
                if (result.charAt(0) === '{') {
                    //
                    /*
                     * (CYB-4637) В соответствующем action-е ставится flash,
                     * который даст сигнал на открытие модалки после
                     * перезагрузки страницы и будет содержать нужные данные
                    */
                } else {
                    // render form again with error messages
                    $('#fcoCreateModal .modal-body').html(result);
                }
            }
        });

        $('#realCreateSubmitFlag').attr('value', 0);

        return false;
    });

    $('#fcoCreateModal').on('shown.bs.modal', function () {
        $('body').css('overflow', 'hidden');
    });

    $('#fcoCreateModal').on('hide.bs.modal', function () {
        $('body').css('overflow', 'auto');

        var clean_uri = location.protocol + '//' + location.host + location.pathname;

        var currentTabMode = '$currentTabMode';

        if (currentTabMode) {
            clean_uri += '?tabMode=' + currentTabMode;
        }

        window.history.replaceState({}, document.title, clean_uri);
    })
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>