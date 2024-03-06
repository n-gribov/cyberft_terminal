<?php

use yii\widgets\ActiveForm;
use common\helpers\Html;

/**
 * @param ActiveForm $form
 */
?>
<div class="col-lg-7">
    <?php
        echo Html::label($model->getAttributeLabel('notifyDays'));
        echo Html::textInput($model->formName() . '[notifyDays]', $model->getNotifyDays($terminalId), ['class' => 'form-control']);
    ?>
</div>
