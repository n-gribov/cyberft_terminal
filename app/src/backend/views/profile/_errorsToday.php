<?php
    use yii\helpers\Html;
?>

<div class="counters-today-subblock errors-today">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="counters-label"><?= Yii::t('app/diagnostic', 'Errors') ?></span>
        </div>
        <div class="panel-body">
            <ul>
                <?php foreach($errors as $error) { ?>
                    <li>
                        <?=Html::a($error['title'], $error['url'])?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>