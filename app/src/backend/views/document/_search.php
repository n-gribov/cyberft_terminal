<?php

use common\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model common\document\DocumentSearch
 * @var $form yii\widgets\ActiveForm
 * @var $enableDeletableDocumentsFilter boolean
 * @var $deletableDocumentsFilterValue boolean
 */
?>

<div class="pull-right">
    <?php
        if (isset($enableDeletableDocumentsFilter) && $enableDeletableDocumentsFilter) {
            $form = ActiveForm::begin([
                'method' => 'get',
                'action' => Url::toRoute(['index']),
                'fieldConfig' => [
                    'options' => [
                        'template' => '{input}',
                        'tag'      => 'span',
                    ],
                ],
                'options' => ['style' => 'display: inline']
            ]);
            echo $form
                ->field($model, 'showDeletableOnly', ['template' => '{input}'])
                ->checkbox([
                    'label' => Yii::t('app', 'Show deletable documents only'),
                    'labelOptions' => ['style' => 'font-weight: normal; margin-right: 15px'],
                    'onChange' => '$(this).closest("form").submit();'
                ]);
            ActiveForm::end();
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

?>