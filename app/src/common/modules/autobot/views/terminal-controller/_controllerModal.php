<?php

use common\models\Country;
use common\modules\autobot\forms\CreateAutobotForm;
use common\widgets\Alert;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/** @var CreateAutobotForm $model */
/** @var Country[] $countries */

$countryDropDownOptions = \yii\helpers\ArrayHelper::map($countries, 'alfa2Code', 'name');

$form = ActiveForm::begin(['options' => ['data' => ['submit-modal' => true]]]);
?>

<div class="fade modal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app/autobot', 'Create controller') ?></h4>
            </div>
            <div class="modal-body">
                <?= Alert::widget() ?>
                <?php
                echo $form->field($model, 'lastName');
                echo $form->field($model, 'firstName');
                echo $form->field($model, 'middleName');
                ?>
                <hr>
                <h4><?= Yii::t('app/autobot', 'Data for certificate') ?></h4>
                <?php
                echo $form->field($model, 'country')->widget(
                    Select2::class,
                    ['data' => $countryDropDownOptions]
                );
                echo $form->field($model, 'stateOrProvince');
                echo $form->field($model, 'locality');
                ?>
            </div>
            <div class="modal-footer">
                <div>
                    <?= Html::submitButton(
                        Yii::t('app', $model->isNewRecord ? 'Create' : 'Save'),
                        [
                            'class' => 'btn btn-success',
                            'data' => ['disable-on-submit' => true],
                        ]
                    ) ?>
                    <?= Html::button(
                        Yii::t('app', 'Cancel'),
                        [
                            'class' => 'btn btn-default',
                            'data' => ['dismiss' => 'modal'],
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
ActiveForm::end();
