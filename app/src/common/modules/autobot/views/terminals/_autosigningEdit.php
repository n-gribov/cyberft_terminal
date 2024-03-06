<?php
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$model = $data['model'];
$primaryAutobotName = $data['primaryAutobotName'];
$autobots = $data['autobots'];
$isTerminalRunning = $data['isTerminalRunning'];
?>
<?php if ($isTerminalRunning) : ?>
    <div class="alert alert-danger">
        <?= Yii::t('app/terminal', 'Updating participant settings is not possible while automatic processing for terminal "{terminalId} is running', ['terminalId' => $model->terminal_id]) ?>
    </div>
<?php endif ?>

<?php if (count($autobots)) :?>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'terminal_id')->hiddenInput(['value'=>$model->terminal_id])->label(false) ?>
    <?= $form->field($model, 'participant_id')->hiddenInput(['value'=>$model->participant_id])->label(false) ?>
    <?= $form->field($model, 'update')->hiddenInput(['value'=>'1'])->label(false) ?>
    <div class="row">
        <div class="col-sm-6">
            <table class="table">
                <thead>
                <tr>
                    <th><?= Yii::t('app/autobot', 'Controller') ?> терминала "<?= $model->terminal_id ?>"</th>
                    <th><?= Yii::t('app/terminal', 'Signature required') ?></th>
                </tr>
                </thead>
                <tr>
                    <td>
                        <?= $primaryAutobotName ?>
                    </td>
                    <td>
                        <input type="checkbox" name="primary" value="1" checked disabled>
                    </td>
                </tr>
                <?php foreach($autobots as $autobot) { ?>
                    <tr>
                        <td>
                            <?= $autobot['name'] ?>
                        </td>
                        <td>
                            <input type="checkbox" name="autobots[]" value="<?= $autobot['id'] ?>" <?= $autobot['status'] ?>>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <?php if (!$isTerminalRunning) : ?>
        <div class="row">
            <div class="col-sm-2">
                <?=Html::submitButton(Yii::t('app', 'Save'), ['name' => 'save', 'class' => 'btn btn-primary btn-block']) ?>
            </div>
        </div>
    <?php endif ?>
    <?php ActiveForm::end()?>
<?php endif ?>