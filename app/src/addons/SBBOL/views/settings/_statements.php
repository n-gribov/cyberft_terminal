<?php

use addons\SBBOL\settings\SBBOLSettings;
use kartik\widgets\ActiveForm;
use kartik\widgets\TimePicker;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var SBBOLSettings $settings */

?>

<div class="panel panel-body">
    <?php $form = ActiveForm::begin() ?>

        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($settings, 'requestYesterdaysStatements', ['options' => ['class' => 'col-xs-12']])->checkbox() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($settings, 'requestTodaysStatements', ['options' => ['class' => 'col-xs-12']])->checkbox() ?>
            </div>
        </div>
        <div class="row">
            <div  id="request-schedule-settings" class="col-sm-10">
                <?= $form->field($settings, 'requestTodaysStatementsTimeFrom')->widget(TimePicker::class, [
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
                <?= $form->field($settings, 'requestTodaysStatementsTimeTo')->widget(TimePicker::class, [
                    'pluginOptions' => [
                        'showSeconds'  => false,
                        'showMeridian' => false,
                        'minuteStep'   => 1,
                    ]
                ]) ?>
                <?= $form
                    ->field($settings, 'requestTodaysStatementsInterval')
                    ->dropDownList($settings->getStatementREquestIntervalOptions())
                ?>
            </div>
        </div>
        <br/>
        <div class="form-group">
            <div class="col-sm-2">
                <?=Html::submitButton(Yii::t('app', 'Save'), ['name' => 'save', 'class' => 'btn btn-primary btn-block']) ?>
            </div>
        </div>

    <?php ActiveForm::end()?>
</div>

<?php
$this->registerCss('
    #request-schedule-settings {
        padding-left: 40px;
    }
    #request-schedule-settings .form-group {
        max-width: 150px;
    }
');

