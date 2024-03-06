<?php
    use yii\helpers\Html;
?>

<div class="swiftfin-today counters-today-subblock">
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <span class="counters-label">SwiftFin</span>
        </div>
        <div class="panel-body">
            <ul>
                <?php foreach($swiftDocs as $doc) { ?>
                    <li>
                        <?=Html::a($doc['title'], $doc['url'])?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>