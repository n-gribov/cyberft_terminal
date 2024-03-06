<?php

use common\modules\autobot\models\ProxySettingsForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model \common\modules\autobot\models\ProxySettingsForm */
$model = $data['settingsForm'];

$form = ActiveForm::begin();
?>

<div class="row">
    <div class="col-xs-6">
        <?= $form->field($model, 'protocol')->dropDownList(ProxySettingsForm::getProtocolLabels()) ?>
        <?= $form->field($model, 'host') ?>
        <?= $form->field($model, 'port') ?>
        <?= $form->field($model, 'login') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end();
