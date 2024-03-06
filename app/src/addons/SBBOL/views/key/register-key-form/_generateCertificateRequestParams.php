<?php

use addons\SBBOL\models\forms\RegisterKeyForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var RegisterKeyForm $model */

$form = ActiveForm::begin(['id' => 'generate-certificate-request-params-form']);

echo $form
    ->field($model, 'keyOwnerId')
    ->dropDownList(RegisterKeyForm::getKeyOwnerSelectOptions(), ['prompt' => '-']);
echo $form->field($model, 'email')->textInput();
?>

<hr>

<div class="text-right">
    <?= Html::submitButton(
        Yii::t('app/sbbol', 'Next'),
        ['class' => 'btn btn-success']
    ) ?>
</div>

<?php

ActiveForm::end();
