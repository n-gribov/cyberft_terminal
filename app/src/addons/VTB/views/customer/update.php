<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \addons\VTB\models\VTBCustomer */

$this->title = Yii::t('app/vtb', 'Edit customer');

?>


<?php $form = ActiveForm::begin(); ?>

<div class="form-group">
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'customerId')->textInput(['readonly' => true]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'fullName')->textInput(['readonly' => true]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'inn')->textInput(['readonly' => true]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'terminalId')->textInput(); ?>
        </div>
    </div>

    <?= Html::a(Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-default']) ?>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
