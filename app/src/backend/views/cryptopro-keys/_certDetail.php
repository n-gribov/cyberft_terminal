<?php

/* @var $this yii\web\View */
/* @var $cert array */

?>

<div class="panel-heading">
    <?= Yii::t('app/cert', 'Certificate'); ?>
</div>
<strong><?= Yii::t('app/cert', 'Organization'); ?></strong>: <?= (isset($cert['subject']['O'])) ? $cert['subject']['O'] : ''; ?><br />
<strong><?= Yii::t('app/cert', 'Subdivision'); ?></strong>: <?= (isset($cert['subject']['OU'])) ? $cert['subject']['OU'] : ''; ?><br />
<strong><?= Yii::t('app/cert', 'Common Name (CN)'); ?></strong>: <?= (isset($cert['subject']['CN'])) ? $cert['subject']['CN'] : ''; ?><br />
<strong><?= Yii::t('app/cert', 'Email'); ?></strong>: <?= (isset($cert['subject']['emailAddress'])) ? $cert['subject']['emailAddress'] : ''; ?>

