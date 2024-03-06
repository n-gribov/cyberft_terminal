<?php
    use yii\helpers\Html;
?>

<div class="edm-today counters-today-subblock">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="counters-label">
                <?= Yii::t('edm', 'Banking') ?>
            </span>
        </div>
        <div class="panel-body">
            <ul>
                <?php foreach($edmToday as $counter) { ?>
                    <li>
                        <?=Html::a($counter['title'], $counter['url'])?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>