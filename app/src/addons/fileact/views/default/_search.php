<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\document\DocumentSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $filterStatus boolean */
?>

<div class="pull-right">
    <?php
        echo Html::a('',
            '#',
            [
                'class' => 'btn-columns-settings glyphicon glyphicon-cog',
                'title' => Yii::t('app', 'Columns settings')
            ]
        );
    ?>
</div>