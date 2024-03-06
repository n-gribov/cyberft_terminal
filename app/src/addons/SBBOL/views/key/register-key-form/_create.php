<?php

use addons\SBBOL\models\forms\RegisterKeyForm;
use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var RegisterKeyForm $model */
/** @var array $csrParams */

$form = ActiveForm::begin([
    'id'      => 'create-key-form',
    'action'  => 'create',
    'options' => ['enctype' => 'multipart/form-data'],
]);

$hiddenFieldOptions = ['template' => '{input}', 'options' => ['tag' => false]];
echo $form->field($model, 'keyOwnerId', $hiddenFieldOptions)->hiddenInput();
echo $form->field($model, 'email', $hiddenFieldOptions)->hiddenInput();
?>

<p>
    <?= Yii::t('app/sbbol', 'Please, create the key and upload key container, public key and certificate request') ?>
</p>

<label><?= Yii::t('app/sbbol', 'Keys generation parameters') ?></label>
<div class="well">
<?= Html::encode($csrParams['subject']) ?><br>
bicryptid=<?= Html::encode($csrParams['bicryptId']) ?><br>
exporteble=<?= Html::encode($csrParams['exportable']) ?>
</div>

<?= $form
    ->field($model, 'certificateRequestFile')
    ->widget(
        FileInput::className(),
        [
            'model' => $model,
            'attribute' => 'certificateRequestFile',
            'pluginOptions' => [
                'showPreview' => false,
                'showUpload' => false
            ]
        ]
    );
?>

<?= $form
    ->field($model, 'publicKeyFile')
    ->widget(
        FileInput::className(),
        [
            'model' => $model,
            'attribute' => 'publicKeyFile',
            'pluginOptions' => [
                'showPreview' => false,
                'showUpload' => false
            ]
        ]
    );
?>

<?= $form
    ->field($model, 'keyContainerZipFile')
    ->widget(
        FileInput::className(),
        [
            'model' => $model,
            'attribute' => 'keyContainerZipFile',
            'pluginOptions' => [
                'showPreview' => false,
                'showUpload' => false
            ]
        ]
    );
?>

<?= $form->field($model, 'keyPassword')->passwordInput() ?>

<hr>

<div class="text-right">
    <?= Html::submitButton(
        Yii::t('app/sbbol', 'Register'),
        [
            'class' => 'btn btn-success',
            'data' => [
                'loading-text' => '<i class="fa fa-spinner fa-spin"></i> ' . Yii::t('app/sbbol', 'Register'),
            ]
        ]
    ) ?>
</div>

<?php

ActiveForm::end();
