<?php

use common\helpers\Html;
use common\widgets\documents\SignDocumentsButton;
use yii\bootstrap\Modal;
use yii\web\JsExpression;use yii\web\View;

$header = '<h4 class="modal-title" id="myModalLabel">Валютная операция</h4>';

$updateButton = Html::a(
    Yii::t('app', 'Update'),
    '#',
    [
        'class' => 'btn btn-success',
        'id' => 'fcoViewUpdateButton',
        'data' => [
            'id' => '1',
            'type' => ''
        ]
    ]
);

$deleteButton = Html::a(
    Yii::t('app', 'Delete'),
    '#',
    [
        'class' => 'btn btn-danger',
        'id' => 'fcoViewDeleteButton',
        'data' => [
            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]
);

$signButton = SignDocumentsButton::widget([
    'id' => 'fcoViewSignButton',
    'buttonText' => Yii::t('document', 'Sign'),
    'prepareDocumentsIds' => new JsExpression("function (signCallback) { signCallback([$('#fcoViewSignButton').data('document-id')]); }"),
    'alertsContainerSelector' => '#fcoViewModal .modal-body',
]);

$rejectSignButton = Html::a(
    Yii::t('edm', 'Reject signing'),
    '#',
    [
        'id' => 'rejectSignButton',
        'target' => '_blank',
        'class' => 'btn btn-warning',
    ]
);

$printButton = Html::a(
    Yii::t('app', 'Print'),
    '#',
    [
        'id' => 'fcoViewPrintButton',
        'target' => '_blank',
        'class' => 'btn btn-default pull-left print-link',
    ]
);

$footer = $printButton . $signButton . $rejectSignButton . $updateButton . $deleteButton;

$modal = Modal::begin([
    'id' => 'fcoViewModal',
//    'clientOptions' => [
//        'backdrop' => 'static',
//    ],
    'header' => $header,
    'footer' => $footer,
]);

$modal::end();

$this->registerCss('
    #fcoViewModal .modal-body {
        background-color: #fff;
    }

    #fcoViewModal {
    }

    #fcoViewModal .modal-dialog {
       width: 768px;
    }

    #fcoViewModal .modal-content {
        background-color: #eee;
    }

    #fcoViewModal .modal-header {
        border-bottom: 0;
    }

    #fcoViewModal .modal-footer {
        border-top: 0;
    }

');

// JS
$script = <<< JS

$('body').on(
    'click',
    '#fcoViewUpdateButton',
    function(e) {
        e.preventDefault();

        var id = $(this).data('id');
        var type = $(this).data('type');

        // Проверка на количество имеющихся подписей
        $.ajax({
            url: '/edm/foreign-currency-operation-wizard/get-document-signatures-count?id=' + id,
            type: 'get',
            success: function(result) {
                let confirmed = true;
                if (result.signaturesCount > 0) {
                    confirmed = confirm('Внимание! Документ подписан! ' +
                     'В случае изменения документа подписи будут автоматически отозваны! Редактировать документ?');
                }
                if (confirmed) {
                    $.ajax({
                        url: '/edm/foreign-currency-operation-wizard/update?id=' + id + '&type=' + type,
                        type: 'get',
                        success: function(answer) {
                            // Добавляем html содержимое на страницу формы
                            $('#fcoViewModal').modal('hide');

                            $('#fcoViewModal .modal-body').html('');
                            $('#fcoCreateModal .modal-body').html('');

                            $('#fcoUpdateModal .modal-body').html(answer);
                            $('#fcoUpdateModal').modal('show');
                        }
                    });
                }
            }
        });
    }
);

$('#rejectSignButton').click(
    function(e) {
        e.preventDefault();

        var id = $(this).data('id');

        $('#fcoViewModal').modal('hide');

        $('#fcoViewModal .modal-body').html('');
        $('#fcoCreateModal .modal-body').html('');
        $('#fcoUpdateModal .modal-body').html('');

        $('#fcoRejectSigningModalId').val(id);
        $('#fcoRejectSigningModal').modal('show');
});

    $('#fcoViewModal').on('shown.bs.modal', function () {
        $('body').css('overflow', 'hidden');
    });

    $('#fcoViewModal').on('hide.bs.modal', function () {
        $('body').css('overflow', 'auto');
    })

JS;

$this->registerJs($script, View::POS_READY);
?>
