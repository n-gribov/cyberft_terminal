<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $model \addons\raiffeisen\models\RaiffeisenCustomerAccount */
/** @var $currencySelectOptions array */

?>

<?php $form = ActiveForm::begin(); ?>

<div class="form-group">
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'number')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'bankBik')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'bankName')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'currencyCode')->dropDownList($currencySelectOptions); ?>
        </div>
    </div>
    <?= Html::a(
        Yii::t('app', 'Back'),
        ['/raiffeisen/customer/view', 'id' => $model->customerId],
        ['class' => 'btn btn-default'])
    ?>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end();
