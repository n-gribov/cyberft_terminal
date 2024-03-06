<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \addons\Sbbol2\models\Sbbol2Organization */
/** @var $terminalAddressSelectOptions array */

$this->title = Yii::t('app/sbbol', 'Edit organization');

?>


<?php $form = ActiveForm::begin(); ?>

<div class="form-group">
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

<?php ActiveForm::end(); ?>
