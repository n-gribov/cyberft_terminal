<?php

use addons\edm\models\BankLetter\IsoMessageTypeCodes;
use addons\edm\models\BankLetter\VtbMessageTypeCodes;
use addons\edm\models\DictBank;
use common\helpers\vtb\VTBHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \addons\edm\models\BankLetter\BankLetterForm $model */

?>
    <div id="form-modal" class="fade modal" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?= Yii::t('edm', 'Letter to bank') ?></h4>
                </div>
                <div class="modal-body">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'bank-letter-form',
                        'validationUrl' => Url::to(['/edm/bank-letter/validate']),
                    ]);

                    $senderOptions = ArrayHelper::map($model->getAvailableSenderTerminals(), 'id', 'screenName');
                    $receiverBankOptions = ArrayHelper::map($model->getAvailableReceiverBanks(), 'bik', 'name');

                    echo $form
                        ->field($model, 'senderTerminalId')
                        ->dropDownList($senderOptions, ['prompt' => '-'])
                        ->label(Yii::t('edm', 'From'));

                    echo $form
                        ->field($model, 'receiverBankBik')
                        ->dropDownList($receiverBankOptions, ['prompt' => '-'])
                        ->label(Yii::t('edm', 'Send to bank'));

                    echo $form
                        ->field($model, 'isoMessageTypeCode', ['options' => ['class' => 'hidden'], 'enableAjaxValidation' => true, 'enableClientValidation' => false])
                        ->dropDownList(IsoMessageTypeCodes::all());

                    echo $form
                        ->field($model, 'vtbMessageTypeCode', ['options' => ['class' => 'hidden'], 'enableAjaxValidation' => true, 'enableClientValidation' => false])
                        ->dropDownList(VtbMessageTypeCodes::all(), ['prompt' => '-']);

                    echo $form->field($model, 'subject', ['enableAjaxValidation' => true]);
                    echo $form
                        ->field($model, 'message', ['enableAjaxValidation' => true])
                        ->textarea(['rows' => 5]);

                    echo $form->field($model, 'documentId', ['template' => '{input}{error}'])->hiddenInput();

                    /**
                     * @todo унифицировать вью для аттачмент филес (уже 3 шт. копипасты накопилось)
                     */
                    ?>
                    <h4><?= Yii::t('edm', 'Attached files') ?></h4>
                    <?php
                    echo $this->render('_attachedFilesGridView', ['models' => $model->attachedFiles]);

                    echo $form->field($model, 'attachedFilesJson', ['template' => '{input}{error}'])->hiddenInput();

                    echo Html::button(
                        Yii::t('app', 'Add'),
                        [
                            'id' => 'add-attached-file-button',
                            'class' => 'btn btn-primary',
                            'data' => ['loading-text' => '<i class=\'fa fa-spinner fa-spin\'></i> ' . Yii::t('app', 'Add')],
                            //'disabled' => true
                        ]
                    );
                    ActiveForm::end();
                    ?>
                    <form action="/edm/bank-letter/upload-attached-file" id="upload-attached-file-form" enctype="multipart/form-data">
                        <input type="file" name="file" class="hidden"/>
                    </form>
                </div>
                <div class="modal-footer">
                    <div>
                        <?= Html::button(
                            Yii::t('app', 'Save'),
                            [
                                'id' => 'create-button',
                                'class' => 'btn btn-success',
                                'data' => [
                                    'loading-text' => '<i class="fa fa-spinner fa-spin"></i> ' . Yii::t('app', 'Create'),
                                ]
                            ]
                        ) ?>
                        <?= Html::button(
                            Yii::t('app', 'Cancel'),
                            [
                                'id' => 'cancel-button',
                                'class' => 'btn btn-default',
                                'data' => [
                                    'dismiss' => 'modal',
                                ]
                            ]
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

$this->registerJsFile(
    '@web/js/edm/bank-letter/bank-letter-form.js',
    ['depends' => [yii\web\JqueryAsset::className()]]
);

$vtbTerminalId = VTBHelper::getGatewayTerminalAddress();
$vtbBiks = array_reduce(
    $model->getAvailableReceiverBanks(),
    function ($carry, DictBank $bank) use ($vtbTerminalId) {
        if (!empty($vtbTerminalId) && $bank->terminalId === $vtbTerminalId) {
            $carry[] = $bank->bik;
        }
        return $carry;
    },
    []
);
$vtbBiksJson = json_encode($vtbBiks);
$maxAttachmentsCountsByBiks = json_encode($model->getMaxAttachmentsCountsByBiks());

$js = <<<JS
    function toggleActionButtonsState (enabled) {
        $('#create-button').button(enabled ? 'reset' : 'loading');
        $('#cancel-button').prop('disabled', !enabled);
    }

    function submitForm(form) {
        var formData = new FormData(form.get(0));
        var documentId = formData.get('BankLetterForm[documentId]');
        $.ajax({
            url: documentId ? '/edm/bank-letter/update' : '/edm/bank-letter/create',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response && response.validationErrors) {
                    form.yiiActiveForm('updateMessages', response.validationErrors, true);
                    toggleActionButtonsState(true);
                }
            },
            beforeSend: function () {
                toggleActionButtonsState(false);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status != 302) {
                    toggleActionButtonsState(true);
                }
            }
        });
    }

    function isVtbBik (bik) {
        return $vtbBiksJson.indexOf(bik) >= 0;
    }

    function getMaxAttachmentsCount(bik) {
        var maxCountsByBiks = $maxAttachmentsCountsByBiks;
        return maxCountsByBiks[bik] || null;
    }

    function triggerAttributeValidation(attributeId) {
        var form = $('#bank-letter-form');
        var value = form.yiiActiveForm('find', attributeId).value;
        if (value !== '') {
            form.yiiActiveForm('validateAttribute', attributeId);
        }
    }

    function handleReceiverChange() {
        var receiverBik = $('#bankletterform-receiverbankbik').val();

        $('.field-bankletterform-isomessagetypecode').toggleClass('hidden', isVtbBik(receiverBik));
        $('.field-bankletterform-vtbmessagetypecode').toggleClass('hidden', !isVtbBik(receiverBik));
        $('#upload-attached-file-form').trigger('update-max-attachments-count', [getMaxAttachmentsCount(receiverBik)])

        triggerAttributeValidation('bankletterform-subject');
        triggerAttributeValidation('bankletterform-message');
        triggerAttributeValidation('bankletterform-attachedfilesjson');
    }

    var form = $('#bank-letter-form');

    form.on('beforeSubmit', function () {
        submitForm(form);
        return false;
    });
    $('#create-button').click(function () {
        form.trigger('submit');
    });
    $('#form-modal').on('hide.bs.modal', function () {
        form.trigger('reset');
        handleReceiverChange();
    });
    $('#bankletterform-receiverbankbik').change(handleReceiverChange);
    $('#bankletterform-attachedfilesjson').change(function () {
        triggerAttributeValidation('bankletterform-attachedfilesjson');
    });

    handleReceiverChange();
JS;

$this->registerJs($js, yii\web\View::POS_READY);
