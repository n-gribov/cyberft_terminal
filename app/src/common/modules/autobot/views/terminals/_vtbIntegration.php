<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var array $data */

/** @var \common\settings\VTBIntegrationSettings $settings */
$settings = $data['settings'];

?>

<?php $form = ActiveForm::begin() ?>

<div class="row">
    <div class="col-xs-4">
        <?= $form->field($settings, 'enableCryptoProSign')->checkbox() ?>
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

<h4><?= Yii::t('app/settings', 'The keys for signing outgoing documents') ?></h4>

<?php $form = ActiveForm::begin() ?>
<?= $this->render(
    '@backend/views/cryptopro-keys/_addonsKeysSettings',
    [
        'cryptoproKeys'       => $data['cryptoproKeys'],
        'cryptoproKeysSearch' => $data['cryptoproKeysSearch'],
    ]
) ?>
<?php ActiveForm::end() ?>

<?= $this->render('@backend/views/cryptopro-keys/_keyUploadModal') ?>
