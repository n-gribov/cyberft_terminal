<?php

use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationSearch;
use addons\edm\models\Statement\StatementSearch;
use common\document\DocumentSearch;
use common\widgets\InlineHelp\InlineHelp;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm as ActiveForm2;

/* @var $this View */
/* @var $model DocumentSearch */
/* @var $form ActiveForm2 */
/* @var $filterStatus boolean */
?>
<div class="pull-right">
<?php
    if ($model instanceof StatementSearch) {
        echo Html::checkbox('hide-null-turnover',
                $hideNullTurnovers,
                ['id' => 'hide-null-turnover', 'style' => 'margin-right: 5px']
        );
            echo Html::label(Yii::t('edm', 'Hide documents with null turnovers'),
                'hide-null-turnover',
                ['style' => 'font-weight: normal; margin-right: 15px']
        );
    }

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


    // Получение номера виджета помощи в зависимости от страницы
    $widgetId = null;

    if ($model instanceOf StatementSearch) {
        $widgetId = 'edm-statement-journal';
    } elseif ($model instanceOf ForeignCurrencyOperationSearch) {
        $widgetId = 'edm-foreign-currency-operation-journal';
    }

    if ($widgetId) {
        echo InlineHelp::widget(['widgetId' => $widgetId, 'setClassList' => ['edm-journal-wiki-widget']]);
    }
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
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, View::POS_READY);

?>
