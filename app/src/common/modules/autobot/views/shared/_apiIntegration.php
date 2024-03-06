<?php

use common\settings\AppSettings;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/** @var AppSettings $settings */
/** @var string $enableCheckboxLabel */

?>

<?php $form = ActiveForm::begin(['id' => 'settings-form']); ?>

<?= $form
        ->field($settings, 'enableApi')
        ->checkbox(['label' => $enableCheckboxLabel])
?>

<?php if ($settings->enableApi): ?>
<div id="tokenSettings" class="row">
    <div class="col-xs-8">
        <?= $form->field($settings, 'apiAccessToken')->textarea(['readonly' => true, 'rows' => 5]) ?>
    </div>
    <div id="token-actions" class="col-xs-4">
        <button id="generate-token-button" class="btn btn-link" type="button" title="<?= Yii::t('app', 'Generate new token') ?>">
            <span class="glyphicon glyphicon-refresh"></span>
        </button>
        <button id="copy-token-button" class="btn btn-link" type="button" title="<?= Yii::t('app', 'Copy') ?>">
            <span class="glyphicon glyphicon-copy"></span>
        </button>
    </div>
</div>
<?php endif ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']); ?>
</div>

<?= Html::hiddenInput('generate-new-token', '0') ?>

<?php
ActiveForm::end(); 

$generateTokenConfirmMessage = Yii::t('app', 'When generating new token, it is necessary to change it in the external system, otherwise the authorization with the API will be impossible. Generate new token?');
$script = <<<JS
    $('body').on('click', '#generate-token-button', function(e) {
        e.preventDefault();
        if (confirm("$generateTokenConfirmMessage")) {
            $('input[name="generate-new-token"]').val('1');
            $('#settings-form').submit();
        }
    });
    $('body').on('click', '#copy-token-button', function(e) {
        e.preventDefault();
        var textArea = document.getElementById('appsettings-apiaccesstoken');
        textArea.select();
        document.execCommand('copy');
        textArea.setSelectionRange(0, 0);
    });
    
JS;

$this->registerJs($script, View::POS_READY);

$this->registerCss('
    #token-actions {
        margin-left: -20px;
        padding-top: 20px;
    }
    #token-actions .btn {
        color: #2b8f0e;
        display: block;
    }
');
