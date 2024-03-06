<?php

use common\modules\autobot\forms\CreateAutobotForm;
use common\widgets\Alert;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/** @var CreateAutobotForm $model */

$form = ActiveForm::begin(['options' => ['data' => ['submit-modal' => true]]]);
?>

<div class="fade modal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app/autobot', 'Create key') ?></h4>
            </div>
            <div class="modal-body">
                <?= Alert::widget() ?>
                <?php
                echo $form->field($model, 'password')->passwordInput();
                echo $form->field($model, 'passwordConfirmation')->passwordInput();
                ?>
            </div>
            <div class="modal-footer">
                <div>
                    <?= Html::submitButton(
                        Yii::t('app', 'Create'),
                        [
                            'class' => 'btn btn-success',
                            'data' => ['disable-on-submit' => true],
                        ]
                    ) ?>
                    <?= Html::button(
                        Yii::t('app', 'Cancel'),
                        [
                            'class' => 'btn btn-default',
                            'data' => ['dismiss' => 'modal']
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
ActiveForm::end();