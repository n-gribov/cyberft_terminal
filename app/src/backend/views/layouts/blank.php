<?php

/**
 * Пустой шаблон, без подключения основной стилизации
 */

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<?php $this->beginPage() ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
    <html lang="<?=Yii::$app->language?>">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=10;IE=11"/>
        <meta charset="<?=Yii::$app->charset?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?=Html::csrfMetaTags()?>
        <title><?=Html::encode($this->title)?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php
            $this->beginBody();
            print $content;
            $this->endBody();
        ?>
    </body>
    </html>
<?php $this->endPage() ?>