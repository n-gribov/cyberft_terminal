<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

Modal::begin([
    'id' => 'fcoRejectSigningModal',
    'header' => '<h2>'.Yii::t('edm', 'Reject signing').'</h2>',
]);
?>
<div class="panel-body">
<?=Html::beginForm(Url::toRoute(['/edm/foreign-currency-operation-wizard/reject-signing'])); ?>
<?=Html::hiddenInput('document-id', null, ['id' => 'fcoRejectSigningModalId']) ?>
<div class="form-group col-lg-12">
    <?=Html::label(Yii::t('edm', 'Reason'))?>
<?= Html::textarea('businessStatusComment', '', ['class' => 'form-control col-lg-6']) ?>
</div>
<div class="form-group col-lg-12">
<?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success']) ?>
</div>
<?= Html::endForm(); ?>
</div>
<?php Modal::end(); ?>
