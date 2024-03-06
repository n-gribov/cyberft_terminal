<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;

/** @var \yii\web\View $this */

Modal::begin([
    'id' => 'register-key-modal',
    'header' => Html::tag('h4', Yii::t('app/sbbol', 'Key registration')),
    'options' => [
        'data' => [
            'backdrop' => 'static',
        ],
    ],
]);

Modal::end();

$this->registerJsFile(
    '/js/sbbol/key/register-key-form.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJs('RegisterKeyForm.initialize()');

$this->registerCss('
#register-key-modal hr {
    margin: 15px 0;
}
#register-key-modal .well {
    font-size: 12px;
    background-color: #f9f9f9;
}
');
