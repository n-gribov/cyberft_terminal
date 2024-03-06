<?php

use addons\swiftfin\models\form\WizardForm;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\MaskedInput;
use common\widgets\Participants\ParticipantsWidget;

/* @var $model WizardForm */
/* @var $form ActiveForm */
/* @var $this View */


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
    <div class="row">
        <div class="col-sm-6">
            <?php $form = ActiveForm::begin([
                'type'       => ActiveForm::TYPE_HORIZONTAL,
                'fullSpan'   => 12,
                'formConfig' => [
                    'labelSpan' => 3
                ]
            ]) ?>

            <?= ParticipantsWidget::widget([
                'form' => $form,
                'model' => $model
            ]) ?>
            <?= $form->field($model, 'terminalCode')->dropDownList([],
                    [
                'id' => 'wizardform-terminal-code',
                'class' => 'form-control',
            ]) ?>

            <?php

                $contentTypeOptions = ['placeholder' => Yii::t('doc/mt', 'Select document type')];
                $contentTypeEvents = [];

                if (Yii::$app->cache->exists('swiftfin/wizard/doc-' . Yii::$app->session->id) && $model->contentType) {
                    $contentTypeOptions['class'] = 'content-type-change';
                    $contentTypeEvents['select2:selecting'] = "onSelecting";
                }

                if (Yii::$app->cache->exists('swiftfin/wizard/edit-' . Yii::$app->session->id)) {
                    $contentTypeOptions['disabled'] = 'disabled';
                }

                echo $form->field($model, 'contentType')->widget(Select2::classname(), [
                    'data'          => $model->getSupportedTypes(),
                    'options'       => $contentTypeOptions,
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'pluginEvents' => $contentTypeEvents
            ]);?>

            <?=$form->field($model, 'bankPriority')->widget(MaskedInput::className(), ['mask' => '****'])?>
            <div class="form-group">
                <div class="col-md-offset-3 col-sm-3">
                    <?=Html::submitButton(Yii::t('app', 'Next'), ['name'  => 'send',
                        'class' => 'btn btn-primary btn-block'])?>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end() ?>