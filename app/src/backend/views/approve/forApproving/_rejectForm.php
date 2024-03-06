<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;

/* @var integer $commandId Command ID */
/* @var common\models\form\CommandRejectForm $rejectModel Reject form model */

Modal::begin([
    'header' => '<h2>'.\Yii::t('app', 'Reject').'</h2>',
    'toggleButton' => [
        'tag' => 'button',
        'class' => 'btn btn-danger',
        'label' => \Yii::t('app', 'Reject'),
    ]
]);

?>

<?php $form = ActiveForm::begin(['action' => '/approve/reject/']); ?>

<?= Html::hiddenInput('commandId', $commandId); ?>
<?= $form->field($rejectModel, 'reason')->input('text'); ?>
<div class="form-group">
	<?= Html::submitButton(\Yii::t('app', 'Reject'), ['class' => 'btn btn-warning']); ?>
</div>


<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>