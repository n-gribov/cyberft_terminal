<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;

/* @var integer $documentId Document ID */
/* @var DocumentCorrectionForm $correctionModel Document correction form model */

Modal::begin([
    'header' => '<h2>'.\Yii::t('doc', 'For correction').'</h2>',
    'toggleButton' => [
        'tag' => 'button',
        'class' => 'btn btn-warning',
        'label' => \Yii::t('doc', 'For correction'),
    ]
]);

?>

<?php $form = ActiveForm::begin(['action' => 'correction']); ?>

<?= Html::hiddenInput('documentId', $documentId); ?>
<?= $form->field($correctionModel, 'correctionReason')->input('text'); ?>
<div class="form-group">
	<?= Html::submitButton(\Yii::t('doc', 'For correction'), ['class' => 'btn btn-warning']); ?>
</div>


<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>