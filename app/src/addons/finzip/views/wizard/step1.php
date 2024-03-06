<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use common\widgets\Participants\ParticipantsWidget;

?>

<div class="row">
    <div class="col-sm-6">
        <?php $form = ActiveForm::begin([
            'type'       => ActiveForm::TYPE_HORIZONTAL,
            'fullSpan'   => 12,
            'formConfig' => [
                'labelSpan' => 3
            ]
        ]) ?>
        <?=ParticipantsWidget::widget([
            'form' => $form,
            'model' => $model
        ])?>
        <?=$form->field($model, 'terminalCode')->dropDownList([], [
            'id'    => 'wizardform-terminal-code',
            'class' => 'form-control',
        ]);?>
        <div class="form-group">
            <div class="col-md-offset-3 col-sm-3">
                <?=Html::submitButton(Yii::t('app', 'Next'), ['name'  => 'send',
                                                              'class' => 'btn btn-primary btn-block'])?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>