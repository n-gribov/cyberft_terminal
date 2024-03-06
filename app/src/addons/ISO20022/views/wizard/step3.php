<?php

use addons\ISO20022\models\form\WizardForm;
use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $model WizardForm */

?>
<?= yii\widgets\DetailView::widget([
	'model' => $model,
	'template' => "<tr><th width='30%'>{label}</th><td>{value}</td></tr>",
	'attributes' => [
		'sender',
		'recipient',
		'subject',
		'descr',
        [
            'attribute' => 'file',
            'value' => $model->fileName
        ]
	]
]) ?>
<?php
	$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, 'fullSpan' => 12]);
    echo Html::hiddenInput('wizardComplete', '1');
?>

<div class="form-group row">
    <?php if (isset($message)) { ?>
        <div id="Message" class="row">
            <div class="col-sm-12"><?= $message ?></div>
        </div><br>
    <?php } ?>

    <div class="col-sm-offset-2 col-sm-10 pull-right">
        <?=Html::hiddenInput('wizardComplete', 1)?>
        <?php
            echo Html::a(Yii::t('app', 'Back'), ['/ISO20022/wizard/step2'], ['name' => 'send', 'class' => 'btn btn-default']) . ' ';
            echo Html::submitButton(Yii::t('app', 'Confirm'), ['name' => 'send', 'class' => 'btn btn-primary']);
        ?>
    </div>

</div>

<?php
	ActiveForm::end()
?>
