<?php
use yii\bootstrap\Modal;

$header = '<h4 class="modal-title" id="fcoModalTitle"></h4>';
$footer = '<div id="fcoUpdateModalButtons">
        <button id="fcoUpdateButton" type="button" class="btn btn-success">Сохранить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
</div>';

$modal = Modal::begin([
    'id' => 'fcoUpdateModal',
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => false,
    ],
    'options' => [
        'tabindex' => false,
    ],
    'headerOptions' => ['id' => 'fcoUpdateModalHeader'],
    'footerOptions' => ['id' => 'fcoUpdateModalFooter'],
    'header' => $header,
    'footer' => $footer,
]);

$modal::end();

// CSS
$this->registerCss('
    #fcoUpdateModal .modal-body {
        height: 720px;
        overflow: auto;
    }

    #fcoUpdateModal {
        overflow: hidden !important;
    }

    #fcoUpdateModal .modal-content {
        width: 760px;
    }
');

// JS
$script = <<<JS
    $('#fcoUpdateButton').on('click', function(e) {
        var form = $('#fcoUpdateForm');
        $('#realUpdateSubmitFlag').attr('value', 1);

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
                    $('#fcoUpdateModal .modal-body').html(result);
                }
            }
        });

        $('#realUpdateSubmitFlag').attr('value', 0);

        return false;
    });

    $('#fcoUpdateModal').on('shown.bs.modal', function () {
        $('body').css('overflow', 'hidden');
    })

    $('#fcoUpdateModal').on('hide.bs.modal', function () {
        $('body').css('overflow', 'auto');
    })
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>