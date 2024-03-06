<?php
use yii\helpers\Html;
?>

<div class="finzip-today counters-today-subblock">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="counters-label"><?= Yii::t('app/menu', 'Free Format') ?></span>
        </div>
        <div class="panel-body">
            <?php foreach($finzipDocs as $doc) { ?>
                <ul>
                    <li>
                        <?=Html::a($doc['title'], $doc['url'])?>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </div>
</div>