<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

Modal::begin([
    'header' => '<h2>'.Yii::t('edm', 'Reject signing').'</h2>',
    'toggleButton' => [
        'tag' => 'button',
        'class' => 'btn btn-warning',
        'label' => Yii::t('edm', 'Reject signing'),
    ]
]);
?>
<div class="panel-body">
<?=Html::beginForm(Url::toRoute([$url])); ?>
<?=Html::hiddenInput('id', $id) ?>
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
