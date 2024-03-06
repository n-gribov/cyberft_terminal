<?php

use common\document\DocumentFormatGroup;
use common\helpers\Html;
use common\modules\participant\models\BICDirParticipant;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model BICDirParticipant */

$this->title = $model->participantBIC;
?>

<?php $form = ActiveForm::begin(); ?>

<div class="form-group">
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'participantBIC')->textInput(['readonly' => true]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'name')->textInput(['readonly' => true]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?=
            $form
                ->field($model, 'documentFormatGroup')
                ->dropDownList(DocumentFormatGroup::getAll(), ['prompt' => '-']);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?=
            $form->field($model, 'maxAttachmentSize')->textInput();
            ?>
        </div>
    </div>

    <?= Html::a(Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-default']) ?>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
