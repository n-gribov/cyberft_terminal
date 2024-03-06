<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \common\models\form\LoginPasswordForm */

$this->title = Yii::t('app', 'Login with password');
?>

<h4 class="text-center"><?= Yii::t('app', 'Login') ?></h4>

<?= \common\widgets\Alert::widget() ?>

<?php $form = ActiveForm::begin([
    'validateOnBlur' => false,
    'validateOnChange' => false,
    'fieldConfig' => ['template' => '{input}{label}']
]); ?>
    <?= $form
        ->field($model, 'email')
        ->textInput([
            'autofocus' => true,
            'placeholder' => 'Email',
            'autocomplete' => 'username email',
        ]) ?>

    <?= $form
        ->field($model, 'password')
        ->passwordInput([
            'placeholder' => Yii::t('app', 'Password'),
            'autocomplete' => 'current-password',
            'class' => ($model->password === '' || $model->password === null) ? 'form-control placeholder-shown' : 'form-control',
        ]) ?>

    <?= Html::submitButton(Yii::t('app', 'Log in'), ['class' => 'btn btn-primary btn-block']) ?>

    <div class="links">
        <?= Html::a(
            Yii::t('app', 'Recover password'),
            Url::to(['/request-password-reset']),
            ['class' => 'pull-left']
        )
        ?>
        <?= Html::a(
            Yii::t('app', 'Login by key'),
            Url::to(['/site/login-key']),
            ['class' => 'pull-right']
        )
        ?>
    </div>
<?php ActiveForm::end();
