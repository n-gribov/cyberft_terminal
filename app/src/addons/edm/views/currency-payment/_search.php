<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

?>

<div class="pull-right">
    <?php
    echo Html::a('',
        '#',
        [
            'class' => 'btn-find-modal glyphicon glyphicon-search',
            'title' => Yii::t('edm', 'Find')
        ]
    );
    echo Html::a('',
        '#',
        [
            'class' => 'btn-columns-settings glyphicon glyphicon-cog',
            'title' => Yii::t('app', 'Columns settings')
        ]
    );
    ?>
</div>

<?php

$this->registerCss('
    .btn-find-modal {
        margin-right: 5px;
    }
');

$script = <<< JS
    // Вызов модальной формы поиска по журналу выписок
    $('.btn-find-modal').on('click', function(e) {
        e.preventDefault();
        $('#searchModal').modal('show');
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);
