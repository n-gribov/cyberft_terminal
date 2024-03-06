<?php
/** @var array $values */
use yii\helpers\Html;
?>
<table class="values-table">
    <?php foreach ($values as $value) : ?>
        <tr>
            <td><?= $value === null ? '' : Html::encode($value) ?></td>
        </tr>
    <?php endforeach ?>
</table>
