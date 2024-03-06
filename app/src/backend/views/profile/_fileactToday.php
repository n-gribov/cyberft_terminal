<?php
    use yii\helpers\Html;
?>

<div class="fileact-today counters-today-subblock">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="counters-label">Fileact</span>
        </div>
        <div class="panel-body">
            <?php foreach($fileactDocs as $doc) { ?>
                <ul>
                    <li>
                        <?=Html::a($doc['title'], $doc['url'])?>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </div>
</div>