<?php
use common\modules\certManager\models\Cert;
use yii\web\View;
?>
<div class="modal fade" id="deactivateCertModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 id="deactivate-modal-title"></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-success btn-deactivate-cert">Выполнить</button>
            </div>
        </div>
    </div>
</div>
<?php
    $botRole = Cert::ROLE_SIGNER_BOT;
    $script = <<<JS
    $('#$buttonId').on('click', function(e) {
        e.preventDefault();
        var role = getModelRole();
        if (role == '$botRole') {
           // Проверка наличия сертификата контролера для данного терминала
            $.get(
                '/certManager/cert/check-controller-cert-by-terminal',
                { terminalId: '$model->terminalId', fingerprint: '$model->fingerprint' }
            ).done(
                function(data)
                {
                    if (data.hasOwnProperty('terminalId'))
                    {
                        var modalText =
                        '<p class="warning-title">Внимание!</p><p>Для Терминала <strong>' + data.terminalId
                        + '</strong> организации <strong>' + data.participantName
                        + '</strong> существует активный сертификат с ролью Контролер:</p><hr><p>Владелец: <strong>' + data.fullName
                        + '</strong><br>Отпечаток: <strong>' + data.fingerprint
                        + '</strong><br> Срок действия: <strong>' + data.useBefore
                        + '</strong></p><hr><p>Заблокировать существующий сертификат и активировать новый?</p>';
                        $('#deactivate-modal-title').html(modalText);
                        $('#deactivateCertModal').modal('show');
                    } else {
                        deactivateCertModalConfirm();
                    }
                }
            );
        } else {
            deactivateCertModalConfirm();
        }
    });

    $('.btn-deactivate-cert').on('click',
        function(e) {
            $('#deactivateCertModal').modal('hide');
            deactivateCertModalConfirm();
        }
    );
JS;

$this->registerJs($script, View::POS_READY);

$this->registerCss('
    .warning-title {
        font-size: 24px;
        margin-bottom: 15px;
    }

    .modal-title {
        color: #000;
        font-size: 16px;
    }
');
?>