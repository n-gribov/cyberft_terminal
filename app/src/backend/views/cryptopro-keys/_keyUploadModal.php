<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/** @var \yii\web\View $this */
?>
<!-- Modal -->
<div class="modal fade" id="modalCryptoProCertUpload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?= Yii::t('app/settings', 'Add a new key')?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="msg-block alert"></div>
                <?php $form = ActiveForm::begin(['id' => 'cryptopro-keys-upload', 'options' => ['enctype' => 'multipart/form-data']]) ?>
                <div class="form-group">
                    <?=Html::label('Сертификат')?>
                    <?=Html::fileInput('upload[]', null, ['multiple' => 'multiple', 'class' => 'cryptopro-cert-upload'])?>
                </div>
                <div class="form-group">
                    <?=Html::label('Контейнер')?>
                    <?=Html::fileInput('upload[]', null, ['multiple' => 'multiple', 'class' => 'cryptopro-cert-upload'])?>
                </div>
                <?php ActiveForm::end()?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-form-submit"><?=Yii::t('app', 'Save')?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=Yii::t('app', 'Close')?></button>
            </div>
        </div>
    </div>
</div>
<?php
$script = <<<JS
    // Инициализация submit формы
    $('.btn-form-submit').on('click', function() {
        $('.msg-block').hide();
        $('.msg-block').removeClass('alert-danger');
        $('.msg-block').removeClass('alert-info');
        $('#cryptopro-keys-upload').submit();
    });

    // Загрузка сертификата и контейнера
    $('body').on('submit', '#cryptopro-keys-upload', function(e) {
       e.preventDefault();

       var data = new FormData();

       data.append('cert', $('input[type=file]')[0].files[0]);
       data.append('container', $('input[type=file]')[1].files[0]);

       $.ajax({
            url: '/cryptopro-keys/file-upload',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(res) {
                setTimeout(function() {}, 1000);
                if (res.status == 'processing') {

                    $('.msg-block').html(res.msg);
                    $('.msg-block').addClass('alert-info');
                    $('.msg-block').show();

                    var seconds = 0;

                    var checkInterval = setInterval(function() {
                      // ajax-запрос для получения статуса загрузки сертификата

                      seconds++;

                      $.ajax({
                        url: '/cryptopro-keys/get-file-upload-status',
                        type: 'get',
                        dataType: 'json',
                        success: function(answer) {
                            if (answer.status == 'error') {
                                $('.msg-block').html(answer.msg);
                                $('.msg-block').addClass('alert-danger');
                                $('.msg-block').show();
                                clearInterval(checkInterval);
                            }

                            // Если истекло допустимое время исполнения скрипта
                            if (seconds >= 20) {
                                $('.msg-block').html('Ошибка загрузки ключа');
                                $('.msg-block').addClass('alert-danger');
                                $('.msg-block').show();
                                clearInterval(checkInterval);
                            }
                        }
                      });
                    }, 1000);

                    //// Скрываем форму загрузки сертификата
                    //$('#modalCryptoProCertUpload').modal('hide');
                    //
                    //// Очищаем поле выбора файла формы
                    //$('.cryptopro-cert-upload').val('');
                } else if (res.status == 'error') {
                    $('.msg-block').html(res.msg);
                    $('.msg-block').addClass('alert-danger');
                    $('.msg-block').show();
                }
            }
        });
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);

$this->registerCss('
    .msg-block {
        display: none;
    }
');

?>
