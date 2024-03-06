<?php

use yii\widgets\ActiveForm;
use common\helpers\Html;

/**
 * @param ActiveForm $form
 */
?>
<div class="col-lg-7">
    <?php
        echo Html::label($model->getAttributeLabel('deliveryDays'));
        echo Html::textInput($model->formName() . '[deliveryDays]', $model->getDeliveryDays($terminalId), ['class' => 'form-control']);
    ?>
</div>
