<?php

use common\models\Terminal;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Terminalorm yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Create');
?>
    <div class="terminal-form">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'terminalId')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'status')->dropDownList(Terminal::getstatusLabels()) ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php

$script = <<<JS
    deprecateSpaceSymbol('#terminal-title');
JS;

$this->registerJs($script, yii\web\View::POS_READY);
