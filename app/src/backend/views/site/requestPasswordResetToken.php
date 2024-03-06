<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\form\PasswordResetRequestForm */

$this->title = Yii::t('app', 'Password reset request');
?>

<h4 class="text-center"><?= Yii::t('app', 'Password recovery') ?></h4>
<p class="text-center"><?= Yii::t('app', 'Please, enter your email address to receive<br/> email with password reset link') ?></p>

<?= \common\widgets\Alert::widget() ?>

<?php $form = ActiveForm::begin([
    'validateOnBlur' => false,
    'validateOnChange' => false,
    'fieldConfig' => ['template' => '{input}{label}'],
]); ?>
    <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Email']) ?>
    <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary btn-block']) ?>
    <div class="links text-center">
        <?= Html::a(
            Yii::t('app', 'Back'),
            Url::to(['/site/login-password'])
        )
        ?>
    </div>
<?php ActiveForm::end(); ?>
