<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$model = $data['model'];
$terminals = $data['terminals'];
$participants = $data['participants'];

?>

<?php $form = ActiveForm::begin([
    'fullSpan'   => 12,
    'formConfig' => [
        'labelSpan' => 3
    ]
]) ?>

<div class="row">
    <div class="col-sm-6">
        <?=Html::activeHiddenInput($model, 'terminal_id')?>
        <?=$form->field($model, 'participant_id')->dropDownList($participants, [
            'id'    => 'participant_id',
            'class' => 'form-control',
        ]);?>

    </div>
</div>
<div class="row">
    <div class="col-sm-offset-4 col-sm-2" style="margin-top:20px;">
        <?=Html::submitButton(Yii::t('app', 'Next'), ['name'  => 'send', 'class' => 'btn btn-primary btn-block'])?>
    </div>
</div>
<?php ActiveForm::end() ?>