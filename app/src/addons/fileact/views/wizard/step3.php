<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

// Создать детализированное представление
echo yii\widgets\DetailView::widget([
    'model' => $model,
    'template' => "<tr><th width='30%'>{label}</th><td>{value}</td></tr>",
    'attributes' => [
        'sender',
        'recipient',
    ]
]);

$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, 'fullSpan' => 12])
?>

<div class="panel-body">
    <div class="form-group row">
        <?php if (isset($message)) : ?>
            <div id="Message" class="row">
                <div class="col-sm-12"><?= $message ?></div>
            </div><br>
        <?php endif ?>
        <div class="col-sm-offset-2 col-sm-10 pull-right">
            <?php
                echo Html::a(Yii::t('app', 'Back'), ['/fileact/wizard/step2'], ['name' => 'back', 'class' => 'btn btn-default']) . ' ';
                echo Html::submitButton(Yii::t('app', 'Confirm'), ['name' => 'send', 'class' => 'btn btn-primary']);
            ?>
        </div>
    </div>
</div>

<?php
ActiveForm::end();
