<?php

$header = '<h4 class="modal-title" id="myModalLabel">Шаблон валютного платежа</h4>';
$footer = '<button type="button" class="btn btn-primary btn-submit-template">Сохранить</button><button type="button" class="btn btn-primary btn-submit-create-template">Сохранить и cоздать документ</button><button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>';

echo yii\bootstrap\Modal::widget([
    'id' => 'edmTemplateFCPModal',
    'header' => $header,
    'footer' => $footer,
    'options' => [
        'tabindex' => false
    ],
]);
