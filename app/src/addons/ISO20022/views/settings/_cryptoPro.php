<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use common\widgets\AdvancedTabs;

// Настройки вложенных табов
$data = [
    'action' => 'subTabMode',
    'defaultTab' => 'subTabCryptoProKeys',
    'tabs' => [
        'subTabCryptoProKeys' => [
            'label' => Yii::t('app/settings', 'The keys for signing outgoing documents'),
            'content' => '@backend/views/cryptopro-keys/_addonsKeysSettings',
        ],
        'subTabCryptoProCerts' => [
            'label' => Yii::t('app/settings', 'Certificates for verification of incoming documents'),
            'content' => '@addons/ISO20022/views/settings/_cryptoProCerts',
        ],
    ],
];
?>
<div class="panel panel-body">
    <?php $form = ActiveForm::begin() ?>
        <div class="row">
            <div class="col-sm-4">
                <?=$form->field($settings, 'enableCryptoProSign', ['options' => ['class' => 'col-xs-12']])->checkbox()?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <?=$form->field($settings, 'useCompatibleSigning', ['options' => ['class' => 'col-xs-12']])->checkbox()?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <?=$form->field($settings, 'useSerial', ['options' => ['class' => 'col-xs-12']])->checkbox()?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <?=Html::submitButton(Yii::t('app', 'Save'), ['name' => 'save', 'class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <hr/>

            <?= AdvancedTabs::widget([
                'data' => $data,
                'notFoundTabContent' => '<div class="alert alert-danger" style="margin-top:20px">'.Yii::t('app/error', 'The requested page could not be found.').'</div>',
                'params' => [
                    'cryptoproKeys' => $cryptoproKeys,
                    'cryptoproKeysSearch' => $cryptoproKeysSearch,
                    'cryptoproCert' => $cryptoproCert,
                    'cryptoproCertSearch' => $cryptoproCertSearch,
                ]
            ]) ?>

    <?php ActiveForm::end()?>
</div>

<?= $this->render('@backend/views/cryptopro-keys/_keyUploadModal') ?>
