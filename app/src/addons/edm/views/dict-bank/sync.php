<?php

use yii\helpers\Html;
use yii\log\Logger;
use yii\web\View;

/** @var array $log */
/** @var View $this */

$this->title = Yii::t('app/menu', 'Banks Directory');
?>
<?= Html::a(Yii::t('app', 'Back'), ['index'], ['name' => 'send', 'class' => 'btn btn-success'])?>
<div style="margin-top: 20px;">
<?php
    $status = null;
    foreach($log as $i => $message) :
?>
    <?php if ($i == 0 && $status === null) : ?>
        <div class="alert alert-success">
    <?php elseif ($i > 0 && $status !== $message[1] && $message[1] === Logger::LEVEL_INFO ) : ?>
        </div><div class="alert alert-success">
    <?php elseif ($i > 0 && $status !== $message[1] && $message[1] === Logger::LEVEL_ERROR ) : ?>
        </div><div class="alert alert-danger">
    <?php endif ?>
    <p><?= $message[0] ?></p>
    <?php $status = $message[1]?>
<?php endforeach ?>
    </div>
</div>
