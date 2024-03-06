<?php

use common\models\form\LoginKeyForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model LoginKeyForm */

$this->title = Yii::t('app', 'Login with key');
?>

<h4 class="text-center"><?= Yii::t('app', 'Login') ?></h4>

<?= \common\widgets\Alert::widget() ?>
<div id="cyber-sign-service-error" class="alert alert-info hidden">
    <?= Yii::t(
        'app',
        '<p>Signing service is not installed or is not running.</p><p><a href="http://download.cyberft.ru/CyberSignService/CyberSignService.zip" target="_blank">Download CyberSignService</a></p><p><a href="http://download.cyberft.ru/CyberSignService/CyberSignService_manual.pdf" target="_blank">Setup manual</a></p>'
    ) ?>
</div>

<?php $form	= ActiveForm::begin(['id' => 'login-form']); ?>
    <?= Html::button(Yii::t('app', 'Log in'), ['id' => 'login-button', 'class' => 'btn btn-primary btn-block']) ?>
    <?= Html::hiddenInput('cert-body', '', ['id' => 'cert', 'class' => 'form-control hidden', 'readonly' => 'true']) ?>
    <?= Html::hiddenInput('signature', '', ['id' => 'signature', 'class' => 'form-control hidden', 'readonly' => 'true']) ?>
    <div class="links text-center">
        <?= Html::a(
            Yii::t('app', 'Log in with password'),
            Url::to(['/site/login-password'])
        )
        ?>
    </div>
<?php ActiveForm::end(); ?>

<?php

$this->registerJsFile('https://localhost:45678/api.js');

$signingErrorMessage = json_encode(Yii::t('app', 'Authorization request has not been signed'));

$this->registerJs(<<<JS
function onSignError()
{
    BootstrapAlert.show('#login-form', 'danger', $signingErrorMessage);
    toggleLoginButton(true);
}

function toggleLoginButton(isEnabled) {
    $('#login-button').prop('disabled', !isEnabled);
}

function onSignComplete(data, parameters)
{
    $('#signature').val(data);

    // Hack for backward compatibility: if no cert body, then look for old version with fingerprint
    if (parameters.hasOwnProperty('cert_body')) {
        $('#cert').val(parameters.cert_body);
    } else {
        $('#cert').val(parameters.fingerprint);
    }
    $("#login-form").submit();
}

function startSigning()
{
    BootstrapAlert.removeAll('#login-form');
    toggleLoginButton(false);
    $.ajax({
        url: '/site/get-auth-passcode',
        cache: false,
        success: function(response) {
            if (response.passcode) {
                CyberFTClient.sign(CyberFTClient.Method.ANY, response.passcode, onSignComplete, onSignError);
            } else {
                toggleLoginButton(true);
            }
        },
        error: function() {
            toggleLoginButton(true);
        }
    });
}

$('#login-button').click(function() {
    if (typeof CyberFTClient !== 'undefined') {
        CyberFTClient.initialize();
        CyberFTClient.setHash('{$algorithm}');
        startSigning();
    } else {
        $('#cyber-sign-service-error').removeClass('hidden');
    }
});
JS
);
