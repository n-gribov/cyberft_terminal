<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $errors string[] */

?>

<?php foreach ($errors as $error) : ?>
    <p><?= Html::encode($error) ?></p>
<?php endforeach ?>
