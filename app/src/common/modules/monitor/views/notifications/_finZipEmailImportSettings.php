<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var $this View **/

/** @var \addons\finzip\settings\FinZipSettings **/
$model = $data['model'];

$senderTerminalsSelectOptions = $data['senderTerminalsSelectOptions'];
$receiverTerminalsSelectOptions = $data['receiverTerminalsSelectOptions'];

?>
<?php $form = ActiveForm::begin(); ?>
<h3><?= Yii::t('app/finzip', 'Email import settings') ?></h3>

<?= $form->field($model, 'enableImportFromEmailServer')->checkbox() ?>

<div id="email-server-settings" <?= $model->enableImportFromEmailServer ? '' : 'class="hidden"' ?>>
    <?= $form
        ->field($model, 'emailImportSenderTerminalAddress')
        ->dropDownList($senderTerminalsSelectOptions, ['prompt' => '-'])
        ->label(Yii::t('app/finzip', 'Send incoming documents on behalf of terminal'))
    ?>
    <?= $form
        ->field($model, 'emailImportReceiverTerminalAddress')
        ->dropDownList($receiverTerminalsSelectOptions, ['prompt' => '-'])
        ->label(Yii::t('app/finzip', 'To terminal'))
    ?>

    <h4><?= Yii::t('app/finzip', 'Email server settings') ?></h4>

    <?= $form->field($model, 'emailImportServerHost')->label(Yii::t('app/finzip', 'Host')) ?>
    <?= $form->field($model, 'emailImportServerPort')->label(Yii::t('app/finzip', 'Port')) ?>
    <?= $form->field($model, 'emailImportServerLogin')->label(Yii::t('app/finzip', 'Login')) ?>
    <?= $form->field($model, 'emailImportServerPassword')->passwordInput()->label(Yii::t('app/finzip', 'Password')) ?>
</div>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
$('#finzipsettings-enableimportfromemailserver').on('click change', function() {
    $('#email-server-settings').toggleClass('hidden', !$(this).is(':checked'));
});
JS
);
