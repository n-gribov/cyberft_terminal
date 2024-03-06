<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/** @var View $this */
/** @var array $data */
/** @var \common\settings\Sbbol2IntegrationSettings $settings */

$settings = $data['settings'];

?>

<?php $form = ActiveForm::begin() ?>

<div class="row">
    <div class="col-xs-4">
        <?= $form->field($settings, 'gatewayUrl') ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-4">
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']); ?>
        </div>
    </div>
</div>

<?= Html::hiddenInput('action', 'saveSettings') ?>

<?php ActiveForm::end() ?>

<hr>

<form action="<?= $settings->gatewayUrl ?>/sbbol2/connect" method="post">
<?= Html::button(
    Yii::t('app/settings', 'Register terminal in Sberbank'),
    [
        'id' => 'register-in-sberbank-button',
        'class' => 'btn btn-success',
        'disabled' => empty($settings->gatewayUrl),
    ]
) ?>
<?= Html::hiddenInput('request') ?>
</form>

<?php
$createRequestUrl = Url::to(['/sberbank-registration/create-request']);
$js = <<<JS
$('#register-in-sberbank-button').on('click', function() {
    var button = $(this);
    var form = button.closest('form');
    $(this).prop('disabled', true);
    $.ajax({
        url: '$createRequestUrl',
        type: 'POST',
        cache: false,
        data: {
            terminalId: {$data['terminalId']}
        },
        success: function (response) {
            console.log(response);
            if (response.success) {
                form.find('[name=request]').val(response.request);
                form.trigger('submit');
            } else if (response.errorMessage) {
                var containerSelector = '.well.well-content';
                BootstrapAlert.removeAll(containerSelector);
                BootstrapAlert.show(containerSelector, 'danger', response.errorMessage);
                button.prop('disabled', false);
            }
        },
        error: function () {
            $(this).prop('disabled', false);
        }
    });
})
JS;

$this->registerJs($js, View::POS_READY);
