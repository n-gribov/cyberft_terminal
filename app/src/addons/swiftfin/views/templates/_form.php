<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\MaskedInput;
use common\widgets\Participants\ParticipantsWidget;

/* @var $this yii\web\View */
/* @var $model addons\swiftfin\models\SwiftfinTemplate */
/* @var $docTypes addons\swiftfin\models\form\WizardForm */
/* @var $form yii\widgets\ActiveForm */


$this->registerJs(<<<JS

    var onSelecting = function(e)
    {
        var conf = confirm('При изменении типа документа все введенные данные будут потеряны! Вы желаете продолжить?');

        if (!conf) {
            e.preventDefault();
        } else {
            $.ajax('/swiftfin/wizard/clear-wizard-cache');
        }

    };

JS
    , View::POS_READY);
?>

<?php $form = ActiveForm::begin([
    'method'	=> 'post',
    'type' 		=> ActiveForm::TYPE_HORIZONTAL
]); ?>

<div class="row">
    <div class="col-sm-6">

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?=ParticipantsWidget::widget([
            'form' => $form,
            'model' => $model
        ])?>

        <?=$form->field($model, 'terminalCode')->dropDownList([], [
            'id'    => 'wizardform-terminal-code',
            'class' => 'form-control',
        ]);?>
        <?=$form->field($model, 'docType')->widget(Select2::classname(), [
            'data'          => $docTypes,
            'options'       => ['placeholder' => Yii::t('doc/mt', 'Select document type')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);?>
        <?=$form->field($model, 'bankPriority')->widget(MaskedInput::className(), ['mask' => '****'])?>
        <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
