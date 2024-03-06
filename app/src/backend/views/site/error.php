<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<?php if ($message) : ?>
    <p><?= nl2br(Html::encode($message)) ?></p>
<?php else : ?>
    <p>
        <?php
            if (!empty($exception) && $exception->getMessage()) {
                echo $exception->getMessage();
            } else {
                echo Yii::t('app/error', 'An error has occurred during communication with the server');
            }
        ?>
    </p>
<?php endif ?>