<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/** @var \yii\web\View $this */

Modal::begin([
    'id' => 'register-holding-modal',
    'header' => Html::tag('h4', Yii::t('app/sbbol', 'Holding registration')),
    'footer' => Html::button(
        Yii::t('app/sbbol', 'Register'),
        [
            'class' => 'btn btn-success submit-button',
            'data' => [
                'loading-text' => '<i class="fa fa-spinner fa-spin"></i> ' . Yii::t('app/sbbol', 'Register'),
            ]
        ]
    ),
    'options' => [
        'data' => [
            'backdrop' => 'static',
        ],
    ],
]);

$model = new \addons\SBBOL\models\forms\RegisterHoldingForm();

$form = ActiveForm::begin();

echo $form->field($model, 'login');
echo $form->field($model, 'password')->passwordInput();
echo $form->field($model, 'senderName');

ActiveForm::end();
Modal::end();

$this->registerJsFile(
    '/js/sbbol/organization/register-holding-form.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJs('RegisterHoldingForm.initialize()');
