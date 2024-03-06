<?php

use addons\raiffeisen\settings\RaiffeisenSettings;
use kartik\widgets\ActiveForm;
use kartik\widgets\TimePicker;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var RaiffeisenSettings $settings */

?>

<div class="panel panel-body">
    <?php $form = ActiveForm::begin() ?>

        <h4><?= Yii::t('app/raiffeisen', 'Incoming documents request') ?></h4>
        <div class="row">
            <div class="col-sm-10 request-schedule-settings">
                <?= $form->field($settings, 'requestIncomingDocumentsTimeFrom')->widget(TimePicker::class, [
                    'pluginOptions' => [
                        'showSeconds'  => false,
                        'showMeridian' => false,
                        'minuteStep'   => 60,
                    ],
                    'pluginEvents' => [
                        'changeTime.timepicker' => <<<JS
                            function (e) {
                                if (e.time.minutes !== 0) {
                                    $(e.target).timepicker('setTime', e.time.hours + ':00')
                                }
                            }
                        JS
                    ]
                ]) ?>
                <?= $form->field($settings, 'requestIncomingDocumentsTimeTo')->widget(TimePicker::class, [
                    'pluginOptions' => [
                        'showSeconds'  => false,
                        'showMeridian' => false,
                        'minuteStep'   => 1,
                    ]
                ]) ?>
                <?= $form
                    ->field($settings, 'requestIncomingDocumentsInterval')
                    ->dropDownList($settings->getIncomingDocumentsRequestIntervalOptions())
               ?>
            </div>
        </div>

        <h4><?= Yii::t('app/raiffeisen', 'Documents status request') ?></h4>
        <div class="row">
            <div class="col-sm-10 request-schedule-settings">
                <?= $form->field($settings, 'processAsyncRequestsInterval')->widget(TouchSpin::class, [
                    'pluginOptions' => [
                        'verticalbuttons' => true,
                    ]
                ]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2">
                <?=Html::submitButton(Yii::t('app', 'Save'), ['name' => 'save', 'class' => 'btn btn-primary btn-block']) ?>
            </div>
        </div>

    <?php ActiveForm::end()?>
</div>

<?php
$this->registerCss('
    .request-schedule-settings {
        margin-bottom: 25px;
    }
    .request-schedule-settings .form-group {
        max-width: 150px;
    }
');
