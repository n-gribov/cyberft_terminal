<?php

use addons\sbbol2\settings\Sbbol2Settings;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/** @var Sbbol2Settings $settings */

?>

<div class="panel panel-body">
    <?php $form = ActiveForm::begin() ?>

    <h4><?= Yii::t('app/sbbol2', 'Sberbank API') ?></h4>

    <div class="panel panel-default">
        <div class="panel-body">
            <p>Сертификаты и ключ TLS необходимо выложить на сервер вручную:</p>
            <ul>
                <li>
                    <strong>Клиентский сертификат:</strong>
                    <?= Html::encode($settings->tlsClientCertificatePath) ?>
                </li>
                <li>
                    <strong>Клиентский ключ:</strong>
                    <?= Html::encode($settings->tlsKeyPath) ?>
                </li>
                <li>
                    <strong>Корневые и CA сертификаты:</strong>
                    <?= Html::encode($settings->tlsCaBundlePath) ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($settings, 'apiUrl')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($settings, 'tlsKeyPassword')->passwordInput() ?>
        </div>
    </div>

    <hr>
    <h4><?= Yii::t('app/sbbol2', 'Sberbank client authorization service') ?></h4>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($settings, 'authorizationUrl')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($settings, 'authorizationApiUrl')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($settings, 'apiClientId')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($settings, 'apiClientSecret')->passwordInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($settings, 'authorizationPartnerScope')->textInput() ?>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-2">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['name' => 'save', 'class' => 'btn btn-primary btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end()?>

</div>
