<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $model \addons\raiffeisen\models\RaiffeisenCustomer */
/** @var $terminalAddressSelectOptions array */
/** @var $signatureTypeSelectOptions array */

?>

<?php $form = ActiveForm::begin(); ?>

<div class="form-group">
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'shortName')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'fullName')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'internationalName')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'propertyType')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'inn')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'kpp')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'ogrn')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'dateOgrn')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'countryCode')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'addressState')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'addressDistrict')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'addressSettlement')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'addressStreet')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'addressBuilding')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'addressBuildingBlock')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'addressApartment')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'isHoldingHead')->checkbox(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'holdingHeadId')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'login')->textInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
        <?= $form->field($model, 'password')->passwordInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'certificate')->textarea(['rows' => 10]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'privateKeyPassword')->passwordInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form
                ->field($model, 'signatureType')
                ->dropDownList($signatureTypeSelectOptions, ['prompt' => '-']);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form
                ->field($model, 'terminalAddress')
                ->dropDownList($terminalAddressSelectOptions, ['prompt' => '-']);
            ?>
        </div>
    </div>

    <?= Html::a(Yii::t('app', 'Back'), 'index', ['class' => 'btn btn-default']) ?>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end();
