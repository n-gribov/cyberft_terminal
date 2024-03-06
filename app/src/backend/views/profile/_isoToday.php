<?php
    use yii\helpers\Html;
?>

<div class="iso-today counters-today-subblock">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="counters-label">ISO20022</span>
        </div>
        <div class="panel-body">
            <ul>
                <?php foreach($isoDocs as $doc) { ?>
                    <li>
                        <?=Html::a($doc['title'], $doc['url'])?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>